<?php
function KeyGen32()
{
	$Key = hash('md5', time() + rand(0,1000));

	for($i=0; $i<6; ++$i)
	{
		$UpperCase = rand(0,31);
		$Key[$UpperCase] = strtoupper($Key[$UpperCase]);
	}

	return substr($Key, 0, 32);
}
?>