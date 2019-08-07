<?php
include_once "_LIB/lib_DB.php";
include_once "_LIB/lib_Key.php";
include_once "_Lib/lib_ErrorHandler.php";
include_once "_LIB/lib_Log.php";
$g_AccountNo = 0;
// ���� �α� & �������Ϸ� 
$GameLog = GAMELog::getInstance($cnf_GAME_LOG_URL);
$PF = Profiling::getInstance($cnf_PROFILING_LOG_URL, $_SERVER['PHP_SELF']);

// * file_get_contents("php://input");
// POST ������� ���� http ��Ŷ�� body�� ������ �� �ִ�. �Ϲ������� PHP������ form��� ������ �̿��ϴ� ��찡 ��κ������� body�� JSON���� ���� raw data�� �־ ������ ��쿡 �����ܿ��� �����Ϸ��� ���� ���� ��ɾ �̿��ؾ� �Ѵ�.
$_RequestData = file_get_contents("php://input");

// php 7.2�������� �ѱ� �����͸� ������ JSON_ERROR_UTF8 �߻�
if(function_exists('mb_detect_encoding'))
{
	if(mb_detect_encoding($_RequestData, "EUC-KR, UTF-8, ASCII") == "EUC-KR")
	{
		 $_RequestData = iconv("EUC-KR", "UTF-8//TRANSLIT", $_RequestData);
		 //$_RequestData = utf8_encode($_RequestData);
	}
}


$_JSONData = json_decode($_RequestData, true);
if(!is_array($_JSONData))	
{
	echo "JSON Data Error : ".json_last_error()."\n";
	exit;
}

$PF->startCheck(PF_PAGE);
$PF->startCheck(PF_MYSQL_CONN);
DB_Connect();

$PF->stopCheck(PF_MYSQL_CONN);

?>