#ifndef __CALL_HTTP_H__
#define __CALL_HTTP_H__

#pragma comment(lib, "ws2_32.lib")
#include <WinSock2.h>
#include <WS2tcpip.h>
#include <locale.h>
#include <Windows.h>
#include <cstdio>

#define POST	0
#define GET		1

// JSON 데이터 형식 "{ \"id\":\"ttsdstt\", \"password\":\"ttsdsdt\", \"nickname\":\"bsds\" }"
// HttpCall(L"127.0.0.1", L"http://127.0.0.1:80/auth_login.php", "id=12d3&pass=1234", outData, "GET");


namespace mylib 
{
	//////////////////////////////////////////////////////////////////////////
	// Send Data to WebServer + Recv Data to WebServer
	//
	// Parameters:	(WCHAR*) 도메인 주소
	//				(WCHAR*) 요청할 URL
	//				(int) 메소드(POST or GET)
	//				(char*) 보낼 데이터(Message Body)	
	//				(char*) _out_ 데이터를 받을 데이터	
	//				(int) 데이터를 받을 데이터 크기
	// Return:		(int) 성공시 0, 실패시 소켓 에러
	//////////////////////////////////////////////////////////////////////////
	errno_t		CallHttp(WCHAR * szDomainAddr, WCHAR * URL, int iMethodType, char * szSendData, char * OutRecvBuffer, int OutRecvBufferSize = 1024);


	//////////////////////////////////////////////////////////////////////////
	// Make `HTTP Request Message Format`
	//
	// Parameters:	(int) 메소드(POST or GET)
	//				(char*) 요청할 URL
	//				(char*) 요청할 호스트
	//				(char*) 보낼 내용 버퍼		
	//				(int) 보낼 내용 버퍼 크기
	//				(char*) _out_ 메세지 버퍼	
	//				(int) 메세지 버퍼 크기
	// Return:
	//////////////////////////////////////////////////////////////////////////
	void	makeHttpMsg(int iMethod, char * szRequestURL, char * szRequestHostIP, char * szSendContent, size_t iContentLen, char * pOutBuf, size_t iOutbufSize);


	//////////////////////////////////////////////////////////////////////////
	// Translate Domain(WString) → IP(WString)
	//
	// Parameters:	(WCHAR*) _out_ IP		
	//				(int) IP(WSTring) 길이
	//				(WCHAR const*) 도메인
	// Return:		(int) 성공시 0, 실패시 소켓 에러
	//////////////////////////////////////////////////////////////////////////
	errno_t ConvertDomain2IP(WCHAR* _Destination, rsize_t _SizeInBytes, WCHAR const* _Source);


	//////////////////////////////////////////////////////////////////////////
	// Translate UTF16(wchar) -> UTF8(char)
	// :: 내부에서 문자열 동적 할당(new)
	//
	// Parameters:	(const WCHAR*) 변환할 문자열
	// Return:		(char*) 변환된 문자열
	//////////////////////////////////////////////////////////////////////////
	char *	ConvertWC2C(const WCHAR * inStr);


	//////////////////////////////////////////////////////////////////////////
	// Translate UTF16(wchar) -> UTF8(char)
	//
	// Parameters:	(const WCHAR*) 변환할 문자열
	//				(int) 변환할 문자열 길이
	//				(char*) 변환된 문자열을 저장할 버퍼
	//				(int) 변환된 문자열을 저장할 버퍼 크기
	// Return:		(int) 변환된 문자열의 길이
	//////////////////////////////////////////////////////////////////////////
	int	ConvertWC2C(const WCHAR * pInStr, int iInStrLen, char * pOutBuf, int iOutbufSize);


	//////////////////////////////////////////////////////////////////////////
	// Translate UTF8(char) -> UTF16(wchar)
	// :: 내부에서 문자열 동적 할당(new)
	//
	// Parameters:	(const char*) 변환할 문자열
	// Return:		(WCHAR*) 변환된 문자열
	//////////////////////////////////////////////////////////////////////////
	WCHAR *	ConvertC2WC(const char * inStr);


	//////////////////////////////////////////////////////////////////////////
	// Translate UTF8(char) -> UTF16(wchar)
	//
	// Parameters:	(const WCHAR*) 변환할 문자열
	//				(int) 변환할 문자열 길이
	//				(char*) 변환된 문자열을 저장할 버퍼
	//				(int) 변환된 문자열을 저장할 버퍼 크기
	// Return:		(int) 변환된 문자열의 길이
	//////////////////////////////////////////////////////////////////////////
	int	ConvertC2WC(const char * pInStr, int iInStrLen, WCHAR * pOutBuf, int iOutbufSize);
}
#endif