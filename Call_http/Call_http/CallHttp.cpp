#include "CallHttp.h"
#include "CSystemLog.h"

int CallHttp(WCHAR * HostIP, WCHAR * URL, char * PostData, char * OutRecvData, char * MethodType)
{
	int err = 0;

	WSADATA wsa;
	if (WSAStartup(MAKEWORD(2, 2), &wsa) != 0)
	{
		err = WSAGetLastError();
		LOG(L"HTTP_LOG", LOG_ERROR, L"WSAStartup() Error : %d", err);
		return false;
	}

	// 1. TCP ���� ����
	// 2. RecvTimeout, SendTimeout ����(5�� ~10��)
	//	 �������̹Ƿ� ������ ������ ������츦 ���
	// 4. �������� Connect
	WCHAR * szIP = new WCHAR[INET_ADDRSTRLEN];
	Domain2IP(HostIP, szIP);

	SOCKET Socket = ConnectHttp(szIP, 80);
	if (Socket == INVALID_SOCKET)
		return -1;

	// 3. HTTP ���������� ������ ����.
	char szData[df_HTTP_BUF_SIZE];
	ZeroMemory(szData, sizeof(szData));

	char * szHost = ConvertWC2C(HostIP);
	char * szPath = ConvertWC2C(URL);
	if (strcmp(MethodType, "POST") == 0)
	{
		HttpMakePostData(szPath, szHost, PostData, strlen(PostData), szData);
	}
	else if (strcmp(MethodType, "GET") == 0)
	{
		sprintf_s(szData, df_HTTP_BUF_SIZE, "%sGET %s?%s HTTP/1.1\r\nHost: %s\r\n\r\n", szData, szPath, PostData, szHost);
	}
	else
	{
		delete[] szIP;
		delete[] szHost;
		delete[] szPath;
		return -1;
	}

	///printf("%s", szData);
	// 5. 3������ ������� �����͸� send
	if (send(Socket, szData, strlen(szData), 0) == SOCKET_ERROR)
	{
		err = WSAGetLastError();
		LOG(L"HTTP_LOG", LOG_ERROR, L"send() Error : %d", err);
		delete[] szIP;
		delete[] szHost;
		delete[] szPath;
		return err;
	}

	// 6. send �� �ٷ� recv ȣ��
	// ������ ������ ������ ���� ���̸� ����. �޴� ���̴� ������ ��Ȳ�� �µ��� ����. (ex 1024)recv
	char szRecvBuf[df_HTTP_BUF_SIZE * 2];		// RecvData
	char * ptStrPos = NULL;		// RecvData Pos
	char szCheck[10];			// Completion Code & Body Length
	int iHeaderLength = 0;
	int iBodyLength = -1;
	DWORD dwTransferred = 0;

	ZeroMemory(szRecvBuf, sizeof(szRecvBuf));
	ZeroMemory(szCheck, sizeof(szCheck));

	while (1)
	{
		FD_SET ReadSet;
		FD_ZERO(&ReadSet);
		FD_SET(Socket, &ReadSet);
		select(0, &ReadSet, NULL, NULL, NULL);
		if (FD_ISSET(Socket, &ReadSet) > 0)
		{
			int iRecvLength = recv(Socket, szRecvBuf + dwTransferred, df_HTTP_BUF_SIZE * 2 - dwTransferred, 0);
			if (iRecvLength <= 0)
				break;
			dwTransferred += iRecvLength;
			///printf("%s \n\n", szRecvBuf);

			/* HTTP_HEADER �м� */
			if (iBodyLength == -1)
			{
				// 7. �����͸� ���� �� HTTP ������� �Ϸ��ڵ� ���.
				//	���� �����Ϳ��� ù��° 0x20 �ڵ带 ã�Ƽ� �� ���� 0x20 ������ �Ϸ� �ڵ�
				//	HTTP/1.1 200 OK     // 200�� �Ϸ��ڵ�
				ptStrPos = strchr(szRecvBuf, 0x20); // 0x20 : space
				if (ptStrPos != NULL)
				{
					ptStrPos += 1;
					strncpy_s(szCheck, sizeof(szCheck), ptStrPos, strchr(ptStrPos, 0x20) - ptStrPos);
					err = atoi(szCheck);
					if (err != 200)
						break;
					///printf("%s\n%d\n\n", ptStrPos, err);

					// 8. �����͸� ���� �� HTTP ������� Content-Length: ���.
					//���� �����Ϳ��� ù��° Content_Length : ���ڿ��� ã�Ƽ�, �� ���� 0x0d������ ������(BODY) �� ����.
					// Content - Length : 159	// �� ���ڸ� ��ȯ�Ͽ� BODY ���� ����
					ptStrPos = strstr(szRecvBuf, "Content-Length:");
					if (ptStrPos != NULL)
					{
						ptStrPos += 16;
						strncpy_s(szCheck, sizeof(szCheck), ptStrPos, strchr(ptStrPos, 0x0d) - ptStrPos);
						iBodyLength = atoi(szCheck);
						///printf("%s\n%d\n\n", ptStrPos, iBodyLength);

						// 9. ����� ��(Body ����) Ȯ��
						// \r\n\r\n �� ã�Ƽ� �� �Ʒ� �κи��� BODY �� ��.
						ptStrPos = strstr(szRecvBuf, "\r\n\r\n");
						if (ptStrPos != NULL)
						{
							ptStrPos += 4;
							iHeaderLength = ptStrPos - szRecvBuf;
						}
					}
				}
			}

			if (iRecvLength >= iHeaderLength + iBodyLength)
			{
				strcpy_s(OutRecvData, iBodyLength + 1, ptStrPos);
				///printf("%s\n\n", OutRecvData);
				break;
			}
		}
	}

	delete[] szIP;
	delete[] szHost;
	delete[] szPath;
	closesocket(Socket);
	WSACleanup();
	return err;
}

SOCKET ConnectHttp(WCHAR * HostIP, int iPort)
{
	int		err;

	// 1. TCP ���� ����
	SOCKET Socket = socket(AF_INET, SOCK_STREAM, 0);
	if (Socket == INVALID_SOCKET)
	{
		err = WSAGetLastError();
		LOG(L"HTTP_LOG", LOG_ERROR, L"socket() Error : %d", err);
		return INVALID_SOCKET;
	}

	// 2. RecvTimeout, SendTimeout ���� (5�� ~ 10��)
	//   �������̹Ƿ� ������ ������ ���� ��츦 ���
	timeval time = { 5, 0 }; // 5.0 second
	setsockopt(Socket, SOL_SOCKET, SO_RCVTIMEO, (char*)&time, sizeof(int));
	setsockopt(Socket, SOL_SOCKET, SO_SNDTIMEO, (char*)&time, sizeof(int));

	BOOL	bOptval = TRUE;
	setsockopt(Socket, IPPROTO_TCP, TCP_NODELAY, (char *)&bOptval, sizeof(bOptval));

	// connect()
	//connect() ���н� Blocking Socket�� ��� 20�� ���� ���. �� �ð��� �����Ű�� ���� Non-Blocking ����
	u_long uOptval = TRUE;
	ioctlsocket(Socket, FIONBIO, &uOptval);

	SOCKADDR_IN ServerAddr;
	ZeroMemory(&ServerAddr, sizeof(ServerAddr));
	ServerAddr.sin_family = AF_INET;
	ServerAddr.sin_port = htons(iPort);
	InetPton(AF_INET, HostIP, &ServerAddr.sin_addr);
	if (connect(Socket, (SOCKADDR *)&ServerAddr, sizeof(ServerAddr)) == SOCKET_ERROR)
	{
		err = WSAGetLastError();
		if (err != WSAEWOULDBLOCK)
		{
			LOG(L"HTTP_LOG", LOG_ERROR, L"connect() Error : %d", err);
			return INVALID_SOCKET;
		}
	}

	uOptval = FALSE;
	ioctlsocket(Socket, FIONBIO, &uOptval);

	return Socket;
}

void HttpMakePostData(char * Path, char * Host, char * Content, size_t ContentLangth, char *out)
{
	sprintf_s(out, df_HTTP_BUF_SIZE, "%sPOST %s HTTP/1.1\r\n", out, Path);
	sprintf_s(out, df_HTTP_BUF_SIZE, "%sUser-Agent: Fiddler\r\n", out);
	sprintf_s(out, df_HTTP_BUF_SIZE, "%sContent-Length: %d\r\n", out, ContentLangth);
	sprintf_s(out, df_HTTP_BUF_SIZE, "%sHost: %s\r\n", out, Host);
	sprintf_s(out, df_HTTP_BUF_SIZE, "%sContent-Type: application/x-www-form-urlencoded\r\n", out);
	sprintf_s(out, df_HTTP_BUF_SIZE, "%sConnection: close\r\n\r\n", out);
	sprintf_s(out, df_HTTP_BUF_SIZE, "%s%s", out, Content);
}

// Domain �� IP 
bool Domain2IP(WCHAR * szDomain, WCHAR *outIP)
{
	int err;
	ADDRINFOW	*pAddrInfo;
	if (GetAddrInfo(szDomain, L"0", NULL, &pAddrInfo) != 0)
	{
		err = WSAGetLastError();
		LOG(L"HTTP_LOG", LOG_ERROR, L"GetAddrInfo() Error : %d", err);
		return false;
	}

	if (InetNtop(AF_INET, &((SOCKADDR_IN *)pAddrInfo->ai_addr)->sin_addr, outIP, INET_ADDRSTRLEN) == NULL)
	{
		err = WSAGetLastError();
		LOG(L"HTTP_LOG", LOG_ERROR, L"InetNtop() Error : %d", err);
		return false;
	}

	FreeAddrInfo(pAddrInfo);
	return true;
}

// UTF16 �� UTF8. ��� �� delete �ʼ�
char * ConvertWC2C(const WCHAR* inStr)
{
	int iStrLen = wcslen(inStr);
	char * pOutStr = new char[iStrLen + 1];
	ZeroMemory(pOutStr, sizeof(char) * iStrLen + 1);

	if (WideCharToMultiByte(CP_UTF8, 0, inStr, iStrLen, pOutStr, sizeof(char) * iStrLen, NULL, NULL) == 0)
	{
		int err = GetLastError();
		LOG(L"HTTP_LOG", LOG_ERROR, L"WideCharToMultiByte() Error : %d", err);
		delete[] pOutStr;
		return nullptr;
	}
	return pOutStr;
}

// UTF8 �� UTF16. ��� �� delete �ʼ�
WCHAR * ConvertC2WC(const char * inStr)
{
	int iStrLen = strlen(inStr);
	WCHAR * pOutStr = new WCHAR[iStrLen + 1];
	ZeroMemory(pOutStr, sizeof(WCHAR) * iStrLen + 1);

	if (MultiByteToWideChar(CP_UTF8, 0, inStr, iStrLen, pOutStr, iStrLen * 2 + 1) == 0)
	{
		int err = GetLastError();
		LOG(L"HTTP_LOG", LOG_ERROR, L"MultiByteToWideChar() Error : %d", err);
		delete[] pOutStr;
		return nullptr;
	}
	return pOutStr;
}
