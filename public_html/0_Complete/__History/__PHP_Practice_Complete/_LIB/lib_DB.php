<?php
include "_Config_DB.php";
include "_LIB/lib_Log.php";

function DB_Connect()
{
	global $g_DB_IP;
	global $g_DB_ID;
	global $g_DB_PASS;
	global $g_DB_NAME;
	global $g_DB_PORT;
	global $g_DB;

	global $g_AccountNo;

	$g_DB = mysqli_connect($g_DB_IP, $g_DB_ID, $g_DB_PASS, $g_DB_NAME, $g_DB_PORT);
	if(!$g_DB)
	{
		file_put_contents('php://stderr', "Game DB ERROR # mysqli_connect() : " . mysqli_connect_error());
		exit;
	}

	mysqli_set_charset($g_DB, "utf8");
	// 필수X. 혹시라도 DB 인코딩이 UTF-8이 아닐 경우, UTF-8로 값을 달라고 DB에 요청하는 함수

	//	mysqli_autocommit($g_DB, FALSE);
	// 지속적인 트랜젝션 상태. 트랜젝션이 필요하지 않는 상황에서도 트렌젝션을 생성시켜 효율성이 떨어짐
}

function DB_Disconnect()
{
	global $g_DB;
	if(isset($g_DB))
	{
		mysqli_close($g_DB);
		// 정석대로 넣어본 것임. 필수는 아님, 알아서 APACHE가 다 해줌. 
		// DB 할 일 다 했으면 빨리 접속을 끊어주는게 좋음.
	}
}


function DB_ExecQuery($Query)
{
	global $g_DB;
	global $g_AccountNo;

	$Result	= mysqli_query($g_DB, $Query);
	if(!$Result)
	{
		LOG_System($g_AccountNo, "mysqli_connect()", $Query . " / " . mysqli_error($g_DB), 0);
		exit;
	}
	return $Result;
}


function DB_TransactionQuery($qryArr)
{
	global $g_DB;
	global $g_AccountNo;
	mysqli_begin_Transaction($g_DB);

	foreach($qryArr as $Query)
	{
		if(!is_string($Query))
		{
			LOG_System($g_AccountNo, "DB_TransactionQuery()", $Query . " / " . mysqli_error($g_DB), 0);
			mysqli_rollback($g_DB);
			exit;
		}

		if(!mysqli_query($g_DB, $Query))
		{
			LOG_System($g_AccountNo, "DB_TransactionQuery()", $Query . " / " . mysqli_error($g_DB), 0);
			mysqli_rollback($g_DB);
			exit;
		}
	}

	// LAST_INSERT_ID() : 현재 세션에서 방금 얻은 AUTO_INCREMENT값 반환
	//  연쇄적으로 해당 사용자의 DB에 접근해야할 경우 재활용 목적
	// 여러 Query문을 한 번에 날릴때 한 값만 얻기 때문에 추후 배열로 저장하여 해결해야함
	$insert_id = mysqli_insert_id($g_DB);
	mysqli_commit($g_DB);

	return $insert_id;
}

?>