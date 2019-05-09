<?php
/*---------------------------
- 클라이언트 IP/포트, 서버 IP/포트 를 출력하는 php
---------------------------*/
echo "클라이언트 IP		:" . $_SERVER['REMOTE_ADDR'] . "<br/>";
echo "클라이언트 포트 : 	" . $_SERVER['REMOTE_PORT'] . "<br/>";
echo "서버 IP :	" . $_SERVER['SERVER_ADDR'] . "</br>";
echo "서버 포트 :	" . $_SERVER['SERVER_PORT'] . "</br>";
?>
