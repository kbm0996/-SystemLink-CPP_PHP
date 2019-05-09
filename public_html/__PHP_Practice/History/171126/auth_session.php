<?php
include "_LIB/lib_DB.php";
include "_LIB/lib_Key.php";
include_once "_LIB/lib_Log.php";

/*---------------------------------------------
	Request Param - accountno, sessionkey
	Response Param - resultcode, accountno, new_sessionkey
---------------------------------------------*/

if(!isset($_GET['accountno']) || !isset($_GET['sessionkey']))
	exit;

$PF->startCheck(PF_MYSQL_CONN);
DB_Connect();
$PF->stopCheck(PF_MYSQL_CONN);

$AccountNo		= mysqli_real_escape_string($g_DB, $_GET['accountno']);
$SessionKey		= mysqli_real_escape_string($g_DB, $_GET['sessionkey']);


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

	echo "Searching Session Failed <br>";
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

	echo "AccountNo : " . $AccountNo . "SessionKey : " . $SessionKey . " → ". $New_SessionKey ." Updating Session Success <br>";
}

$g_AccountNo = $AccountNo;

DB_Disconnect();

$PF->stopCheck(PF_PAGE, "Total Page");
$PF->LOG_Save();
?>