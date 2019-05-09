<?php
include "_LIB/lib_DB.php";
include "_LIB/lib_Key.php";
include_once "_LIB/lib_Log.php";


/*---------------------------------------------
	Request Param - id,pass
	Response Param - resultcode, accountno, sessionkey
---------------------------------------------*/
// 프로파일링
$PF = Profiling::getInstance($cnf_PROFILING_LOG_URL, $_SERVER['PHP_SELF']);
$PF->startCheck(PF_PAGE);

if(!isset($_GET['id']) || !isset($_GET['pass']))
	exit;

$PF->startCheck(PF_MYSQL_CONN);
DB_Connect();
$PF->stopCheck(PF_MYSQL_CONN);

$id		= mysqli_real_escape_string($g_DB, $_GET['id']);
$pass	= mysqli_real_escape_string($g_DB, $_GET['pass']);

$Query = "SELECT accountno FROM account WHERE id = '{$id}' AND password = '{$pass}'";
$PF->startCheck(PF_MYSQL);
$Result		= DB_ExecQuery($Query);
$PF->stopCheck(PF_MYSQL, "Select");

$Account	= mysqli_fetch_array($Result, MYSQL_ASSOC);
mysqli_free_result($Result);

if($Account === null) // ===는 변수의 타입까지 비교한다
{
	$resultCcde = false;
	$sessionkey = null;

	echo "Login Failed <br>";
}
else
{
	$resultcode = true;
	$sessionkey = KeyGen32();

	$arrQry = array();

	$Query		= "INSERT INTO session (accountno, sessionkey, regtime) VALUES ('{$Account['accountno']}', '{$sessionkey}', UNIX_TIMESTAMP(NOW())) ON DUPLICATE KEY UPDATE sessionkey = '{$sessionkey}', regtime=UNIX_TIMESTAMP(NOW())";
	array_push($arrQry, $Query);

	$Query		= "INSERT INTO login (accountno, time, ip, count) VALUES ('{$Account['accountno']}', UNIX_TIMESTAMP(NOW()), '{$_SERVER['REMOTE_ADDR']}', 1) ON DUPLICATE KEY UPDATE time=UNIX_TIMESTAMP(NOW()), ip='{$_SERVER['REMOTE_ADDR']}', count=count+1";
	array_push($arrQry, $Query);

	$PF->startCheck(PF_MYSQL);
	$AccountNo = DB_TransactionQuery($arrQry);
	$PF->stopCheck(PF_MYSQL, "Insert");

	
	echo "AccountNo : " . $Account['accountno'] . " SessionKey : " . $sessionkey . " Login Success <br>";
}

$g_AccountNo = $Account['accountno'];

DB_Disconnect();

$PF->stopCheck(PF_PAGE, "Total Page");
$PF->LOG_Save();
?>