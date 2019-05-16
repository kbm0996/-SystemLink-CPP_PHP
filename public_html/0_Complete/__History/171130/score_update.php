<?php
include "_Startup.php";

/*---------------------------------------------
	Request Param - accountno, sessionkey, new_score
				{"accountno":value,"sessionkey":"value","new_score":"value"}
	Response Param - resultcode
---------------------------------------------*/
$AccountNo		= mysqli_real_escape_string($g_DB, $_JSONData['accountno']);
$SessionKey		= mysqli_real_escape_string($g_DB, $_JSONData['sessionkey']);
$New_Score		= mysqli_real_escape_string($g_DB, $_JSONData['new_score']);

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

	$arrQry = array();

	$Query		= "INSERT INTO score (accountno, score) VALUES ({$AccountNo}, {$New_Score}) ON DUPLICATE KEY UPDATE score = {$New_Score}";
	array_push($arrQry, $Query);

	$PF->startCheck(PF_MYSQL);
	DB_TransactionQuery($arrQry);
	$PF->stopCheck(PF_MYSQL, "Insert");

	echo "{'resultcode':''}";
}

include "_Cleanup.php";
?>