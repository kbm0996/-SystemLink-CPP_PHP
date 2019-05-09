<?php
/*
http://zetawiki.com/wiki/PHP_%EC%84%B8%EC%85%98_%EB%A1%9C%EA%B7%B8%EC%9D%B8_%EA%B5%AC%ED%98%84
http://allinfo.tistory.com/1183
*/
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
ob_start();
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

session_start();

if(isset($_SESSION['otptime']))
{
	$_SESSION['otptime'] = strtotime("now"); 
}
else
{
	if($_SESSION['otptime'] - 60 <= strtotime("now");
	{

	}
}


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



?>