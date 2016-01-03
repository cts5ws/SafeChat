<?php

    session_start();

    //database info
    require_once('dbconfig.php');
    $con = mysqli_connect($DB_HOST, $DB_USER, $DB_PASSWORD, $DB_DATABASE);

    $name = $_SESSION["name"];
    $chat = $_SESSION["chat"];
    $message = mysqli_escape_string($con, $_POST["message"]);

    $messageNum = mysqli_num_rows(mysqli_query($con, "select * from `$chat`"));

    $time = date("h:i:sa");
    $mydate=getdate(date("U"));
    $date = $mydate[mon] . "/" . $mydate[mday] . "/" . $mydate[year];
    $timestamp = $date . " @ " . $time;

    mysqli_query($con,"update `$chat` set m_num = m_num + 1");
    mysqli_query($con, "insert into `$chat` (name, message, time, m_num) values ('$name','$message','$timestamp','1')");

    if($messageNum == 20){
        mysqli_query($con, "delete from `$chat` where `m_num` = '21'");
    }

    $data = [];
    $iter = 1;

    $result = mysqli_query($con, "select * from `$chat` order by `m_num` asc");
    while($row = mysqli_fetch_array($result)){
        $data[$iter] = $row;
        $iter++;
    }

    mysqli_close($con);

    $data["current_user"] = $name;
    echo json_encode($data);
?>