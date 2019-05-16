<?php
/*---------------------------------------
  클라이언트 IP/포트, 서버 IP/포트 를 출력하는 php
---------------------------------------*/
echo "----------------------------------------------------<br>";
echo "클라이언트 IP/포트, 서버 IP/포트 를 출력하는 php <br>";
echo "----------------------------------------------------<br><br>";
echo "클라이언트 IP		:" . $_SERVER['REMOTE_ADDR'] . "<br>";
echo "클라이언트 포트 : 	" . $_SERVER['REMOTE_PORT'] . "<br>";
echo "서버 IP :	" . $_SERVER['SERVER_ADDR'] . "<br>";
echo "서버 포트 :	" . $_SERVER['SERVER_PORT'] . "<br>";
echo "<br><br>";


/*---------------------------------------
- URL GET 방식으로 구구단 단수 입력 받아서 출력하기.
ex) 127.0.0.1/gugu.php?num=3
---------------------------------------*/
echo "----------------------------------------------------<br>";
echo "URL GET 방식으로 구구단 단수 입력 받아서 출력하기 <br>";
echo "ex) 127.0.0.1/gugu.php?num=3 <br>";
echo "----------------------------------------------------<br><br>";
function Gugu($num)
{
	if($num == null)
	{
		$num = 1;
	}
	
	echo "$num 단 <br>";
	for($i = 1; $i <= 9; ++$i)
	{
		echo $num." * ".$i." = ".$i*$num."<br>";    
	}
	
}
Gugu($_GET['num']);
echo "<br><br>";


/*---------------------------------------
- URL GET 방식으로 문자열을 입력 받으면 이를 . 로 잘라서 표시하기.
ex) 127.0.0.1/gugu.php?str=abdf.dfwf.dfdfe.asdfasdffe.ㅇ.3.4.2.333ㄹㅇ
# 참고함수
strstr : 문자열의 처음을 구하는 함수
strlen : 문자열의 길이를 구하는 함수
substr : 문자열을 자르는 함수 substr(문자열, 시작index, 길이)
strpos : 문자열에 특정 문자열이 포함되어 있는지 확인하는 함수
---------------------------------------*/
echo "----------------------------------------------------<br>";
echo "URL GET 방식으로 문자열을 입력 받으면 이를 . 로 잘라서 표시하기 <br>";
echo "ex) 127.0.0.1/gugu.php?str=abdf.dfwf.dfdfe.asdfasdffe.ㅇ.3.4.2.333ㄹㅇ <br>";
echo "----------------------------------------------------<br><br>";
function Explode_Dot($str)
{
	if($str != null)
	{
		$strTok = explode('.' , $str); // string 나누기
		$cnt = count($strTok); // 배열 크기
		for($i = 0 ; $i < $cnt ; ++$i)
		{
			echo $strTok[$i] . "<br>";
		}
	}
}
$string = $_GET['str']; 
Explode_Dot($string);
echo "<br><br>";


/*---------------------------------------
- money.php?money=100,20,3000,400,5010,200,3004,3040,200,300,1000
컴마로 구분 된 여러개의 숫자를 $money 로 받음.
컴마 기준으로 분리하여 배열로 저장.
모든 총 합을 구한 뒤 1000단위 컴마를 넣어서 출력.
16,274
+number_format 를 활용 
---------------------------------------*/
echo "----------------------------------------------------<br>";
echo "컴마로 구분 된 여러개의 숫자를 money 로 받음. 컴마 기준으로 분리하여 배열로 저장.<br>";
echo "모든 총 합을 구한 뒤 1000단위 컴마를 넣어서 출력.<br>";
echo "ex) money.php?money=100,20,3000,400,5010,200,3004,3040,200,300,1000 <br>";
echo "----------------------------------------------------<br><br>";
function Sum($str)
{
	$sum = 0;
	if($str != null)
	{
		$strTok = explode(',' , $str); // string 나누기
		$cnt = count($strTok); // 배열 크기
		for($i = 0 ; $i < $cnt ; ++$i)
		{
			echo $strTok[$i] . '.';
			$sum += $strTok[$i];
		}
	}
	echo '<br>';
	return $sum;
}

$string = $_GET['money']; 
echo 'SUM : ' . number_format(Sum($string)) . '<br>'; //1000단위 컴마를 넣어서 출력.
echo '<br><br>';


/*---------------------------------------
- URL 필터링 기능
내부에 Filter 배열 생성.
배열에는 필터링 대상의 문자열을 넣음.
"REMOTE_ADDR"
"CHAR("
"CHR("
"EVAL("
들어오는 모든 인자 대상으로 위 문자열 들을 검색.
일치하는게 나온다면 BLOCK !  을 출력.
조건: 대소문자 모두 가능해야함. Remote_addr, reMote_ADDR 등..이 입력 되어도 감지 해야 함.
---------------------------------------*/
echo "----------------------------------------------------<br>";
echo "- URL 필터링 기능 <br>";
echo "----------------------------------------------------<br><br>";
function FindString($str = 'value', $str2 = 'value')
{
	$str = strtoupper($str);
	$str2 = strtoupper($str2);
	if(strpos($str, $str2) == true) 
	{
		echo $str2 . " Find <br>";
	}

	return true;
}
$Filter = array("remote_addr", "char(", "chr(", "eval(");
$FilterCnt = count($Filter); // 배열 요소 개수 

for($i = 0; $i < $FilterCnt; ++$i)
{
	FindString($_SERVER['REQUEST_URI'], $Filter[$i]); 
}
echo '<br><br>';


/*---------------------------
- 시간 기준 자동생성 키 만들기

OTP 방식
OTP 시간을 기준으로 특정 키 생성.
리얼 OTP 는 ? - 생성 키 값에서 시간을 유추할 수 있도록 하여 서버쪽에서 시간을 동기화 시킴.

: 시간을 사용하여 32자의 문자로 된 키를 생성하라 (1분단위)
---------------------------*/
echo "----------------------------------------------------<br>";
echo "- 시간 기준 자동생성 키 만들기 <br>";
echo "----------------------------------------------------<br><br>";
function Encrypt($value)
{
	$Key = hash('md5', $value);

	for($i=0; $i<6; ++$i)
	{
		$UpperCase = rand(0,31);
		$Key[$UpperCase] = strtoupper($Key[$UpperCase]);
	}

	return substr($Key, 0, 32);
}

if(!isset($_SESSION)) 
{
	session_start();
}

if(!isset($_SESSION['otptime']) || !isset($_SESSION['sessionkey']))
{
	$_SESSION['otptime'] = strtotime("now"); 
	$_SESSION['sessionkey'] = Encrypt($_SESSION['otptime']);
}
else
{
	if($_SESSION['otptime'] + 60 <= strtotime("now"))
	{
		$_SESSION['otptime'] = strtotime("now"); 
		$_SESSION['sessionkey'] = Encrypt($_SESSION['otptime']);
	}
}
echo 'CurTime : ' . strtotime("now") . ' / SessID :' . session_id() . '<br><br>';
echo $_SESSION['otptime'] . '<br>';
echo $_SESSION['sessionkey'] . '<br>';
?>