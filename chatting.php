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
        $first_name=$_SESSION['ffname'][$_SESSION['x'.$_COOKIE["log"]]];
        
        foreach($messages as $message)
	{
		if($a==$message['id1'] && $b==$message['id2'])
		{
			$chat=$message['Message'];
			break;
		}
	} 
	if(isset($_POST['show']))
	{
		if($a==$m)
			$chat.='<div class="mmessage"><h5 style="float:right">'.date('H:i', mktime(date('h')+5,date('i')+30,date('s'))).'</h5><h2>'.$_POST['message'].'</h2></div>';
		else
			$chat.='<div class="umessage"><h5 style="float:right">'.date('H:i', mktime(date('h')+5,date('i')+30,date('s'))).'</h5><h2>'.$_POST['message'].'</h2></div>';
		$_POST['message']='';
		foreach($messages as $message)
		{
			if($a==$message['id1'] && $b==$message['id2'])
			{
				mysqli_query($conn,"UPDATE messages SET Message='$chat' WHERE id1='$a' AND id2='$b';");
				break;
			}
		}
		
		//adding starts
		$onlines = [];
        $result = mysqli_query($conn,'SELECT * FROM online');
        while ($row = $result->fetch_assoc()) {
            $onlines[] = $row;
        }
		
		$notifications = [];
        $result = mysqli_query($conn,'SELECT * FROM notification');
        while ($row = $result->fetch_assoc()) {
            $notifications[] = $row;
        }
		
		foreach($onlines as $online)
		{
			if($a==$online['id1'] && $b==$online['id2'])
			{
				if($online['flag']!=3)
				{
					if($a==$m)
						mysqli_query($conn,"UPDATE notification SET flag='1' WHERE id1='$a' AND id2='$b';");
					else
						mysqli_query($conn,"UPDATE notification SET flag='2' WHERE id1='$a' AND id2='$b';");
				}
				break;
			}
		}
		//adding Ends
		
		header('Location: chatting.php');
	}
    if(isset($_POST['back']))
	{
		//adding starts
		$onlines = [];
        $result = mysqli_query($conn,'SELECT * FROM online');
        while ($row = $result->fetch_assoc()) {
            $onlines[] = $row;
        }
		
		$notifications = [];
        $result = mysqli_query($conn,'SELECT * FROM notification');
        while ($row = $result->fetch_assoc()) {
            $notifications[] = $row;
        }
		
		foreach($onlines as $online)
		{
			if($a==$online['id1'] && $b==$online['id2'])
			{
				if($a==$m)
					$x=$online['flag']-1;
				else
					$x=$online['flag']-2;
				mysqli_query($conn,"UPDATE online SET flag='$x' WHERE id1='$a' AND id2='$b';");
				break;
			}
		}
		//adding Ends
        header('Location: chats.php');
	}
?>

<!DOCTYPE html>
<html>
	<head>
		<title>Messages</title>
		<link rel="stylesheet" href="stylesssss.css">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	</head>
	<body>	
                <button id="view_all" onclick="view()" style="float:right">Whole Conversation</button>
                <button id="view_last" onclick="last()" style="float:right">End of chat</button>
                
                <form  method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>" id="back">
			<input type="submit" name="back" value="Back" id="bottom" >
                        <span id="name"><?php echo $first_name; ?></span>
		</form>
                
                <div id="wrapper">
                        <div id="messages"></div>
                </div>
		<script>
			$(document).ready(function(){
				$('#messages').load('test.php');
                                setTimeout(function(){
					window.location.href = "logout.php";
				},60000);
				refresh();
			});
			
			function refresh()
			{
				setTimeout(function(){
					$('#messages').fadeOut('fast').load('test.php').fadeIn('fast');
					refresh();
				},10000);
			}
		</script>
		<script>
                        function view() {
                            document.getElementById("messages").style.position = "relative";
                            document.getElementById("view_all").style.display = "none";
                            document.getElementById("view_last").style.display = "block";
                        }
                        function last() {
                            document.getElementById("messages").style.position = "absolute";
                            document.getElementById("view_all").style.display = "block";
                            document.getElementById("view_last").style.display = "none";
                        }
                </script>
		<br>
		<form  method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>" id="cm">
			<textarea rows="2" cols=49% name="message" id="msg" style="font-size:30px ;" autofocus></textarea>
                        <input type="submit" name="show" value="send" id="show">
		</form>
	</body>
</html>