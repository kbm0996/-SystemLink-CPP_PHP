<?php
include "_LIB/lib_Log.php";
include "_Config_Log.php";

$GameLog = GAMELog::getInstance($cnf_GAME_LOG_URL);

$GameLog->AddLog(0,1,2,0,0,1,2, 'PS');// �α� �߰�
$GameLog->AddLog(0,1,3,0,0,1,2, 'PS');// �α� �߰�
$GameLog->AddLog(0,1,4,0,0,1,2, 'PS');// �α� �߰�
$GameLog->AddLog(0,1,12,0,2,1,4, 'PS');// �α� �߰�
$GameLog->AddLog(0,1,13,0,0,1,2, 'PS');// �α� �߰�

$GameLog->SaveLog(); 
?>