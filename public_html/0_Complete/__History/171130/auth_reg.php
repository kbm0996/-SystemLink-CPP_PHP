<?php
include "_Startup.php";

/*---------------------------------------------
	Request Param - id,pass,nickname
			{"id":"아이디","pass":"비밀번호","nickname":"닉네임"}
	Response Param - resultcode, accountno
---------------------------------------------*/
$ID			= mysqli_real_escape_string($g_DB, $_JSONData['id']);
$Pass		= mysqli_real_escape_string($g_DB, $_JSONData['pass']);
$Nickname	= mysqli_real_escape_string($g_DB, $_JSONData['nickname']);

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

	echo "{'resultcode':''}";
}
else
{
	$ResultCode = true;
	$HashPass = Hashing64($Pass);

	$arrQry = array();
	$Query		= "INSERT INTO account (id, password, nickname) VALUES ('{$ID}', '{$HashPass}', '{$Nickname}')";
	array_push($arrQry, $Query);

	$PF->startCheck(PF_MYSQL);
	$AccountNo = DB_TransactionQuery($arrQry);
	$PF->stopCheck(PF_MYSQL, "Insert");

	echo "{'resultcode':'','accountno':'" . $AccountNo . "'}";
}

include "_Cleanup.php";
?>