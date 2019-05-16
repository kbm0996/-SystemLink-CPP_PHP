<?php
include "_Startup.php";

/*---------------------------------------------
	Request Param - accountno, sessionkey
				{"accountno":value,"sessionkey":"value"}
	Response Param - resultcode, score
---------------------------------------------*/
$AccountNo		= mysqli_real_escape_string($g_DB, $_JSONData['accountno']);
$SessionKey		= mysqli_real_escape_string($g_DB, $_JSONData['sessionkey']);

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

	echo "{'resultcode':''}";
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

	echo "{'resultcode':'','score':'" . $Score['score'] . "'}";
}

include "_Cleanup.php";
?>