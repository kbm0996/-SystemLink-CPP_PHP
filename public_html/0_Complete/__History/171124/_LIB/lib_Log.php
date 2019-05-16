<?php

include "_lib/lib_Call.php";
include "_Config_Log.php";

//Log_System(0, '테스트', '테스트트트트');

function Log_System($AccountNo, $Action, $Message, $LogLevel = 0)
{
	global $g_LogLevel;

	if($g_LogLevel < $LogLevel)
		return;

	if($AccountNo <= 0|| !isset($AccountNo))
	{
		// 실제 클라이언트 IP 얻기
		//  프록시(캐시)서버가 있을 경우, 클라IP가 아니라 프록시 서버IP가 들어가므로 이를 감지하는 코드가 필요함
		if (array_key_exists('HTTP_X_FORWARDED_FOR', $_SERVER))
			$AccountNo = $_SERVER['HTTP_X_FORWARDED_FOR'];
		else if(array_key_exists('REMOTE_ADDR', $_SERVER))
			$AccountNo = $_SERVER['REMOTE_ADDR'];
		else
			$AccountNo = 'local';
	}

	$postField = array("accountno"=>$AccountNo, "action"=>$Action, "message"=>$Message);

	//echo Call_Socket("http://127.0.0.1/_log/logsystem.php", $postField, "POST");
	$Response = Call_Curl("http://127.0.0.1/_log/logsystem.php", $postField, "POST");
	//echo $Response['body'];
}
?>