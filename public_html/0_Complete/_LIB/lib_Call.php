<?php
/* 사용법
$Params = array("id"=>"f", "pass"=>"1234");
echo Call_Socket("http://127.0.0.1/auth_login.php", $Params, 'GET');
*/
function Call_Socket($url, $params, $type = 'POST')
{
	// 입력된 params 를 key 와 value 로 분리하여 
	// post_param 이라는 배열로 key=value 타입으로 생성.
	// 혹시나 value 가 배열인 경우는 , 로 나열.
	foreach ($params as $key => &$val)
	{
		if (is_array($val))
			$val = implode(',', $val);
		$post_params[] = $key.'='.urlencode($val);
	}
	
	// $post_params 에는 [0] id=test1 / [1] pass=test1  형태로 들어감.
	// 이를 & 기준으로 하나의 스트링으로 붙임.
	$post_string = implode('&', $post_params);

	//$post_string = http_build_query($params, '', '&');

	$parts = parse_url($url); 

	// http / https에 따라 소켓접속 타임아웃 30초
	// ssl 설정으로 인해 현재는 작동되지 않는 소스?
	if ($parts['scheme'] == 'http')
	{
		$fp = fsockopen($parts['host'], isset($parts['port'])?$parts['port']:80, $errno, $errstr, 10);
	}
	else if($parts['scheme'] == 'https')
	{
		$fp = fsockopen("ssl://" . $parts['host'], isset($parts['port'])?$parts['port']:443, $errno, $errstr, 30);
	}

	if(!$fp)
	{
		// 에러로그 실제로는 화면출력해선 안됨 (클라에게 전송되는 것이므로)
		echo "$errstr ($errno)<br/>\n";
		return 0;
	}

	$ContentsLength = strlen($post_string); // POST 방식의 경우 body의 크기가 틀리면 해독하지않음
	// GET 방식은 URL에 parameter를 붙임
	if('GET' == $type)
	{
		$parts['path'] .= '?' . $post_string;
		$ContentsLength = 0;
	}

	// HTTP 프로토콜 생성 - 띄어쓰기도 틀려선 안됨
	$out = "$type ".$parts['path']." HTTP/1.1\r\n";
	$out.= "Host: ".$parts['host']."\r\n";
	$out.= "Content-Type: application/x-www-form-urlencoded\r\n";
	$out.= "Content-Length:".$ContentsLength."\r\n";
	$out.= "Connection: Close\r\n\r\n";
	// Data goes in the request body for a Post request

	// POST 방식이면 프로토콜 뒤에 parameter를 붙임
	if('POST' == $type && isset($post_string))
		$out.= $post_string;

	$Result = fwrite($fp, $out);

	// 바로 끊어버리는 경우 서버측에서 이를 무시해버리는 경우가 있음
	// 대표적으로 cafe24 서버의 경우 그러함
	// fread를 한 번 호출하여 조금이라도 받아주는 것으로 이를 해결 가능
	 $Response = fread($fp, 1000);
	// echo $Response;

	fclose($fp);

	return $Result;
}

/* 사용법
$postField = array("id"=>"f", "pass"=>"1234");
$Response = Call_Curl("http://127.0.0.1/auth_login.php", $postField, "GET");
echo $Response['body']; < 결과 body
echo $response['code']; < 결과 code
*/
function Call_Curl($url, $postFields = array(), $type = 'POST')
{
	$ci = curl_init();

	curl_setopt($ci, CURLOPT_USERAGENT, "TEST AGENT ");
	curl_setopt($ci, CURLOPT_CONNECTTIMEOUT, 30);
	curl_setopt($ci, CURLOPT_TIMEOUT, 30);
	curl_setopt($ci, CURLOPT_RETURNTRANSFER, TRUE);		// 결과값을 받을것인지
	curl_setopt($ci, CURLOPT_SSL_VERIFYPEER, FALSE);	// SSL 체크같은데 true시 안되는 경우가 많
	curl_setopt($ci, CURLOPT_HEADER, FALSE);			// 헤더 출력 여부
	if('POST' == $type)
	{
		curl_setopt($ci, CURLOPT_POST, TRUE);			// Post Get 접속 여부
		if(!empty($postFields))
		{
			curl_setopt($ci, CURLOPT_POSTFIELDS, $postFields);
		}
	}
	else
	{
		curl_setopt($ci, CURLOPT_POST, FALSE);
		$post_string = http_build_query($postFields, '', '&');

		$url .= '?' . $post_string;
	}

	curl_setopt($ci, CURLOPT_URL, $url);				// 접속할 URL 주소

	$response = array();
	//---------------------------------------------
	// 실제 HTTP 전송
	//---------------------------------------------
	$response['body'] = curl_exec($ci);
	$response['code'] = curl_getinfo($ci, CURLINFO_HTTP_CODE);

	curl_close($ci);
	return $response;
}

?>