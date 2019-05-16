<?php

//$postField = array("id"=>"f", "pass"=>"1234");
//$Response = Call_Curl("http://127.0.0.1/auth_login.php", $postField, "GET");
$postField = array("accountno"=>"123", "action"=>"1234", "message"=>"구와아아아악!");
$Response = Call_Curl("http://127.0.0.1/LogSystem.php", $postField, "POST");
echo $Response['body'];

/* 사용법
// $postField = array( "IP"			=> $_SERVER['REMOTE_ADDR'],
//					   "Query"		=> $this->QUERY,
//					   "Comment"	=> $this->COMMENT
//					);
//
// $Response = Call_Curl("http://url...path.php", $postField);
//
// 결과 $Response는 배열로 Body와 ResultCode 반환
//
// $response['body'] < 결과 Body
// $response['code'] < 결과 code
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

		/*------------------------------------------------------
		// #1 URL 인코드한 쿼리 문자열 생성
		// 1. 입략된 params를 key와 value로 분리
		// 2. post)param이라는 배열을 key=value 타입으로 생성
		// 혹시나 value가 배열인 경우는 ,로 나열
		foreach($postFields as $key=>&$val)
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