<?php
include "_Config.php";
/*---------------------------------------------
	Request Param - accountno, action, message
	Response Param 
---------------------------------------------*/
//------------------------------------------------------------
// 시스템 로그 남기는 함수 / DB, 테이블 정보는 _Config.php 참고.
//
// POST 방식으로 로그 스트링을 저장한다.
//
// $_POST['AccountNo']	: 유저
// $_POST['Action']		: 액션구분
// $_POST['Message']	: 로그스트링
//------------------------------------------------------------

// AccountNo, Action, Message 인자 확인 및 없을시 디폴트 값 생성
if(!isset($_POST['accountno']))	$_POST['accountno'] = "null";
if(!isset($_POST['action']))	$_POST['action'] = "null";
if(!isset($_POST['message']))	$_POST['message'] = "null";

$g_LOGDB = mysqli_connect($g_LOGDB_IP, $g_LOGDB_ID, $g_LOGDB_PASS, $g_LOGDB_NAME, $g_DB_PORT);
if(!$g_LOGDB)
{
	echo "ERROR # mysqli_connect() : " . mysqli_connect_error();
	exit;
}

mysqli_set_charset($g_LOGDB, "utf8");

$AccountNo  = mysqli_real_escape_string($g_LOGDB, $_POST['accountno']);
$Action     = mysqli_real_escape_string($g_LOGDB, $_POST['action']);
$Message    = mysqli_real_escape_string($g_LOGDB, $_POST['message']);

// 월별 테이블 이름 만들어서 로그 INSERT
$TableName = "SystemLog_".@date("Ym");
$Query = "INSERT INTO {$TableName} (date, accountno, action, message) VALUES (NOW(), '{$AccountNo}', '{$Action}', '{$Message}')";
$Result = mysqli_query($g_LOGDB, $Query);

// 테이블 없을시 (errno = 1146) 테이블 생성 후 재 입력
if ( !$Result && mysqli_errno($g_LOGDB) == 1146 ) 
{
	mysqli_query($g_LOGDB, "CREATE TABLE {$TableName} LIKE systemlog_template");
	mysqli_query($g_LOGDB, $Query);
}

if(isset($g_LOGDB))
	mysqli_close($g_LOGDB);
?>