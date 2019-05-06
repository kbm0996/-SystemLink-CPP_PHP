#include <cstdio>
#include "CallHttp.h"

void main()
{
	_wsetlocale(LC_ALL, L"");

	char outData[1024] = { 0, };

	//JSON 양식
	//"{ \"id\":\"12d3\", \"pass\":\"1234\", \"nickname\":\"bsds\" }"
	CallHttp(L"127.0.0.1", L"http://127.0.0.1:80/0_Complete/__History/171126/auth_login.php", "id='아이디'&pass='비밀'", outData, "GET");
	//int err = HttpCall(L"127.0.0.1", L"http://127.0.0.1:80/Register.php", "{\"id\": \"gmf\",\"password\" : \"사용패스워2d드\"}", outData);
	wprintf(L"\n%s \n", outData);
}