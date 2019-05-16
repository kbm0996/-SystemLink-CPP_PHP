<?php
include "_Startup.php";

/*---------------------------------------------
	Request Param - accountno, sessionkey
				{"accountno":value,"sessionkey":"value"}
	Response Param - resultcode, accountno, new_sessionkey
---------------------------------------------*/
$AccountNo		= mysqli_real_escape_string($g_DB, $_JSONData['accountno']);
$SessionKey		= mysqli_real_escape_string($g_DB, $_JSONData['sessionkey']);

$Query = "SELECT COUNT(accountno) AS Cnt FROM session WHERE accountno = '{$AccountNo}' AND sessionkey = '{$SessionKey}' AND UNIX_TIMESTAMP(NOW()) > regtime + 300"; // 세션 갱신 시간 5분 (300초)
$PF->startCheck(PF_MYSQL);
$Result		= DB_ExecQuery($Query);
$PF->stopCheck(PF_MYSQL, "Select");

$Session	= mysqli_fetch_array($Result, MYSQL_ASSOC);
mysqli_free_result($Result);

if($Session['Cnt'] == 0)
{
	$ResultCode = false;
	$AccountNo = null;
	$New_SessionKey = null;

	echo "{'resultcode':''}";
}
else
{
	$ResultCode = true;
	$New_SessionKey = KeyGen32();

	$arrQry = array();

	$Query		= "INSERT INTO session (accountno, sessionkey, regtime) VALUES ({$AccountNo}, '{$New_SessionKey}', UNIX_TIMESTAMP(NOW())) ON DUPLICATE KEY UPDATE sessionkey = '{$New_SessionKey}', regtime=UNIX_TIMESTAMP(NOW())";
	array_push($arrQry, $Query);
	
	$PF->startCheck(PF_MYSQL);
	$AccountNo = DB_TransactionQuery($arrQry);
	$PF->stopCheck(PF_MYSQL, "Insert");

	echo "{'resultcode':'','accountno':'" . $AccountNo . "','sessionkey':'". $New_SessionKey ."'}";
}

include "_Cleanup.php";
?>