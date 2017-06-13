<?php
/* 引入資料庫 */
include_once('php/config.php');

// set some variables
$host = "127.0.0.1";
$port = 50000;

header('Content-type: text/html; charset=utf-8'); //指定utf8編碼 
error_reporting(E_ALL);
set_time_limit(0);
ob_implicit_flush();

echo $title = "PHP-Socket伺服器 已啟動...\n";
echo "Host:" . $host . "\n";
echo "Port:" . $port . "\n";

if (($sock = socket_create(AF_INET, SOCK_STREAM, SOL_TCP)) < 0) {
    echo "socket_create() failed: reason: " . socket_strerror(socket_last_error()) . "\n";
}

if (socket_bind($sock, $host, $port) < 0 ) {
    echo "socket_bind() failed: reason: " . socket_strerror(socket_last_error($sock)) . "\n";
}

if (socket_listen($sock,10) < 0) {
    echo "socket_listen() failed: reason: " . socket_strerror(socket_last_error($sock)) . "\n";
}

$count = 0;
$today = date("H:i:s");   
$NotDataType = "Not";
do {
    if (($msgsock = socket_accept($sock)) < 0) {
        echo "socket_accept() failed: reason: " . socket_strerror(socket_last_error($sock)) . "\n";
        //break;
    }   //else {

    do {

         $buf = socket_read($msgsock, 2048);                                 // PHP_NORMAL_READ                                   

         if($buf){
            socket_write($msgsock, $buf . "\n", strlen($buf . "\n"));        // response Success!
            $bufHex = bin2hex($buf);
            echo $today . "::" . $bufHex . "\n";
         } else {             
            break;
         }

    } while (true);
    socket_close($msgsock);

} while (true);
socket_close($sock);
?>