<?php
//------------------------------------
// 프로파일링 로그 남기는 함수 / DB, 테이블 정보는 _Config.php 참고
//
// POST 방식으로 프로파일링 로그 저장
//
// $_POST['IP']				= 유저
// $_POST['MemberNo']		= 유저
// $_POST['Action']			= 액션
//
// $_POST['T_Page']			= Time Page
// $_POST['T_Mysql_Conn']	= Time Mysql Connect
// $_POST['T_Mysql']		= Time Mysql
// $_POST['T_ExtAPI']		= Time API
// $_POST['T_Log']			= Time Log
// $_POST['T_ru_u']			= Time user time used
// $_POST['T_ru_s']			= Time system time used
// $_POST['Query']			= Mysql이 있는 경우 쿼리문
// $_POST['Comment']		= 그 외 기타 멘트
//------------------------------------
include_once "_Config.php";

if(!isset($_POST['IP']))			$_POST['IP'] = "None";
if(!isset($_POST['AccountNo']))		$_POST['AccountNo'] = "None";
if(!isset($_POST['Action']))		$_POST['Action'] = "None";
if(!isset($_POST['T_Page']))		$_POST['T_Page'] = "None";
if(!isset($_POST['T_Mysql_Conn']))	$_POST['T_Mysql_Conn'] = "None";
if(!isset($_POST['T_Mysql']))		$_POST['T_Mysql'] = "None";
if(!isset($_POST['T_ExtAPI']))		$_POST['T_ExtAPI'] = "None";
if(!isset($_POST['T_Log']))			$_POST['T_Log'] = "None";
if(!isset($_POST['T_ru_u']))		$_POST['T_ru_u'] = "None";
if(!isset($_POST['T_ru_s']))		$_POST['T_ru_s'] = "None";
if(!isset($_POST['Query']))			$_POST['Query'] = "None";
if(!isset($_POST['Comment']))		$_POST['Comment'] = "None";

$g_LOGDB = mysqli_connect($g_LOGDB_IP, $g_LOGDB_ID, $g_LOGDB_PASS, $g_LOGDB_NAME, $g_LOGDB_PORT);
if(!$g_LOGDB)
{
	file_put_contents('php://stderr', "Log DB ERROR # mysqli_connect() : " . mysqli_connect_error());
	exit;
}

mysqli_set_charset($g_LOGDB, "utf8");

//--------------------------------------
// 문자열 인자의 공격 검사는 하지 않음
// 내부 서버 IP 외에는 본 파일을 호출하지 못하도록 방화벽에서 차단돼야함
//--------------------------------------
$IP				= mysqli_real_escape_string($g_LOGDB, $_POST['IP']);
$AccountNo		= mysqli_real_escape_string($g_LOGDB, $_POST['AccountNo']);
$Action			= mysqli_real_escape_string($g_LOGDB, $_POST['Action']);
$T_Page			= mysqli_real_escape_string($g_LOGDB, $_POST['T_Page']);
$T_Mysql_Conn   = mysqli_real_escape_string($g_LOGDB, $_POST['T_Mysql_Conn']);
$T_Mysql		= mysqli_real_escape_string($g_LOGDB, $_POST['T_Mysql']);
$T_ExtAPI		= mysqli_real_escape_string($g_LOGDB, $_POST['T_ExtAPI']);
$T_Log			= mysqli_real_escape_string($g_LOGDB, $_POST['T_Log']);
$T_ru_u			= mysqli_real_escape_string($g_LOGDB, $_POST['T_ru_u']);
$T_ru_s			= mysqli_real_escape_string($g_LOGDB, $_POST['T_ru_s']);
$Query			= mysqli_real_escape_string($g_LOGDB, $_POST['Query']);
$Comment		= mysqli_real_escape_string($g_LOGDB, $_POST['Comment']);

$TableName = "ProfilingLog_".@date("Ym");
// INSERT DELAYED INTO 내부적으로 SELECT 와 INSERT 가 이루어지기 때문에 에러없이 항상 '1 rows affected' 가 이루어짐.즉, INSERT DELAYED INTO 는 기존에 같은 PRIMARY KEY 가 있으면, 현재의 데이터를 더 이상 INSERT 를 하지 않고, 항상 '1 rows affected' 의 결과를 보냄.
$Query = "INSERT INTO {$TableName} (date, ip, accountno, action, t_page, t_mysql_conn, t_mysql, t_extapi, t_log, t_ru_u, t_ru_s, query, comment) VALUES (NOW(), '{$IP}', '{$AccountNo}', '{$Action}', {$T_Page}, {$T_Mysql_Conn}, {$T_Mysql}, {$T_ExtAPI}, {$T_Log}, {$T_ru_u}, {$T_ru_s}, '{$Query}', '{$Comment}')";
$Result = mysqli_query($g_LOGDB, $Query);

if ( !$Result && mysqli_errno($g_LOGDB) == 1146 ) 
{
	mysqli_query($g_LOGDB, "CREATE TABLE {$TableName} LIKE profilinglog_template");
	mysqli_query($g_LOGDB, $Query);
}

if(isset($g_LOGDB))
	mysqli_close($g_LOGDB);

?>