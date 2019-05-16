<?php
include "_LIB/lib_DB.php";

DB_Connect();

/*---------------------------------------------
	Request Param - accountno, sessionkey, new_score
	Response Param - resultcode
---------------------------------------------*/
/*
if(!isset($_GET['accountno']) || !isset($_GET['sessionkey']) || !isset($_GET['new_score']) )
	exit;
$AccountNo		= mysqli_real_escape_string($g_DB, $_GET['accountno']);
$SessionKey		= mysqli_real_escape_string($g_DB, $_GET['sessionkey']);
$New_Score		= mysqli_real_escape_string($g_DB, $_GET['new_score']);
*/
if(!isset($_POST['accountno']) || !isset($_POST['sessionkey']) || !isset($_POST['new_score']) )
	exit;
$AccountNo		= mysqli_real_escape_string($g_DB, $_POST['accountno']);
$SessionKey		= mysqli_real_escape_string($g_DB, $_POST['sessionkey']);
$New_Score		= mysqli_real_escape_string($g_DB, $_POST['new_score']);

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

	$arrQry = array();

	$Query		= "INSERT INTO score (accountno, score) VALUES ({$AccountNo}, {$New_Score}) ON DUPLICATE KEY UPDATE score = {$New_Score}";
	array_push($arrQry, $Query);

	DB_TransactionQuery($arrQry);

	echo "AccountNo : " . $AccountNo . " SessionKey : " . $SessionKey . " Updating Score Success<br>";
}

$g_AccountNo = $AccountNo;

DB_Disconnect();
?>