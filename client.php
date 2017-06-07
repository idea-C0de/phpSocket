<?php
/* Get */
    $Client = $_GET[sn];

/* PHP socket client ex. */
    error_reporting(E_ALL);
    echo "<h2>TCP/IP Connection</h2>\n";

/* Create a TCP/IP socket. */
    $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
    if ($socket === false) {
        echo "socket_create() failed: reason: " . socket_strerror(socket_last_error()) . "\n";
    } else {
        echo "Andy socket test OK.\n";
    }

    echo "Attempting to connect to '127.0.0.1' on port '10000'...";

    $result = socket_connect($socket, '127.0.0.1', 10000);
    if ($result === false) {
        echo "socket_connect() failed.\nReason: ($result) " . socket_strerror(socket_last_error($socket)) . "\n";
    } else {
        echo "My PHP client OK.\n";
    }
    
    /* 我要傳遞的值 */
    $in = "Cool My Andy Locker Client Success!!\r\n";
    //$in = "HEAD / HTTP/1.1\r\n";
    //$in .= "Host: 127.0.0.1\r\n";
    $in .= "Connection: Close\r\n\r\n";
    $out = '';

    echo "Sending HTTP HEAD request...";
    socket_write($socket, $Client, strlen($in));
    echo "OK.\n";

    echo "Reading response:\n\n";
    while ($out = socket_read($socket, 2048)) {
        echo $out;
    }

    echo "Closing socket...";
    socket_close($socket);
    echo "OK.\n\n";

?>