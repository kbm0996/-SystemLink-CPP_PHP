<?php
// DB STATUS ----------------------------------------------------
$g_DB_IP = "127.0.0.1";
$g_DB_ID = "root";
$g_DB_PASS = "1234";
$g_DB_NAME = "game_schema";
$g_DB_PORT = 3306;

// LOG DB URL ----------------------------------------------------
$cnf_SYSTEM_LOG_URL = "http://127.0.0.1/_LOG/LogSystem.php";
$cnf_GAME_LOG_URL = "http://127.0.0.1/_LOG/LogGame.php";
$cnf_PROFILING_LOG_URL = "http://127.0.0.1/_LOG/LogProfiling.php";

// LOG LEVEL --------------------------------------------------
//define('dfLOG_LEVEL_ERROR',	1);	// define : c����� define�� �ٸ��� �˻��ؼ� ã�� �� ����
//define('dfLOG_LEVEL_DEBUG',	2);
//define('dfLOG_LEVEL_WARNG',	3);
$cnf_LOG_LEVEL = 3;

// PROFILING LOG RATE -----------------------------------------
$cnf_PROFILING_LOG_RATE = 1;	// �α� ���� Ȯ�� : ��� ��ɿ� ���� �������ϸ� �� ����
?>