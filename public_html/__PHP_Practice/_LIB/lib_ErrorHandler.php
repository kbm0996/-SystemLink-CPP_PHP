<?php
//------------------------------------------------------------
/*
	PHP 에러를 핸들링하여 시스템 로그로 남기는 코드
	* 필수사항 : lib_Log.PHP가 include되어있어야 함
*/
//------------------------------------------------------------
ignore_user_abort(true);

	//------------------------------------------------------------
	//	php 종료 로그용 / 미사용
	//------------------------------------------------------------
	function sys_shutdown()
	{

	}
	//------------------------------------------------------------
	// 셧다운 핸들러 : PHP 파일이 종료될 때 자동으로 특정 함수를 호출
	//------------------------------------------------------------
//register_shutdown_function('sys_Shutdown');

//------------------------------------------------------------
// 에러 핸들러 등록
//
// E_ALL 모든 에러를 출력. 이는 php.ini 에서 설정도 가능.
// 에러발생시 ERROR_Handler 호출. 
// 예외발생시 EXCEPTION_Handler 호출.
//
// 이 후에는 Syntax 문법에러 외에는 화면에 에러가 출력되지 않음.
//------------------------------------------------------------
error_reporting(E_ALL);
set_error_handler('ERROR_Handler');
set_exception_handler('EXCEPTION_Handler');

//---------------------------------------------------------------------------------
// PHP 예외 로그 처리.
//
// set_error_handler('ERROR_Handler') 호출하여 사용
//---------------------------------------------------------------------------------
function ERROR_Handler($errno, $errstr, $errfile, $errline)
{
	global $g_AccountNo;
	// 전역으로 g_AccountNo 가 있다는 가정.
	// 누구로 인한 에러인지 확인하기 위해 에러 발생시 AccountNo 를 확인함.
 	if ( isset($g_AccountNo) === FALSE )		$g_AccountNo = -1;
	$ErrorMsg = "($errno) FILE: $errfile / LINE: $errline / MSG: $errstr";

	LOG_System($g_AccountNo, "ERROR_Handler", $ErrorMsg);
   
	//--------------------------------------------------------------------------------
	// 클라이언트에 에러 전송
	//--------------------------------------------------------------------------------
	if ( $g_AccountNo > 0 )
	{
	}
    exit;
}

//---------------------------------------------------------------------------------
// 예외처리 핸들러.   어디선가 throw 를 던지면 발생.
//---------------------------------------------------------------------------------
function EXCEPTION_Handler($exception)
{
	global $g_AccountNo;
 	if ( isset($g_AccountNo) === FALSE )		$g_AccountNo = -1;
	LOG_System($g_AccountNo, "EXCEPTION_Handler", $exception->getMessage());
    exit;
}
?>