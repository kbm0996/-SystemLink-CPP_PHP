<?php
/*---------------------------
- money.php?money=100,20,3000,400,5010,200,3004,3040,200,300,1000
�ĸ��� ���� �� �������� ���ڸ� $money �� ����.
�ĸ� �������� �и��Ͽ� �迭�� ����.
��� �� ���� ���� �� 1000���� �ĸ��� �־ ���.

16,274

+number_format �� Ȱ�� 
---------------------------*/

function sum($str = 'value')
{
	$sum = 0;
	if($str != null)
	{
		//string ������
		$strTok = explode(',' , $str);

		//�迭 ũ�� �������� 
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