<?php 
// set some variables
$host = "127.0.0.1";
$port = 10000;
$number = 10;

// don't timeout!
header('Content-type: text/html; charset=utf-8');
error_reporting(E_ALL);
set_time_limit(0);
ob_implicit_flush();

echo $title = "PHP-Socket server strat...\n";
echo "Host:" . $host . "\n";
echo "Port:" . $port . "\n";
echo "Number:" . "Max" . $number . "value/s" ."\n";

/* create socket */
$socket = socket_create(AF_INET, SOCK_STREAM, 0) or die("Could not create socket\n");

/*  bind socket to port */
$result = socket_bind($socket, $host, $port) or die("Could not bind to socket\n");

/* start listening for connections */
$result = socket_listen($socket, 3) or die("Could not set up socket listener\n");

/* accept incoming connections, spawn another socket to handle communication */
    $count = 0;
    do 
    {
        $spawn = socket_accept($socket) or die("Could not accept incoming connection\n");
        /* read client input */
        $input = socket_read($spawn, 1024) or die("Could not read input\n");
        /* clean up input string */
        $input = trim($input);
        $input_hex = bin2hex($input);
        echo "Client Message : ".$input_hex . "\r\n";
        /* reverse client input and send back */
        //$output = strrev($input_hex) . "\n";
        socket_write($spawn, $input, strlen ($input)) or die("Could not write output\n");
        
                    /* 資料庫處理與轉換 */
        
        
                    /* End 資料庫處理與轉換 */
        
                /* close sockets */
                socket_close($spawn);
            
            if(++$count >= $number){
                break;
            };

    }while(true);
    
  /* close sockets */   
  socket_close($socket);
?>