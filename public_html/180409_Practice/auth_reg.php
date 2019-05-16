<?php
include "_LIB/lib_DB.php";

DB_Connect();
/*-----------------------------------------------------------
Fiddler실행 - 우측Composer탭 - Method:POST로 설정
User-Agent:Fiddler
Host: localhost:3306
Content-Length: 20
Content-Type: application/x-www-form-urlencoded 
 이 항목들을 넣어야 php가 post방식으로 넣었따는 것을 인식 결과도 FIDDLER에서 확인해야함
-----------------------------------------------------------*/
// POST방식
//$id			= $_POST['id'];
//$pass		= $_POST['pass'];
//$nickname	= $_POST['nickname'];

/*---------------------------------------------
	Request Param - id,pass,nickname
	Response Param - resultcode, accountno
---------------------------------------------*/
/*
if(!isset($_GET['id']) || !isset($_GET['pass']) || !isset($_GET['nickname']))
	exit;
$ID			= mysqli_real_escape_string($g_DB, $_GET['id']);
$Pass		= mysqli_real_escape_string($g_DB, $_GET['pass']);
$Nickname	= mysqli_real_escape_string($g_DB, $_GET['nickname']);
*/
if(!isset($_POST['id']) || !isset($_POST['pass']) || !isset($_POST['nickname']))
{
	echo "no info failed";
	exit;
}
$ID			= mysqli_real_escape_string($g_DB, $_POST['id']);
$Pass		= mysqli_real_escape_string($g_DB, $_POST['pass']);
$Nickname	= mysqli_real_escape_string($g_DB, $_POST['nickname']);

// 아이디 확인
$Query		= "SELECT COUNT(accountno) AS Cnt FROM account WHERE id = '{$ID}'";
$Result		= DB_ExecQuery($Query);

$Account	= mysqli_fetch_array($Result, MYSQL_ASSOC);
mysqli_free_result($Result);

if($Account['Cnt'] != 0)
{
	$ResultCode = false;
	$AccountNo = null;

	echo "Regist Failed <br>";
}
else
{
	$ResultCode = true;

	$arrQry = array();

	$Query		= "INSERT INTO account (id, password, nickname) VALUES ('{$ID}', '{$Pass}', '{$Nickname}')";
	array_push($arrQry, $Query);

	$AccountNo = DB_TransactionQuery($arrQry);

	echo "AccountNo : " . $AccountNo . " Regist Success <br>";
}

DB_Disconnect();
?>