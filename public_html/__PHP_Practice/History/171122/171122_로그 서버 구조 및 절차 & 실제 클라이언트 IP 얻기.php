<?php
/*-----------------------------------------------------------------------------
How to Writing Log?

GameServer(web)
	stageClear.php
			...
			...
			LogSystem(1000, "StageClear.php", "에러에러에러"); // 1

LogClient
	LogSystem(...); // 2 -> http://~/LOG/LogSystem 
	LogGame(...);

LogServer
	LOG/LogSystem.php // 3 -> LogDB Insert. POST 형태로 받아서 DB에 저장만하면 됨. 
		//최대한 심플하게 해야함(저장시 성능이 중요. 함수 사용X 해당 파일 혼자서 돌아갈 수 있게끔)
	LOG/LogGame.php
	LOG/LogProfiling.php


//	1. 로그남기는 코드에서 에러가 났을 경우, 파일에 남기는 식으로 구현
//	2. 쿼리문을 저장해야하고 외부 공격으로부터 보호를 해야하는 경우가 있기 때문에 mysqli_real_escape_string 필수
	
//	테이블 저장 요령
//	SystemLog_template
	

-------------------------------------------------------------------------------*/

/*--------------------------------------------------------------------------------
//////////////////////////////////
// System Log Server Part
//////////////////////////////////

// Log insert
$Table_Name = "SystemLog_" . @date("Ym");
$Query = "insert~"; // 시간은 DB 시간 따르는게 좋음
$Result = mysqli_query~

// Not Exist Table
if ( !$Result && mysql_errno($db) == 1146 ) // errcode 1146 = 테이블이 없음. 무식해보이지만 대부분의 웹 개발자가 이런 식으로 코드 구성
{
	$Query = "CREATE TABLE 이름 LIKE 복제대상이름"; // LIKE 키워드. 내용은 복제X 테이블 구성만 복제O
	$Result = mysqli_query~
}

if($g_db_connect)
 //DB끊기


//DB종료
// 로그를 남기기 위한 서버에서 다른 에러가 났으면 php에 저장된다. 조치해줄 수 있는게 없음
// DB마다 다르지만 autoset9의 경우 \server\logs\error.log에 남게 됨.


//////////////////////////////////
// System Log Client Part
//////////////////////////////////

//LogLevel Config 파일에서 관리

LOG_System($accountno, $action, $message ,$Level)
{
	postfield로 logsystem.php에 넘기기
}

//////////////////////////////////
// 실제 클라이언트 IP 얻기
//////////////////////////////////
//  프록시(캐시)서버가 있을 경우(로드밸런서, 방화벽, 하이텔 등 모뎀시절 네트워크, 크롬 데이타세이버(이미지 등의 압축))
// 클라IP가 아니라 프록시 서버IP가 들어가므로 이를 감지하는 코드가 필요함
$_SERVER['HTTP_X_FORWARDED_FOR']  // 악의적이지 않은 Proxy 서버라면 HTTP Header의 X_FORWARDED_FOR 항목에 넣음
$_SERVER['REMOTE_ADDR']
//  HTTP_X_FORWARDED_FOR가 있으면 이를 사용하고, 없으면 REMOTE_ADDR을 사용
// 악의적인 목적의 프록시라면 HTTP의 X_FORWARDED_FOR 항목에도 넣지 않으므로 얻을 방법이 없음

if (array_key_exists('HTTP_X_FORWARDED_FOR', $_SERVER))
	$MemberNo = $_SERVER['HTTP_X_FORWARDED_FOR'];
else if(array_key_exists('REMOTE_ADDR', $_SERVER))
	$MemberNo = $_SERVER['REMOTE_ADDR'];
else
	$MemberNo = 'local';



-------------------------------------------------------------------------------------*/


?>