#include <cstdio>
#include "CallHttp.h"

void main()
{
	_wsetlocale(LC_ALL, L"");

	char outData[1024] = { 0, };

	//JSON ���
	//"{ \"id\":\"12d3\", \"pass\":\"1234\", \"nickname\":\"bsds\" }"
	CallHttp(L"127.0.0.1", L"http://127.0.0.1:80/0_Complete/__History/171126/auth_login.php", "id='���̵�'&pass='���'", outData, "GET");
	//int err = HttpCall(L"127.0.0.1", L"http://127.0.0.1:80/Register.php", "{\"id\": \"gmf\",\"password\" : \"����н���2d��\"}", outData);
	wprintf(L"\n%s \n", outData);
}