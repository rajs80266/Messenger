<?php
	$conn = mysqli_connect('fdb21.awardspace.net','2768726_messenger','Aa#12345','2768726_messenger');
	session_start();
	if($_SESSION['logout'][$_COOKIE["log"]]==1)
		header('Location: index.php');
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
	header('Location: chats.php');
?>