<?php
error_reporting(E_ALL);
set_time_limit(0);
ob_implicit_flush();
print "Starting Socket Server...\n"; 
$address = '127.0.0.1';
$port = 10000;

if (($sock = socket_create(AF_INET, SOCK_STREAM, SOL_TCP)) < 0) {
    echo "socket_create() failed: reason: " . socket_strerror(socket_last_error()) . "\n";
}

if (socket_bind($sock, $address, $port) < 0 ) {
    echo "socket_bind() failed: reason: " . socket_strerror(socket_last_error($sock)) . "\n";
}

if (socket_listen($sock,99) < 0) {
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

        $buf = socket_read($msgsock, 2048); //PHP_NORMAL_READ
        $talkback = "PHP: You said \n";
        socket_write($msgsock, $talkback, strlen($talkback));
        echo $today . "::" . bin2hex($buf) . "\n";
            
    // }

        // if(++$count >= 3){
        //     break;
        // };

    } while (true);
    socket_close($msgsock);

} while (true);

socket_close($sock);
?>