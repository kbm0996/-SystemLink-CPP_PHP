<?php
include "_Startup.php";

/*---------------------------------------------
	Request Param - id,pass
			{"id":"아이디","pass":"비밀번호"}
	Response Param - resultcode, accountno, sessionkey
---------------------------------------------*/
$ID		= mysqli_real_escape_string($g_DB, $_JSONData['id']);
$Pass	= mysqli_real_escape_string($g_DB, $_JSONData['pass']);

$HashPass = Hashing64($Pass);

$Query = "SELECT accountno FROM account WHERE id = '{$ID}' AND password = '{$HashPass}'";

$PF->startCheck(PF_MYSQL);
$Result		= DB_ExecQuery($Query);
$PF->stopCheck(PF_MYSQL, "Select");

$Account	= mysqli_fetch_array($Result, MYSQL_ASSOC);
mysqli_free_result($Result);

if($Account === null) // ===는 변수의 타입까지 비교한다
{
	$resultCcde = false;
	$sessionkey = null;

	echo "{'resultcode':''}";
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
	$g_AccountNo = DB_TransactionQuery($arrQry);
	$PF->stopCheck(PF_MYSQL, "Insert");

	
	echo "{'resultcode':'','accountno':'" . $Account['accountno'] . "','sessionkey':'" . $sessionkey . "'}";
}

include "_Cleanup.php";
?>