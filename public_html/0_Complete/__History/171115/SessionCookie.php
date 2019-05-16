<?php
/*---------------------------
- 시간 기준 자동생성 키 만들기

OTP 방식
OTP 시간을 기준으로 특정 키 생성.
리얼 OTP 는 ? - 생성 키 값에서 시간을 유추할 수 있도록 하여 서버쪽에서 시간을 동기화 시킴.

: 시간을 사용하여 32자의 문자로 된 키를 생성하라 (1분단위)

//name = 설정할 쿠키의 이름
	//value = 쿠키에 저장될 이름
	//expire = 유효 시간을 설정하는 함수
	//path = 쿠키를 사용할 수 있는 범위 지정
	//setcookie(name, value, expire, path);

	//cookie 라는 이름으로 1004라는 데이터를 저장하고 60초만 쿠키 보존 하고 
	// 슬러쉬로 디렉터리를 지정 하여 상위 디렉토리에서도 쿠키를 사용할수 있게 처리
	setcookie("cookie", "1004", 60+time(), "/");
---------------------------*/

//$timestamp = strtotime("+1 hour");
/*
$otp = new Object();
$_SESSION['myotp'] = $otp; //저장하기

if(isset($_SESSION['myObj']))
{
	$newObj = unserialize($_SESSION['myObj']);    //불러오기
}
else
{
	$_SESSION['myObj'] = $obj;
}
*/

@session_start(); // 세션 사용을 위한 선언

/*------------------------------------------------------
별다른 설정을 하지 않았다면 임시 디렉터리에 sess_uid 파일로 세션이 만들어 집니다. 위
예제 UID를 기초로 한다면 실제 파일 이름이 “/tmp/sess_dep8tcts4h1eo39mseerjva5v1”
이 됩니다. 
------------------------------------------------------*/
// 현재 세션 이름을 얻습니다.
echo "session name: ".session_name().'<br/>';		
// 아직 세션을 등록하지 않았으므로 출력 값이 없습니다. 
echo "session: ".@$_SESSION['PHPSESSID'].'<br/>';
// 현재 세션 모듈이름을 얻습니다.
// 파일을 사용하고 있으므로 “files” 값을 반환하지만
// 만약 DB로 세션을 구현하였다면 “user” 값을 반환하게 됩니다.
echo "session module: ".session_module_name().'<br/>';
// 현재 세션 UID를 얻습니다.
echo "session uid: ".session_id().'<br/>';
echo "cookie uid: ".$_COOKIE['PHPSESSID'].'<br/>';

/*------------------------------------------------------
// session_regenerate_id() 함수 
------------------------------------------------------*/
// 이전에 할당된 세션 파일을 지우려면
// delete_old_session 인수에 true로 설정하면 됩니다.
session_regenerate_id();
echo "new session uid:". session_id().'<br/>';
// 출력: ef798ec29ab2d6061a4f74bda2ab2917
// 변경된 세션 UID를 얻습니다.
echo "cookie uid: " . $_COOKIE['PHPSESSID'].'<br/>';
// 출력: cookie uid: ef798ec29ab2d6061a4f74bda2ab2917

/*------------------------------------------------------
// 세션 변수 등록 및 제거 
------------------------------------------------------*/
// 세션에 변수를 등록합니다.
$_SESSION['user'] = 'habony';
echo "session register: " . $_SESSION['user'].'<br/>';
// 출력: session register: habony
// 세션에 등록한 변수를 제거합니다.
unset($_SESSION['user']);
echo "session register: " . @$_SESSION['user'].'<br/>';
//출력: session register:
// 세션에 변수를 새로 등록합니다.
$_SESSION['user'] = 'habony';
echo "session register: " . $_SESSION['user'].'<br/>';
// 출력: session register: habony
$_SESSION['age'] = 20;
echo "session register: " . $_SESSION['age'].'<br/>';
// 출력: session register: 20
// 세션에 배열로 등록합니다.
$_SESSION['arr'][ 'key1'] = 1;
$_SESSION['arr'][ 'key2'] = 2;
print_r($_SESSION['arr']);
echo '<br/>';
/*
출력:
Array
(
 [key1] => 1
 [key2] => 2
)
*/

/*------------------------------------------------------
// session_encode() 함수 
------------------------------------------------------*/
echo session_encode();
// 출력: age|i:20;user|s:6:"habony";arr|a:2:{s:4:"key1";i:1;s:4:"key2";i:2;}

/*------------------------------------------------------
// 세션 전체 제거
------------------------------------------------------*/
// 세션 데이터는 보통 스크립트 종료 후에 저장되므로 이 함수를 호출할 필요는 없습니다.
// 그러나 동시 쓰기를 막기 위해 자동으로 세션 데이터가 잠금(락)상태가 되기 때문에
// 세션을 바로 종료함으로써 세션에 걸리는 시간을 단축시킬 수 있습니다.
// session_commit() 함수도 똑같은 기능을 합니다.
session_write_close();
// 세션에 등록된 변수를 초기화 시킵니다.
$_SESSION = array();
// 세션에 등록된 변수는 초기화하여도
// 세션이름이나 UID는 그대로 남아 있으므로 삭제를 합니다.
if(isset($_COOKIE[session_name()])) {
 setcookie(session_name(), '', time()-42000, ‘/’);
}
// 세션 변수 전체를 삭제합니다.
session_unset();
// 마지막으로 한번 더 세션 전체를 삭제하도록 합니다.
// 이 함수는 UID를 삭제하지 않으며, 세션에 등록된 데이터를 삭제합니다.
@session_destroy();


/*
if (!isset($_SESSION['TIME'])) 
{
  $_SESSION['TIME'] = strtotime("now");
} 
else 
{
  $_SESSION['count']++;
}
*/
//if(strtotime("now")%60 == 0)
{
	$before = strtotime("-1 minutes") . '<br/>';
	$current = strtotime("now") . '<br/>';
	$after = strtotime("+1 minutes") . '<br/>';
}
	echo $before . '<br>' . $current . '<br>' . $after;
	echo '<br><br>';

	$result1 = encrypt($before);
	for($i = 0 ; $i < count($result1) ; ++$i)
		echo $result1[$i] . ' ';
	echo '<br>';

	$result2 = encrypt($current);
	for($i = 0 ; $i < count($result2) ; ++$i)
		echo $result2[$i] . ' ';
	echo '<br>';

	$result3 = encrypt($after);
	for($i = 0 ; $i < count($result3) ; ++$i)
		echo $result3[$i] . ' ';
	echo '<br>';


	function encrypt($timestamp = 'value')
{
	if($timestamp != null)
	{
		$strTok = explode(',' , @number_format($timestamp * pow(1000,30)));
	
		for($i = 0 ; $i < count($strTok) ; ++$i)
		{
			$strTok[$i] = chr($strTok[$i] % 25 + 65);
		}
		return $strTok;
	}
}
?>