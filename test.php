<?php
	session_start();
	if($_SESSION['logout'][$_COOKIE["log"]]==1)
		header('Location: index.php');
	/*$page = $_SERVER['PHP_SELF'];
	$sec = "5";
	header("Refresh: $sec; url=$page");*/
	
	$conn = mysqli_connect('fdb21.awardspace.net','2768726_messenger','Aa#12345','2768726_messenger');
	//$messages = mysqli_fetch_all(mysqli_query($conn,'SELECT * FROM messages'),MYSQLI_ASSOC);
	$messages = [];
        $result = mysqli_query($conn,'SELECT * FROM messages');
        while ($row = $result->fetch_assoc()) {
            $messages[] = $row;
        }
        
        $m=$_COOKIE["log"];
	$u=$_SESSION['x'.$_COOKIE["log"]];
	if($m<$u)
	{
		$a=$m;
		$b=$u;
	}
	else
	{
		$a=$u;
		$b=$m;
	}
	foreach($messages as $message)
	{
		if($a==$message['id1'] && $b==$message['id2'])
		{
			$chat=$message['Message'];
			break;
		}
	}              
        echo $chat;
?>