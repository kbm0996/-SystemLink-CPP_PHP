<?php
/*---------------------------
- URL GET 방식으로 문자열을 입력 받으면 이를 . 로 잘라서 표시하기.
ex) 127.0.0.1/gugu.php?str=abdf.dfwf.dfdfe.asdfasdffe.ㅇ.3.4.2.333ㄹㅇ

# 참고함수
strstr : 문자열의 처음을 구하는 함수
strlen : 문자열의 길이를 구하는 함수
substr : 문자열을 자르는 함수 substr(문자열, 시작index, 길이)
strpos : 문자열에 특정 문자열이 포함되어 있는지 확인하는 함수
---------------------------*/

function explode_dot($str = 'value')
{
	if($str != null)
	{
		//string 나누기
		$strTok = explode('.' , $str);

		//배열 크기 가져오기 
		$cnt = count($strTok);


		for($i = 0 ; $i < $cnt ; ++$i)
		{
			echo $strTok[$i];
			echo "<br/>";
		}
	}
}

$string = $_GET['str']; 

explode_dot($string);
?>