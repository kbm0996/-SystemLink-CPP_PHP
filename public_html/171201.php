<?php
///////////////////////////////////////////////////
// 171201 php
///////////////////////////////////////////////////

function QuitError($ErrorCode, $ExtMessage = '')
{
	// 1. ResJSON_Error
	// 2. DB_Disconnect(); // 어차피 MySQL에 대한 연결과 result 전체가 반환되므로 없어도 됨. 있으면 빠르게 종료
	exit();
}

function ResponseJSON_Error($ErrorCode, $ExtMessage)
{
	// 1. JSON인코딩
	// 2. SystemLOG
	// 3. 에러코드, 에러메시지 Echo
}

// 활용 : 예외 상황 시 언제든 삽입 
// ex. if($password='') quiterror(~~,~~);

/* Startup */
// trim($string); : 문자열의 양쪽 여백 제거
// '    abc'	→ 'abc' 
// ' ddd    '	→ 'ddd'
// '          '	→ ''
// ex)	$id = trim($id)
//		if($id='') quiterror(~~,~~);

/* Register */
// 같은 ID가 동시에 가입되어 동기화문제로 인해 Insert 도중에 에러가 났을 경우
//	df_RESULT_REGISTER_ID_ERROR

/* Login */
//

/* StageClear */
// 1. data_stage에 stageid가 있는지 검사
// 2. clearstage(두 항목 다 index)에 stageid가 있는지 검사. 있으면 경험치X, 없으면 경험치O
//		exp+=clearstageexp
// 3. 레벨업처리. 레벨업데이터 정렬하고, 작거나 같은 단계 중 가장 큰 하나 뽑고, 적용 limit 
//  레벨업 처리시에 중단될 경우도 고려하여 트랜잭션 처리를 해주는 것이 좋다

/* 나홀로 테스트 */
// 유니티로 클라 후딱 만들고 기능별 버튼 생성 후 테스트

/*
 생성자는 C#에서
 Wake는 유니티가 해주는 것

  둘 중 하나만 하면 됨
*/
?>