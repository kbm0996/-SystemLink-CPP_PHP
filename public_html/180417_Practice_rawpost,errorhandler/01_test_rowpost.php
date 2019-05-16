<?php

global $HTTP_RAW_POST_DATA;

echo "Find RAW POST DATA : " . $HTTP_RAW_POST_DATA;
echo '<br>';


// php.ini ���Ͽ�  always_populate_raw_post_data = on  ������ �־�� ��
// POST �� ������ �ϸ�, Form �����Ͱ� ��� �־�� �Ѵ�.

/* �ּ����� ��� 
User-Agent: Fiddler
Host: 127.0.0.1
Content-Type: application/x-www-form-urlencoded
Content-Length: xx
*/

// POST RAW �����ʹ� Fiddler �� ����Ͽ� �׽�Ʈ �ؾ� ��
// Fiddler �� Compser �� > POST ���� > URL �� �����̸� ���� > �Ʒ� ������ �ڽ��� ���� ��� �ۼ�

//    User-Agent: Fiddler
//    Host: 127.0.0.1
//    Content-Type: application/x-www-form-urlencoded

// �ϴ� BODY ������ �ڽ� ������ RAW �����ͷ� ���� ������ �Է� 
//-------------------------------------------------------------------



// ������ �� ����� PHP 7 ���� ������Ƿ� �������� �ʴ� ���.


echo "php://input Type RAW DATA : " . file_get_contents("php://input");



// -- ������ RAW ������ \r\n ���� ������ �и� ��Ű�� ���

// RAW ������ �κ��� �ٷ� JSON ���� ������� �ʰ� /r/n ���� ������ �и��Ͽ�
// �پ��� �뵵�� ����ϱ� ����.

// ������, JSON �����ʹ� ��ȣȭ�� �Ǹ� ������ �ٷ� RAW ��ü�� ��ȣȭ �ع�����
// ��ȣ�� Ǯ ������� ã�� ���� ���� (���� Ű�� ��� �ؾ���)

// �׷��� /r/n ���� ������ ������ ������ ȸ������ ���� �ʿ�� ������ �״� �߿�ġ ���� ����
// �� ��� �� ������ �������� ��ȣŰ�� ã�ƺ�.


// POST �����Ϳ�
/*
	System=�ý��۵�����&Contents={ ���ӵ����� }
	
	�� ���ó�� POST �κ��� �׳� ���� ���� ������� 
	
	System ������ �ý������� �⺻ ������ �ְ�
	Contents ������ ��ȣȭ�� ���� JSON �� �־ ��.

	���� /r/n ���� �и���Ų ������ ������ ����.
	
*/
/*
$BodyData = explode("\r\n", $HTTP_RAW_POST_DATA);
var_dump($BodyData);
*/








?>