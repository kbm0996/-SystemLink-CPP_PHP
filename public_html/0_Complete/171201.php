<?php
///////////////////////////////////////////////////
// 171201 php
///////////////////////////////////////////////////

function QuitError($ErrorCode, $ExtMessage = '')
{
	// 1. ResJSON_Error
	// 2. DB_Disconnect(); // ������ MySQL�� ���� ����� result ��ü�� ��ȯ�ǹǷ� ��� ��. ������ ������ ����
	exit();
}

function ResponseJSON_Error($ErrorCode, $ExtMessage)
{
	// 1. JSON���ڵ�
	// 2. SystemLOG
	// 3. �����ڵ�, �����޽��� Echo
}

// Ȱ�� : ���� ��Ȳ �� ������ ���� 
// ex. if($password='') quiterror(~~,~~);

/* Startup */
// trim($string); : ���ڿ��� ���� ���� ����
// '    abc'	�� 'abc' 
// ' ddd    '	�� 'ddd'
// '          '	�� ''
// ex)	$id = trim($id)
//		if($id='') quiterror(~~,~~);

/* Register */
// ���� ID�� ���ÿ� ���ԵǾ� ����ȭ������ ���� Insert ���߿� ������ ���� ���
//	df_RESULT_REGISTER_ID_ERROR

/* Login */
//

/* StageClear */
// 1. data_stage�� stageid�� �ִ��� �˻�
// 2. clearstage(�� �׸� �� index)�� stageid�� �ִ��� �˻�. ������ ����ġX, ������ ����ġO
//		exp+=clearstageexp
// 3. ������ó��. ������������ �����ϰ�, �۰ų� ���� �ܰ� �� ���� ū �ϳ� �̰�, ���� limit 
//  ������ ó���ÿ� �ߴܵ� ��쵵 ����Ͽ� Ʈ����� ó���� ���ִ� ���� ����

/* ��Ȧ�� �׽�Ʈ */
// ����Ƽ�� Ŭ�� �ĵ� ����� ��ɺ� ��ư ���� �� �׽�Ʈ

/*
 �����ڴ� C#����
 Wake�� ����Ƽ�� ���ִ� ��

  �� �� �ϳ��� �ϸ� ��
*/
?>