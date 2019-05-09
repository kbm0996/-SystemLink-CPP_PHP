<?php
//$Params = array("id"=>"f", "pass"=>"1234");
//echo Call_Socket("http://127.0.0.1/auth_login.php", $Params, 'GET');
$postField = array("accountno"=>"123", "action"=>"1232", "message"=>"구와아아아악!");
echo Call_Socket("http://127.0.0.1/LogSystem.php", $postField, "POST");

//--------------------------------------------
// PHP에서 소켓을 열어 URL 호출 후 끝낸다
// 
// 본래 PHP에서는 curl 라이브러리를 사용하여 외부 URL 호출을 하지만
// 이는 비동기 호출이 되지 않음. URL 호출 결과가 올 때까지 블럭이 걸림
// 
// 그래서 직접 소켓을 열고 웹서버에 데이터 전송 후 바로 종료
// 물론 상대 서버로 데이터를 전송하기까지는 블럭이 걸림. 하지만, 웹서버의 처리 시간까지 대기하지 않는다는 것
//
// 결과가 필요없는 경우에만 사용 (로그)
//
// $url : http:// or https:// 가 포함된 전체 URL
// $params : ['key'] = value 타입. 배열형태로 데이터 입력. array("id"=>"test1", "pass"=>"test1"
// $type : GET / POST
//--------------------------------------------
function Call_Socket($url, $params, $type = 'POST')
{
	/*------------------------------------------------------
	// #1 URL 인코드한 쿼리 문자열 생성
	// 1. 입략된 params를 key와 value로 분리
	// 2. post)param이라는 배열을 key=value 타입으로 생성
	// 혹시나 value가 배열인 경우는 ,로 나열
	foreach($params as $key=>&$val)
	{
		if(is_array($val))
			$val = implode(',', $val);
		$post_params[] = $key . '=' . urlencode($val);
	}

	// 이를 & 기준으로 하나의 str로 붙임
	$post_string = implode('&', $post_params);

	// $post_params에는 [0] id=test1 / [1] pass=test1 형태로 들어감
	-------------------------------------------------------*/
	// #2 URL 인코드한 쿼리 문자열 생성
	// php5부터 지원되는 코드
	$post_string = http_build_query($params, '', '&');


	// 입력된 url을 URL 요소별로 분석
	// $url = 'http://username:password@hostname:9090/path?arg=value#anchor';
	// scheme - e.g. http
	// host
	// port
	// user
	// pass
	// path
	// query - after the question mark?
	// fragment - after the hashmark #
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

	// echo $out;
	// echo "<br><br>";

	// 전송!!
	$Result = fwrite($fp, $out);

	// 바로 끊어버리는 경우 서버측에서 이를 무시해버리는 경우가 있음
	// 대표적으로 cafe24 서버의 경우 그러함
	// fread를 한 번 호출하여 조금이라도 받아주는 것으로 이를 해결 가능
	 $Response = fread($fp, 1000);
	 echo $Response;

	fclose($fp);

	return $Result;
}
?>