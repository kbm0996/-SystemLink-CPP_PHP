#include <cstdio>
#include "CallHttp.h"

using namespace mylib;

void main()
{
	_wsetlocale(LC_ALL, L"");
	int err;
	WSADATA wsa;
	if (WSAStartup(MAKEWORD(2, 2), &wsa) != 0)
	{
		int err = WSAGetLastError();
		wprintf(L"WSAStartup() Error : %d", err);
		return;
	}

	char outData[1024] = { 0, };

	//JSON ���
	//"{ \"id\":\"12d3\", \"pass\":\"1234\", \"nickname\":\"bsds\" }"
	err = CallHttp(L"127.0.0.1", L"http://127.0.0.1:80/0_Complete/__History/171126/auth_login.php", GET, "id='���̵�'&pass='���'", outData, sizeof(outData));
	err = CallHttp(L"127.0.0.1", L"http://127.0.0.1:80/Register.php", POST, "{\"id\": \"gmf\",\"password\" : \"����н���2d��\"}", outData, sizeof(outData));
	

	/* Test End */
	//err = ConvertDomain2IP(outData, sizeof(outData), L"www.naver.com");
	
	wprintf(L"\n%s %d \n", outData, err);


	WSACleanup();
}