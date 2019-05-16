<?php
include "_LIB/lib_DB.php";

DB_Connect();

/*---------------------------------------------
	Request Param - accountno, sessionkey
	Response Param - resultcode, score
---------------------------------------------*/

if(!isset($_GET['accountno']) || !isset($_GET['sessionkey']))
	exit;

$AccountNo		= mysqli_real_escape_string($g_DB, $_GET['accountno']);
$SessionKey		= mysqli_real_escape_string($g_DB, $_GET['sessionkey']);


$Query = "SELECT COUNT(accountno) AS Cnt FROM session WHERE accountno = '{$AccountNo}' AND sessionkey = '{$SessionKey}' AND UNIX_TIMESTAMP(NOW()) < regtime + 300"; // 세션 갱신 시간 5분 (300초)
$Result		= DB_ExecQuery($Query);

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
	$Result		= DB_ExecQuery($Query);
	$Score		= mysqli_fetch_array($Result, MYSQLI_ASSOC);
	mysqli_free_result($Result);

	echo "AccountNo : " . $AccountNo . " SessionKey : " . $SessionKey . " Score : " . $Score['score'] . "<br>";
}

$g_AccountNo = $AccountNo;

DB_Disconnect();
?>