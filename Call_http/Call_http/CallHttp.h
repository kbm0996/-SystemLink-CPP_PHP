#ifndef __CALL_SOCKET__
#define __CALL_SOCKET__

#pragma comment(lib, "ws2_32.lib")
#include <WinSock2.h>
#include <WS2tcpip.h>
#include <locale.h>
#include <Windows.h>


// JSON 데이터 형식 "{ \"id\":\"ttsdstt\", \"password\":\"ttsdsdt\", \"nickname\":\"bsds\" }"
// HttpCall(L"127.0.0.1", L"http://127.0.0.1:80/auth_login.php", "id=12d3&pass=1234", outData, "GET");

#define df_HTTP_BUF_SIZE 1024

// http ¸Þ½ÃÁö Àü¼Û
int		CallHttp(WCHAR * HostIP, WCHAR * URL, char * PostData, char * OutRecvData, char * MethodType = "POST");

SOCKET	ConnectHttp(WCHAR *HostIP, int iPort);

// Domain -> IP
bool	Domain2IP(WCHAR * szDomain, WCHAR *outIP);	 

// Make Post Header & Body
void	HttpMakePostData(char * Path, char * Host, char * Content, size_t ContentLangth, char * out);

// UTF16 -> UTF8
char *	ConvertWC2C(const WCHAR * inStr);	

// UTF8 -> UTF16
WCHAR *	ConvertC2WC(const char * inStr);	

#endif