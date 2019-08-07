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

	char outData[1024];

	//JSON 양식
	//"{ \"id\":\"12d3\", \"pass\":\"1234\", \"nickname\":\"bsds\" }"

	/*------------------------------
		0. Function 
	-------------------------------*/
	//err = ConvertDomain2IP(outData, sizeof(outData), L"www.naver.com");

	/*------------------------------
		1. Register.php
	-------------------------------*/
	//err = CallHttp(L"127.0.0.1", L"http://127.0.0.1:80/Register.php", POST, "{\"id\": \"gmf\",\"password\" : \"사용패스워2d드\"}", outData, sizeof(outData));

	/*------------------------------
		2. Login.php
	-------------------------------*/
	err = CallHttp(L"127.0.0.1", L"http://127.0.0.1:80/login.php", POST, "{\"id\": \"gmf\",\"password\" : \"사용패스워2d드\"}", outData, sizeof(outData));

	/*------------------------------
		3. Session.php
	-------------------------------*/
	//err = CallHttp(L"127.0.0.1", L"http://127.0.0.1:80/Session.php", POST, "{\"accountno\" : \"1\",\"session\" : \"721f9558aaa7A9B5131a750f0aE876a1\"}", outData, sizeof(outData));
	
	/*------------------------------
		4. StageClear.php
	-------------------------------*/
	//err = CallHttp(L"127.0.0.1", L"http://127.0.0.1:80/StageClear.php", POST, "{\"accountno\": \"1\",\"session\": \"e393be6b2ce74e04c4122b64cEbf18a0\",\"stageid\":\"2\"}", outData, sizeof(outData));

	/*------------------------------
		5. UserInfo.php
	-------------------------------*/
	//err = CallHttp(L"127.0.0.1", L"http://127.0.0.1:80/UserInfo.php", POST, "{\"accountno\": \"1\",\"session\" : \"e393be6b2ce74e04c4122b64cEbf18a0\"}", outData, sizeof(outData));


	printf("%s\n", outData);
	//wprintf(L"%s\n", outData);
	WSACleanup();
}