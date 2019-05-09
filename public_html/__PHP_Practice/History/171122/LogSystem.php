<?php
/*
* SystemLog 테이블 템플릿,

CREATE TABLE `SystemLog_template` (
  `no`			int(11) NOT NULL AUTO_INCREMENT,
  `date`		datetime NOT NULL,
  `AccountNo`	varchar(50) NOT NULL,
  `Action`		varchar(128) DEFAULT NULL,
  `Message`		varchar(1024) DEFAULT NULL,

  PRIMARY KEY (`no`)
) ENGINE=MyISAM;
*/

//------------------------------------------------------------
// 시스템 로그 남기는 함수 / DB, 테이블 정보는 _Config.php 참고.
//
// POST 방식으로 로그 스트링을 저장한다.
//
// $_POST['AccountNo']	: 유저
// $_POST['Action']		: 액션구분
// $_POST['Message']	: 로그스트링
//------------------------------------------------------------
include "_Config.php";

// 1. AccountNo, Action, Message 인자 확인 및 없을시 디폴트 값 생성

// DB 연결

// 월별 테이블 이름 만들어서 로그 INSERT

// 테이블 없을시 (errno = 1146) 테이블 생성 후 재 입력
//
// 템플릿 테이블 사용 생성쿼리 - CREATE TABLE 새테이블 LIKE SystemLog_template
// ex) CREATE TABLE `SystemLog_201711` LIKE SystemLog_template;
//
//	if ( errno == 1146 )
//	{
//			테이블 생성 쿼리 ..
//			로그쿼리 다시 쏘기
//	}
//
?>













