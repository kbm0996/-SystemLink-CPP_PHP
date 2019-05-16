<?php
/*
171124 GameLog, Profiler

1. GameLog
	  GameLog_template TABLE구조 = 메일 참조
	 대게 DB에 변화가 있을때 로그를 남긴다. (오류로 인한 컨텐츠 실행 실패는 DB에 변화가 없으므로 게임로그를 남기지 않는다)
	 로그만 가고 적용이 안된다던가 문제가 발생할 여지가 있으니 Transaction처리해서 한꺼번에 넘겨야한다. 편의상 그때그때 로그 배열에 넣고 적용시킬때 한꺼번에 DB에 저장한다.
	  많은 로그를 묶어서 DB에 저장할 방법이 PHP나 html 구조상 딱히 없음. 때문에 JSON 사용
	 
	 JSON 디코드, 배열로 여러번 보내기
	JSON →  array(array(param1, param2, param3), array(param1, param2, param3));
	PHP →	array(
				array(param1, param2, param3), 
				array(param1, param2, param3)
			);
	DB →	LogChunk = {
				{"param1":value, "param2":value, "param3":value}
				{"param1":value, "param2":value, "param3":value}
			}
	 * GameLog 클래스(싱글톤) 메일 참조
	 PHP에서 클래스는 생성자와 소멸자는 클래스명, ~클래스명이 아니라 __construct(), __destruct()임 
	 - $LOG_URL ip저장?
	 - $LogArray 보낼 Log를 모아놓은 배열
	 ~ 싱글톤을 사용하긴 했으나 싱글톤을 사용할만한 대상이 아니기도 하고 결국엔 전역에다가 놓고 쓰기 때문에 올바른 사용법도 아니다. 그저 연습삼아 사용해본 것 뿐
	 - static $instance 포인터 느낌
	 - php 클래스의 멤버 변수 접근은 ::가 아니라 ->임
	 - php와 C# 클래스의 함수는 C의 클래스 함수 반환값이 사본인 것에 반해 포인터를 반환함!!
	 - SaveLog() 함수 : DB에 쏘는 역할 LOG_Local_http (Curl이나 Socket만들어서 보내는 함수)

	 SaveLog등등 로그에 대한 로그도 넣어야 로그에 대해서 파악이 가능
	 
#. require?
	include문이랑 같으나 실패시 fatal error

#. json_encode (php5부터 내장된 함수) php 객체를 str로 만드는 것
	   json_decode str을 php 객체로 만드는 것
	  ex) $Object = json_decode($_POST['LogChunk'], true); 
	  두번째 인자가 false일 경우 class로 뽑는데 애매한 경우가 있음 
	  LogChunk = " { "MemberNo":2, "TEST" : 3 }
	  따라서 true로 하여 array로 뽑는다. 
	  $Object[0]['MemberNo'], $Object[0]['TEST'], $Object[1]['MemberNo'], $Object[1]['TEST']
	  이렇게 사용할 경우 LogChunk가 array로 들어오지 않을 경우 예외처리를 해주자
	  if( !is_array($LogChunk) )
	  {
		  FileLog남기기
		  exit;
	  }

	  foreach ($LogChunk as $iter) //php에서 &는 php의 레퍼런스임. 이 변수 변경시 배열 내용이 바뀜
	  $AccountNo = $iter['AccountNo'];

	  이후는 _Log/LogSystem.php와 유사..

#. 로그시스템에서 에러가 나면 본인의 에러를 저장할 수 없으므로 파일에 저장해야함
	 file_put_contents('php://stderr', "No Chunk~"); // 파일 IO 함수. C언어 stdin(핸들러 - 키보드) stdout(핸들러 - 콘솔) 개념
	stderr = 에러 출력 포트. 에러 로그에 저장됨. 

2. Profiler
	 게임로그처럼 싱글톤 클래스
	 ProfilingLog_template TABLE 구조 = 메일 참조
	 최대시간 최소시간 평균시간 호출횟수 등을 기록. DB를 매번 UPDATE하기에는 너무 느려짐. 게다가 PHP는 튜닝할만한 거리도 없음.
	따라서, 프로파일러를 호출하면 해당 파일 전체에 대해서 프로파일링을 할 것임. DB에 낱개로 INSERT만 할 것

	no
	date
	ip
	accountno  
	action				현재 파일 이름
	T_Page				현재 파일의 처음부터 끝까지 사용 시간(아래 변수들 T_Mysql_Conn Mysql ~ T_Log의 총합 + php로직파트 소요 시간)
	T_Mysql_Conn Mysql	연결에 소요된 시간 - 느려질 여지가 있는 부분임(localhost를 쓰던가 127.0.0.1을 쓰던가 속도차이 등등)
	T_Mysql				쿼리를 날리는데 소요된 시간의 합계(쿼리를 한 번에 10개 날린다 하면 그 10번의 합계)
	T_ExtAPI			외부 API에 대한 시간 - 확인용(ex. 어떤 특정 파일들이 느린데, 아무리봐도 느릴게 없음. 알고보니 퍼블리셔측에서 제공한 서비스가 문제였음)
	T_Log				로그를 보내는데 소요된 시간
	T_ru_u				시스템 자체 사용 시간 (커널, 유저) // 리눅스전용
	T_ru_s				파악해도 써먹을데가 없음 // 윈도우에서는 지원안함
	Query				굉장히 큰 TEXT타입. 현재 파일의 모든 쿼리문을 저장
	Comment				추가 정보..

	그냥 POST방식으로 모두 던질 것 (=_Log/LogSystem.php와 같음)

	사용은 
	$PF->StartCheck(PF_MYSQL);
	$PF->StopCheck(PF_MYSQL, "Sleep1"); // T_Mysql에 합산. Sleep1 항목에 저

	define("PF_PAGE", 1);~~
	C의 define이랑 다름. php 내부적으로는 그냥 검색하는 식임. 성능의 메리트는 없음
	
	///////////////////////////////////////////////////

	클래스 설명
	 function_exists("getrusage"); // getrusage라는 함수가 있는지 없는지 확인
	getrusage 윈도우에는 존재하지 않는 함수. 리눅스전용. 있어도 사용할게 없음
	
	__Construct 저장할 배열 초기 세팅

	싱글톤 세팅하는 곳에서..
	if(rand()%100 < $LogRate)
		로그 남김
	코드 추가

	Log_Save
	게임로그 세이브 함수랑 같음

	안전하게 $_SERVER[HTTP_X_FORWARDED_FOR] 로 수정

	///////////////////////////////////////////////////

	* 인터프리터언어다보니 C언어에서 만든 프로파일러처럼 define으로 프로파일링 작동을 제어할 수 없음
	떄문에 항상 프로파일링하면 느려지므로 웹에서는 Call의 확률을 지정하는 방식으로 함. 라이브시엔 확률을 낮추고 테스트 환경에서는 100%인 방식으로

#. Startup.php, Cleanup.php
	유사 Framework. php용 Library. 각종 초기화 및 선언 등등.. C의 stdafx와 유사

*/

?>