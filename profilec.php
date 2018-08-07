<?php
	session_start();
	if($_SESSION['logout'][$_COOKIE["log"]]==1)
		header('Location: index.php');
	//adding Stars
	$conn = mysqli_connect('fdb21.awardspace.net','2768726_messenger','Aa#12345','2768726_messenger');
	//adding Ends
	$i=1;
	while($i<=$_SESSION['count'])
	{
		$s='chat'.$i;
		$detn=$detg=$dete=$detb=$detu='';
		if(isset($_POST[$s]))
		{
			$detn=$_SESSION['ffname'][$i].' '.$_SESSION['flname'][$i].'<br>';
			$detg=$_SESSION['fgender'][$i].'<br>';
			if($_SESSION['femail'][$i]=='')
				$dete='---';
			else
				$dete=$_SESSION['femail'][$i].'<br>';
			$detb=$_SESSION['fday'][$i].'/'.$_SESSION['fmonth'][$i].'<br>';
			$detu=$_SESSION['funame'][$i].'<br>';
			break;
		}
		$i++;
	}
	if($i<=$_SESSION['count'])
		$_SESSION['x'.$_COOKIE["log"]]=$i;
	if(isset($_POST['message']))
	{		
		$i=$_SESSION['x'.$_COOKIE["log"]];
		$a=$_COOKIE["log"];
		if($i<$a)
		{
			$b=$i;
			$c=$a;
			$v=2;
		}
		else
		{
			$b=$a;
			$c=$i;
			$v=1;
		}
		
		//adding starts
		$onlines = [];
        $result = mysqli_query($conn,'SELECT * FROM online');
        while ($row = $result->fetch_assoc()) {
            $onlines[] = $row;
        }
		foreach	($onlines as $online)
		{
			if($online['id1']==$b && $online['id2']==$c)
			{
				if($online['flag']!=0)
					$v=3;
				break;
			}
		}
		mysqli_query($conn,"UPDATE online SET flag='$v' WHERE id1='$b' AND id2='$c';");
		
		
		$notifications = [];
        $result = mysqli_query($conn,'SELECT * FROM notification');
        while ($row = $result->fetch_assoc()) {
            $notifications[] = $row;
        }
		foreach	($notifications as $notification)
		{
			if($notification['id1']==$b && $notification['id2']==$c)
			{
				if($a==$b && $notification['flag']==2)
					mysqli_query($conn,"UPDATE notification SET flag='0' WHERE id1='$b' AND id2='$c';");
				else if($a==$c && $notification['flag']==1)
					mysqli_query($conn,"UPDATE notification SET flag='0' WHERE id1='$b' AND id2='$c';");
				break;
			}
		}
		//adding Ends
		header('Location: chatting.php');
	}
	if(isset($_POST['cancel']))
		header('Location: chats.php');
?>

<DOCTYPE html>
<html>
	<head>
		<title>Details</title>
		<link rel="stylesheet" href="stylesssss.css">
	</head>
	<body>
		<center><h1>Details:</h1>
		<div id="details">
			<div class="lab">Name : <span class="ans"><?php echo $detn;?></span></div>
			<div class="lab">Gender : <span class="ans"><?php echo $detg;?></span></div>
			<div class="lab">email : <span class="ans"><?php echo $dete;?></span></div>
			<div class="lab">Birthday : <span class="ans"><?php echo $detb;?></span></div>
			<div class="lab">Username : <span class="ans"><?php echo $detu;?></span></div>
			<br><br>
			<form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
				<input type="submit" name="message" value="Message">
				<br><br>
				<input type="submit" name="cancel" value="Cancel">
			</form>
		</div></center>
	</body>
</html>