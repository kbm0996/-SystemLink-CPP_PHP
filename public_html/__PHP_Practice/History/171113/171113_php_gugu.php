<?php
/*---------------------------
- URL GET 방식으로 구구단 단수 입력 받아서 출력하기.
ex) 127.0.0.1/gugu.php?num=3
---------------------------*/

function gugu($num = 'value')
{
	if($num == null)
	{
		throw new Exception("gugu function's argument is null");
	}
	else
	{
		echo "$num 단 <br/>";
		for($i = 1; $i <= 9; ++$i)
		{
			echo $num." * ".$i."=".$i*$num."<br/>";    
		}
	}
}

$num = $_GET['num'];

try
{
	gugu($num);
}
catch(Exception $e)
{
	echo 'Error: ' .$e->getMessage();
}
?>