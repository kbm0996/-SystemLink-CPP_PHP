<?php

global $HTTP_RAW_POST_DATA;

echo "Find RAW POST DATA : " . $HTTP_RAW_POST_DATA;
echo '<br>';


// php.ini 파일에  always_populate_raw_post_data = on  설정이 있어야 함
// POST 로 보내야 하며, Form 데이터가 들어 있어야 한다.

/* 최소한의 헤더 
User-Agent: Fiddler
Host: 127.0.0.1
Content-Type: application/x-www-form-urlencoded
Content-Length: xx
*/

// POST RAW 데이터는 Fiddler 를 사용하여 테스트 해야 함
// Fiddler 의 Compser 탭 > POST 선택 > URL 과 파일이름 적음 > 아래 에디터 박스에 다음 헤더 작성

//    User-Agent: Fiddler
//    Host: 127.0.0.1
//    Content-Type: application/x-www-form-urlencoded

// 하단 BODY 에디터 박스 영역에 RAW 데이터로 보낼 데이터 입력 
//-------------------------------------------------------------------



// 하지만 이 방법은 PHP 7 부터 사라지므로 권장하지 않는 방법.


echo "php://input Type RAW DATA : " . file_get_contents("php://input");



// -- 다음은 RAW 영역을 \r\n 으로 구역별 분리 시키는 방법

// RAW 데이터 부분을 바로 JSON 으로 사용하지 않고 /r/n 으로 구역을 분리하여
// 다양한 용도로 사용하기 위함.

// 이유는, JSON 데이터는 암호화가 되면 좋은데 바로 RAW 전체를 암호화 해버리면
// 암호를 풀 방법조차 찾을 수가 없음 (공통 키를 사용 해야함)

// 그래서 /r/n 으로 구역을 나누고 앞쪽은 회원정보 따위 필요는 하지만 그닥 중요치 않은 내용
// 을 담고 그 내용을 기준으로 암호키를 찾아봄.


// POST 데이터에
/*
	System=시스템데이터&Contents={ 게임데이터 }
	
	위 방법처럼 POST 부분을 그냥 변수 데입 방법으로 
	
	System 변수에 시스템적인 기본 정보를 넣고
	Contents 변수에 암호화된 게임 JSON 을 넣어도 됨.

	굳이 /r/n 으로 분리시킨 이유는 간지를 위함.
	
*/
/*
$BodyData = explode("\r\n", $HTTP_RAW_POST_DATA);
var_dump($BodyData);
*/








?>