<?php
	session_start();
	if($_SESSION['logout'][$_COOKIE["log"]]==1)
		header('Location: index.php');
	$conn = mysqli_connect('fdb21.awardspace.net','2768726_messenger','Aa#12345','2768726_messenger');
	$i=1;
	while($i<=$_SESSION['count'])
	{
		$s='accept'.$i;
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
	if(isset($_POST['friend']))
	{
		$i=$_SESSION['x'.$_COOKIE["log"]];
		$_SESSION['ffriend'][$i][$_SESSION['ffriend_count'][$i]]=$_COOKIE["log"];
		$_SESSION['ffriend'][$_COOKIE["log"]][$_SESSION['ffriend_count'][$_COOKIE["log"]]]=$i;
		$a=$_COOKIE["log"];
		if($i<$a)
		{
			$b=$i;
			$c=$a;
		}
		else
		{
			$b=$a;
			$c=$i;
		}
		mysqli_query($conn,"UPDATE friends SET flag='1' WHERE id1='$b' AND id2='$c';");
		
		$_SESSION['ffriend_count'][$i]++;
		$a=$_SESSION['ffriend_count'][$i];
		mysqli_query($conn,"UPDATE details SET Friend_Count='$a' WHERE id='$i'");
		
		$_SESSION['ffriend_count'][$_COOKIE["log"]]++;
		$b=$_COOKIE["log"];
		$a=$_SESSION['ffriend_count'][$b];
		mysqli_query($conn,"UPDATE details SET Friend_Count='$a' WHERE id='$b'");
		
		header('Location: chats.php');
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
				<input type="submit" name="friend" value="Accept Friend Request">
				<br><br>
				<input type="submit" name="cancel" value="Cancel">
			</form>
		</div></center>
	</body>
</html>