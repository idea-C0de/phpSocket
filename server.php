<?php
/* 引入資料庫 */
//include_once('../php/config.php'); // use -> $PDOLink

/*  php的二進制、八進制、十進制、十六進制轉換函數說明
     Url: http://fanli7.net/a/bianchengyuyan/PHP/20130814/411155.html
    decoct  10 => 8
    dechex 10 => 16
    bin2hex 2 => 16
    bindec  2 => 10
    octdec  8 => 10
    hexdec 16 => 10
    base_convert 任意禁制轉換
*/

// Set time limit to indefinite execution
set_time_limit (0);

$AO_tech_title = "【AO智慧訊息：AO-Tech PHP-Socket伺服器 已啟動中，等待通訊接通...】\n";
print $AO_tech_title;

// Set the ip and port we will listen on, IP addr: 127.0.0.1
$address = '127.0.0.1'; // 
$port = 9527; 
$max_clients = 13;

// Array that will hold client information
$client = array();

// Create a TCP Stream socket    
$sock = socket_create(AF_INET, SOCK_STREAM, 0);

// Bind the socket to an address/port
socket_bind($sock, $address, $port) or die('Could not bind to address');

// Start listening for connections
socket_listen($sock);

// Loop continuously
while(true){
    // Setup clients listen socket for reading
    $read[0] = $sock;
    for ($i = 0; $i < $max_clients; $i++)
    {
        if (isset($client[$i]))
        if ($client[$i]['sock']  != null)
            $read[$i + 1] = $client[$i]['sock'];
    }

    // Set up a blocking call to socket_select()
    $ready = socket_select($read, $write = NULL, $except = NULL, $tv_sec = NULL);
    /* if a new connection is being made add it to the client array */
    if (in_array($sock, $read)) 
    {
        for ($i = 0; $i < $max_clients; $i++) 
        {
            if(!isset($client[$i])){
                $client[$i] = array();
                $client[$i]['sock'] = socket_accept($sock);
                echo("......資料準備開始接收......\n"); // Accepting incomming connection...
                break;
            }
            elseif ($i == $max_clients - 1)
                print ("too many clients");
        }
        if (--$ready <= 0)
            continue;
    } // end if in_array

    // If a client is trying to write - handle it now
    for ($i = 0; $i < $max_clients; $i++) // for each client
    {
        if (isset($client[$i]))
        if (in_array($client[$i]['sock'] , $read))
        {
            $input = socket_read($client[$i]['sock'] , 1024);  //strlen();
            
            /* 如果input為空，或直接主動斷開server也會跟你斷 */
            if($input == null)
            {
                echo("【AO智慧訊息： TCP client已主動進行中斷...】\n");
                unset($client[$i]);
                exit(); 
            }

            if($input){
                $today = date("m-d-Y h:i:s");   
                $R_today = date("y m d h i s"); 
                $NotDataType = "Not"; 
                $bin2hexOutput = bin2hex($input); // "0127000000000001"
                 //$chrOutput = chr($input);          // int ord => ASCll碼 

                 /* 資料轉換 */
                 // $getID = str_split("0127000000000001".$bin2hexOutput,4);
                 // $getID2 = str_split("0127000000000001".$bin2hexOutput,2);
                 // $getID3 = str_split("0127000000000001".$bin2hexOutput,1);
                 // $getPassword = str_split("0127000000000001".$bin2hexOutput,8);

                $RRR2hex = dechex($bin2hexOutput);

                /* hexdec to dechex to date 0x. , intval, substr */
                 $y_today = hexdec(dechex(date("y")));
                 $m_today = hexdec(dechex(date("m")));
                 $d_today = hexdec(dechex(date("d")));
                 $h_today = hexdec(dechex(date("h")));
                 $i_today = hexdec(dechex(date("i")));
                 $s_today = hexdec(dechex(date("s")));

                /* 回傳: 年 月 日 時 分 秒 次, socket_write($client[$i]['sock'],$aoMonitorRclient[$use_index]); */
                socket_write($client[$i]['sock'],$y_today.$m_today.$d_today.$h_today.$i_today.$s_today);

                /* Server端 show bin2hex Output */
                echo $bin2hexOutput . "\n";
                // $clien_ip_addr = socket_getpeername($client[$i]['sock'],$address,$port);

                /* use database */
                
                /* end use database */

            }
        } else {
            // Close the socket
            if (isset($client[$i]))
            //echo("Client disconnected\n");
            if ($client[$i]['sock'] >= 0){ 
                socket_close($client[$i]['sock']); 
                unset($client[$i]); 
            }
        }
    }
} // end while

// Shutting Down
echo("Shutting down\n");

// Close the master sockets
socket_close($sock);
?>
