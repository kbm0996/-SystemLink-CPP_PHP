<?php
/* 사용법
$Params = array("id"=>"f", "pass"=>"1234");
echo Call_Socket("http://127.0.0.1/auth_login.php", $Params, 'GET');
*/
//--------------------------------------------
// $url : http:// or https:// 가 포함된 전체 URL
// $params : ['key'] = value 타입. 배열형태로 데이터 입력. array("id"=>"test1", "pass"=>"test1"
// $type : GET / POST
// 결과가 필요없는 경우에만 사용 (로그)
//--------------------------------------------
function Call_Socket($url, $params, $type = 'POST')
{
	
	$post_string = http_build_query($params, '', '&');

	$parts = parse_url($url); // 문자열을 URL로 해석하고 URL의 구성요소에 맞게 연관배열을 생성해 준다. URL의 유효성은 검사하지 않는다.
	
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
	// Data goes in the path for a GET request

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
//---------------------------------------------
// curl을 사용하여 로그저장 URL 전송
// 해당 결과값을 배열로 반환 [body] / [code]
//---------------------------------------------
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