<?php
/*---------------------------
- money.php?money=100,20,3000,400,5010,200,3004,3040,200,300,1000
컴마로 구분 된 여러개의 숫자를 $money 로 받음.
컴마 기준으로 분리하여 배열로 저장.
모든 총 합을 구한 뒤 1000단위 컴마를 넣어서 출력.

16,274

+number_format 를 활용 
---------------------------*/

function sum($str = 'value')
{
	$sum = 0;
	if($str != null)
	{
		//string 나누기
		$strTok = explode(',' , $str);

		//배열 크기 가져오기 
		$cnt = count($strTok);

		for($i = 0 ; $i < $cnt ; ++$i)
		{
			//echo $strTok[$i] . ' ';
			$sum += $strTok[$i];
		}
	}
	return $sum;
}

$string = $_GET['money']; 

echo number_format(sum($string));
?>