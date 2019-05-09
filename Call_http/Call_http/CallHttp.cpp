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

	// 1. TCP 소켓 생성
	// 2. RecvTimeout, SendTimeout 설정(5초 ~10초)
	//	 블럭소켓이므로 서버가 반응이 없을경우를 대비
	// 4. 웹서버로 Connect
	WCHAR * szIP = new WCHAR[INET_ADDRSTRLEN];
	Domain2IP(HostIP, szIP);

	SOCKET Socket = ConnectHttp(szIP, 80);
	if (Socket == INVALID_SOCKET)
		return -1;

	// 3. HTTP 프로토콜의 데이터 생성.
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
	// 5. 3번에서 만들어진 데이터를 send
	if (send(Socket, szData, strlen(szData), 0) == SOCKET_ERROR)
	{
		err = WSAGetLastError();
		LOG(L"HTTP_LOG", LOG_ERROR, L"send() Error : %d", err);
		delete[] szIP;
		delete[] szHost;
		delete[] szPath;
		return err;
	}

	// 6. send 후 바로 recv 호출
	// 접속이 끊어질 때까지 일정 길이만 받음. 받는 길이는 컨텐츠 상황에 맞도록 설정. (ex 1024)recv
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

			/* HTTP_HEADER 분석 */
			if (iBodyLength == -1)
			{
				// 7. 데이터를 받은 후 HTTP 헤더에서 완료코드 얻기.
				//	받은 데이터에서 첫번째 0x20 코드를 찾아서 그 다음 0x20 까지가 완료 코드
				//	HTTP/1.1 200 OK     // 200이 완료코드
				ptStrPos = strchr(szRecvBuf, 0x20); // 0x20 : space
				if (ptStrPos != NULL)
				{
					ptStrPos += 1;
					strncpy_s(szCheck, sizeof(szCheck), ptStrPos, strchr(ptStrPos, 0x20) - ptStrPos);
					err = atoi(szCheck);
					if (err != 200)
						break;
					///printf("%s\n%d\n\n", ptStrPos, err);

					// 8. 데이터를 받은 후 HTTP 헤더에서 Content-Length: 얻기.
					//받은 데이터에서 첫번째 Content_Length : 문자열을 찾아서, 그 다음 0x0d까지가 컨텐츠(BODY) 의 길이.
					// Content - Length : 159	// 이 숫자를 변환하여 BODY 길이 저장
					ptStrPos = strstr(szRecvBuf, "Content-Length:");
					if (ptStrPos != NULL)
					{
						ptStrPos += 16;
						strncpy_s(szCheck, sizeof(szCheck), ptStrPos, strchr(ptStrPos, 0x0d) - ptStrPos);
						iBodyLength = atoi(szCheck);
						///printf("%s\n%d\n\n", ptStrPos, iBodyLength);

						// 9. 헤더의 끝(Body 시작) 확인
						// \r\n\r\n 을 찾아서 그 아래 부분만을 BODY 로 얻어냄.
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

	//https://github.com/kbm0996/MyLibrary/blob/master/MyLib/MyLib/CNetServer.cpp#L26
	int		err;

	// 1. TCP 소켓 생성
	SOCKET Socket = socket(AF_INET, SOCK_STREAM, 0);
	if (Socket == INVALID_SOCKET)
	{
		err = WSAGetLastError();
		LOG(L"HTTP_LOG", LOG_ERROR, L"socket() Error : %d", err);
		return INVALID_SOCKET;
	}

	// 2. RecvTimeout, SendTimeout 설정 (5초 ~ 10초)
	//   블럭소켓이므로 서버가 반응이 없을 경우를 대비
	timeval time = { 5, 0 }; // 5.0 second
	setsockopt(Socket, SOL_SOCKET, SO_RCVTIMEO, (char*)&time, sizeof(int));
	setsockopt(Socket, SOL_SOCKET, SO_SNDTIMEO, (char*)&time, sizeof(int));

	BOOL	bOptval = TRUE;
	setsockopt(Socket, IPPROTO_TCP, TCP_NODELAY, (char *)&bOptval, sizeof(bOptval));

	// connect()
	//connect() 실패시 Blocking Socket일 경우 20초 정도 대기. 이 시간을 단축시키기 위한 Non-Blocking 설정
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

// Domain → IP 
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

// UTF16 → UTF8. 사용 후 delete 필수
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

// UTF8 → UTF16. 사용 후 delete 필수
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
