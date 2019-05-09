<?php
/*---------------------------
- check.php 뒤에 어떤 변수가 들어올지 모름.
그냥 뭐던지 상관 없이 EXIT 라는 문자가 있다면 
"EXIT Find" 를 출력한다.

+ $_SERVER[XXXXX] 를 활용
---------------------------*/

function FindString($str = 'value', $str2 = 'value')
{
	$str = strtoupper($str);
	$str2 = strtoupper($str2);

	if(strpos($str, $str2) == true) 
	{
		echo $str2 . " Find <br/>";
	}

	return true;
}

echo @$_SERVER['PATH_INFO'] . '<br/>';

@FindString($_SERVER['PATH_INFO'], "exit");

?>