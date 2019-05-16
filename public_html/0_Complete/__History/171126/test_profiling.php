<?php
include "_Config_Log.php";
include "_LIB/lib_Profiling.php";

$PF = Profiling::getInstance($cnf_PROFILING_LOG_URL, $_SERVER['PHP_SELF']);

$PF->startCheck(PF_PAGE);

usleep(100000);

//$PF->startCheck(PF_MYSQL);
//usleep(100000);
//$PF->stopCheck(PF_MYSQL, "Item1");

$PF->startCheck(PF_MYSQL);
usleep(50000);
$PF->stopCheck(PF_MYSQL, "Item2");

$PF->startCheck(PF_MYSQL);
usleep(4000);
$PF->stopCheck(PF_MYSQL, "Item3");

$PF->startCheck(PF_LOG);
usleep(4000);
$PF->stopCheck(PF_LOG, "GameLog1");

$PF->startCheck(PF_EXTAPI);
usleep(4444);
$PF->stopCheck(PF_EXTAPI, "EXTItem");

$PF->startCheck(PF_EXTAPI);
usleep(3330);
$PF->stopCheck(PF_EXTAPI, "EXTItem2");

$PF->stopCheck(PF_PAGE, "Total Page");
$PF->LOG_Save();
?>