<?php
include "_LIB/lib_DB.php";
include_once "_LIB/lib_Log.php";

/*---------------------------------------------
	Request Param - id,pass,nickname
	Response Param - resultcode, accountno
---------------------------------------------*/
// 프로파일링
$PF = Profiling::getInstance($cnf_PROFILING_LOG_URL, $_SERVER['PHP_SELF']);
$PF->startCheck(PF_PAGE);

if(!isset($_GET['id']) || !isset($_GET['pass']) || !isset($_GET['nickname']))
	exit;

$PF->startCheck(PF_MYSQL_CONN);
DB_Connect();
$PF->stopCheck(PF_MYSQL_CONN);

$ID			= mysqli_real_escape_string($g_DB, $_GET['id']);
$Pass		= mysqli_real_escape_string($g_DB, $_GET['pass']);
$Nickname	= mysqli_real_escape_string($g_DB, $_GET['nickname']);

// 아이디 확인
$Query		= "SELECT COUNT(accountno) AS Cnt FROM account WHERE id = '{$ID}'";
$PF->startCheck(PF_MYSQL);
$Result		= DB_ExecQuery($Query);
$PF->stopCheck(PF_MYSQL, "Select");

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
	
	$PF->startCheck(PF_MYSQL);
	$AccountNo = DB_TransactionQuery($arrQry);
	$PF->stopCheck(PF_MYSQL, "Insert");

	echo "AccountNo : " . $AccountNo . " Regist Success <br>";
}

DB_Disconnect();

$PF->stopCheck(PF_PAGE, "Total Page");
$PF->LOG_Save();
?>