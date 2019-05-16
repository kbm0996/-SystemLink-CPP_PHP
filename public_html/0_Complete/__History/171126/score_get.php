<?php
include "_LIB/lib_DB.php";
include_once "_LIB/lib_Log.php";

/*---------------------------------------------
	Request Param - accountno, sessionkey
	Response Param - resultcode, score
---------------------------------------------*/
// 프로파일링
$PF = Profiling::getInstance($cnf_PROFILING_LOG_URL, $_SERVER['PHP_SELF']);
$PF->startCheck(PF_PAGE);

if(!isset($_GET['accountno']) || !isset($_GET['sessionkey']))
	exit;

$PF->startCheck(PF_MYSQL_CONN);
DB_Connect();
$PF->stopCheck(PF_MYSQL_CONN);

$AccountNo		= mysqli_real_escape_string($g_DB, $_GET['accountno']);
$SessionKey		= mysqli_real_escape_string($g_DB, $_GET['sessionkey']);


$Query = "SELECT COUNT(accountno) AS Cnt FROM session WHERE accountno = '{$AccountNo}' AND sessionkey = '{$SessionKey}' AND UNIX_TIMESTAMP(NOW()) < regtime + 300"; // 세션 갱신 시간 5분 (300초)
$PF->startCheck(PF_MYSQL);
$Result		= DB_ExecQuery($Query);
$PF->stopCheck(PF_MYSQL, "Select");

$Session	= mysqli_fetch_array($Result, MYSQL_ASSOC);
mysqli_free_result($Result);

if($Session['Cnt'] == 0)
{
	$ResultCode = false;
	$Score = 0;

	echo "Searching Session Failed <br>";
}
else
{
	$ResultCode = true;

	$Query		= "SELECT score FROM score WHERE accountno = {$AccountNo}";
	$PF->startCheck(PF_MYSQL);
	$Result		= DB_ExecQuery($Query);
	$PF->stopCheck(PF_MYSQL, "Select");

	$Score		= mysqli_fetch_array($Result, MYSQLI_ASSOC);
	mysqli_free_result($Result);

	echo "AccountNo : " . $AccountNo . " SessionKey : " . $SessionKey . " Score : " . $Score['score'] . "<br>";
}

$g_AccountNo = $AccountNo;

DB_Disconnect();
$PF->stopCheck(PF_PAGE, "Total Page");
$PF->LOG_Save();
?>