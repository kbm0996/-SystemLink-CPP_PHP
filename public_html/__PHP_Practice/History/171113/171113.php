gsdasc
;czx'c;zx'
c;z'xc.x'zc'
..x'.x
zz
<br/>

<?php
echo "if문 예제";
echo "<br/>";

$val = 1;
if($val == 1)
{
?>


echo "dfdfdfdf";

<?php
echo 1;
echo 2;
}
?>

<br/>

<?php
echo "array 예제<br/>";


echo "*예1: <br/>";
$cars = array();
$cars[0] = 11111;
$cars[1] = 22222;
$cars[2] = 33333;

echo $cars[0];
echo $cars[1];
echo $cars[2];
echo "<br/>";
echo "<br/>";

echo "*예2: <br/>";
$array = array("cat", 1000, "mouse");

echo $array[0];
echo $array[1];
echo $array[2];
echo "<br/><br/>";

echo "*array push 예: <br/>";
array_push($cars, "Cat2");
echo $cars[3];

echo "foreach문 <br/>";
foreach($cars as $key => $value)
{
	echo $key . "=>" . $value . " 곤뇽 <br/>";
}
echo "<br/>";
echo "*String 예: <br/>";

echo "큰따옴표 ";
$str = "ㅁㄹ/$cars[0]ㄹ <br/>";
echo $str;

echo "작은따옴표 ";
$str = 'ㅁㄹ/$cars[0]ㄹ <br/>';
echo $str;

echo "<br/><br/>함수예제 <br/>";
function test($arg = 'value')
{
	$retval = 125;
	echo "$arg 예제 함수. \n";
	return $retval;
}

$result = test();
echo $result;


echo "<br/><br/>전역변수 함수예제 <br/>";
$g_test = 0;
function test2()
{
	global $g_test; // 떄문에 함수들 첫째줄에는 global 변수 명이 줄줄이 들어감
	$g_test = 100;
}
test2();
echo $g_test;


echo "<br/><br/>익명함수예제 <br/>"; // lambda와 유사

echo "<br/><br/>define 예제 <br/>"; // c의 define과 다름
define("GREETING", "상수 테스트입니다 <br/>", true);
echo GREETING;
echo Greeting;

echo "<br/><br/>슈퍼전역 변수 <br/>";
echo '$_SERVER <br/>';
echo '$_GET <br/>';
echo '$_POST <br/>';
echo '$_FILES <br/>';
echo '$_COOKIE <br/>';
echo '$_SESSION <br/>';
echo '$_REQUEST <br/>';
echo '$_ENV <br/>';

/*
_SERVER
	$_SERVER['환경변수'];
	
환경변수 자주 쓰는 것 -
	HTTP_REFERER	// 이전 URL
	PHP_SELF		// 현재 사용중인 PHP파일 이름
	QUERY_STRING	// 사용자가 GET이나 POST로 보낸 쿼리의 인자를 전부 출력
	 
_GET
GET방식 : 인자 전달. URL에서 바로 볼 수 있음

_POST
POST방식 : 폼 형식. 패킷을 봐야 볼 수 있음


*/

?>


<br/>
v
ad
saddddddddddddddddddaxcs
<br />
<a href = "http://gamecodi.com"><font color=#ff> Link Gamecodi</font></a>