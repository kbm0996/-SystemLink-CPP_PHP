<?php
/*---------------------------
- URL 필터링 기능
check_url.php

내부에 Filter 배열 생성.
배열에는 필터링 대상의 문자열을 넣음.

"REMOTE_ADDR"
"CHAR("
"CHR("
"EVAL("

들어오는 모든 인자 대상으로 위 문자열 들을 검색.
일치하는게 나온다면 BLOCK !  을 출력.

조건: 대소문자 모두 가능해야함. Remote_addr, reMote_ADDR 등..이 입력 되어도 감지 해야 함.
---------------------------*/

function FindString($str = 'value', $str2 = 'value')
{
	$str = strtoupper($str);
	$str2 = strtoupper($str2);

	if(strpos($str, $str2) == true) 
	{
		echo $str2 . " Find <br/>";
	}
}

$Filter = array("remote_addr", "char(", "chr(", "eval(");
$FilterCnt = count($Filter); // 배열 요소 개수 

for($i = 0; $i < $FilterCnt; ++$i)
{
	@FindString($_SERVER['PATH_INFO'], $Filter[$i]); 
}

?>