<?php
    $page = $_SERVER['PHP_SELF'];
	$sec = "5";
	header("Refresh: $sec; url=$page");
        
	session_start();
        
	if($_SESSION['logout'][$_COOKIE["log"]]==1)
		header('Location: index.php');
	$conn = mysqli_connect('fdb21.awardspace.net','2768726_messenger','Aa#12345','2768726_messenger');
	//$friend_reqs = mysqli_fetch_all(mysqli_query($conn,'SELECT * FROM friend_reqs'),MYSQLI_ASSOC);	
        $friend_reqs = [];
        $result = mysqli_query($conn,'SELECT * FROM friend_reqs');
        while ($row = $result->fetch_assoc()) {
            $friend_reqs[] = $row;
        }
        
        //$friends = mysqli_fetch_all(mysqli_query($conn,'SELECT * FROM friends'),MYSQLI_ASSOC);
        $friends = [];
        $result = mysqli_query($conn,'SELECT * FROM friends');
        while ($row = $result->fetch_assoc()) {
            $friends[] = $row;
        }
        
        $details = [];
        $result = mysqli_query($conn,'SELECT * FROM details');
        while ($row = $result->fetch_assoc()) {
            $details[] = $row;
        }
        
        $_SESSION['count']=0;
	foreach($details as $detail)
	{
		$_SESSION['count']++;
		$_SESSION['fid'][$_SESSION['count']]=$detail['id'];
		$_SESSION['ffname'][$_SESSION['count']]=$detail['FName'];
		$_SESSION['flname'][$_SESSION['count']]=$detail['LName'];
		$_SESSION['fgender'][$_SESSION['count']]=$detail['Gender'];
		$_SESSION['femail'][$_SESSION['count']]=$detail['Email'];
		$_SESSION['fday'][$_SESSION['count']]=$detail['Day'];
		$_SESSION['fmonth'][$_SESSION['count']]=$detail['Month'];
		$_SESSION['fyear'][$_SESSION['count']]=$detail['Year'];
		$_SESSION['funame'][$_SESSION['count']]=$detail['UName'];
		$_SESSION['fpassword'][$_SESSION['count']]=$detail['Password'];
		$_SESSION['ffriend_count'][$_SESSION['count']]=$detail['Friend_Count'];
		$_SESSION['ffriend_req_count'][$_SESSION['count']]=$detail['Friend_Req_Count'];
	}
        
	if(isset($_POST['lo']))
	{
		$_SESSION['logout'][$_COOKIE["log"]]=1;
		header('Location: index.php');
	}
	
        if(isset($_POST['as']))
        {
                header('Location: settings.php');
        }
        
	$user=$_SESSION['ffname'][$_COOKIE["log"]];
	$i=1;
	
	$listf=$listr=$listc=$listn='';
	
	if(!isset($_SESSION['count']))
		$_SESSION['count']=0;
	
	for($i=1;$i<=$_SESSION['count'];$i++)
		$_SESSION['ffriend_req_count'][$i]=0;
	foreach	($friend_reqs as $friend_req)
	{
		if($friend_req['flag']==1)
		{
			$_SESSION['ffriend_req'][$friend_req['id2']][$_SESSION['ffriend_req_count'][$friend_req['id2']]]=$friend_req['id1'];
			$_SESSION['ffriend_req_count'][$friend_req['id2']]++;
		}
		else if($friend_req['flag']==2)
		{
			$_SESSION['ffriend_req'][$friend_req['id1']][$_SESSION['ffriend_req_count'][$friend_req['id1']]]=$friend_req['id2'];
			$_SESSION['ffriend_req_count'][$friend_req['id1']]++;
		}
	}

	for($i=1;$i<=$_SESSION['count'];$i++)
		$_SESSION['ffriend_count'][$i]=0;
	foreach($friends as $friend)
	{
		if($friend['flag']==1)
		{
			$_SESSION['ffriend'][$friend['id1']][$_SESSION['ffriend_count'][$friend['id1']]]=$friend['id2'];
			$_SESSION['ffriend_count'][$friend['id1']]++;
			$_SESSION['ffriend'][$friend['id2']][$_SESSION['ffriend_count'][$friend['id2']]]=$friend['id1'];
			$_SESSION['ffriend_count'][$friend['id2']]++;
		}
	}
	
	for($i=1;$i<=$_SESSION['count'];$i++)
	{
		if($i==$_COOKIE["log"])
			continue;
		$flag=1;
		for($j=0;$j<$_SESSION['ffriend_req_count'][$_COOKIE["log"]];$j++)
			if($i==$_SESSION['ffriend_req'][$_COOKIE["log"]][$j])
				$flag=0;
				
		for($j=0;$j<$_SESSION['ffriend_req_count'][$i];$j++)
			if($_COOKIE["log"]==$_SESSION['ffriend_req'][$i][$j])
				$flag=0;
		
		if($flag==1)
		 	$listf.='<br><input style="font-size:35px;" type="submit" name="profile'.$i.'" value="'.$_SESSION['ffname'][$i].' '.$_SESSION['flname'][$i].'"><br>';
	}
	
	for($j=0;$j<$_SESSION['ffriend_count'][$_COOKIE["log"]];$j++)
	{
		$i=$_SESSION['ffriend'][$_COOKIE["log"]][$j];
		
		if($i<$_COOKIE["log"])
		{
			$a=$i;
			$b=$_COOKIE["log"];
		}
		else
		{
			$b=$i;
			$a=$_COOKIE["log"];
		}
		$notifications = [];
        $result = mysqli_query($conn,'SELECT * FROM notification');
        while ($row = $result->fetch_assoc()) {
            $notifications[] = $row;
        }
		foreach($notifications as $notification)
		{
			if($a==$notification['id1'] && $b==$notification['id2'])
			{
				if( ($a==$i && $notification['flag']==1) || ($a!=$i && $notification['flag']==2) )
					$listn.='<br><input style="font-size:35px;" type="submit" name="chat'.$i.'" value="'.$_SESSION['ffname'][$i].' '.$_SESSION['flname'][$i].'" id="green" class="chatlist"><br>';
				else
					$listc.='<br><input style="font-size:35px;" type="submit" name="chat'.$i.'" value="'.$_SESSION['ffname'][$i].' '.$_SESSION['flname'][$i].'" class="chatlist"><br>';
			}
		}
	}
	
	for($j=0;$j<$_SESSION['ffriend_req_count'][$_COOKIE["log"]];$j++)
	{
		$i=$_SESSION['ffriend_req'][$_COOKIE["log"]][$j];
		$flag=1;
		
		for($k=0;$k<$_SESSION['ffriend_count'][$i];$k++)
			if($_COOKIE["log"]==$_SESSION['ffriend'][$i][$k])
				$flag=0;
		
		if($flag==1)
			$listr.='<br><input style="font-size:35px;" type="submit" name="accept'.$i.'" value="'.$_SESSION['ffname'][$i].' '.$_SESSION['flname'][$i].'" class="chatlist"><br>';
	}
?>

<!DOCTYPE html>
<html>
	<head>
		<title>Find Friends</title>
		<link rel="stylesheet" href="stylesssss.css">
	</head>
	<body>
		<p style="align:right;font-size:25px;font-weight:bold;padding-right:30%;">
			Hello <?php echo $user; ?>
			<form id="lo" method="POST" action="<?php echo $_SERVER['PHP_SELF'];?>">
                                <input type="submit" name="as" value="Account Settings" style="float:right;font-size:140%">
				<input type="submit" name="lo" value="Log out" style="font-size:140%">
			</form>
		</p>
		<div id="findfriends" style="float:left">
			<h1 style="font-size:50px">Find friends</h1>
			<form id="ff" method="POST" action="profile.php">
			<center>
				Names<br>
				<?php echo $listf;?>
			</center>
			</form>
		</div>
		<div id="chats">
			<center><h1 style="font-size:80px">chats</h1></center>
			<form id="af" method="POST" action="profilec.php">
			<center>
				Names<br>
				<?php echo $listn;?>
				<?php echo $listc;?>
				<br>
			</center>
			</form>
		</div>
		<div id="acceptfriends" style="float:left">
			<h1 style="font-size:40px">Friend Requests</h1>
			<form id="cf" method="POST" action="profilev.php">
			<center>
				Names<br>
				<?php echo $listr;?>
			</center>
			</form>
		</div>
	</body>
</html>