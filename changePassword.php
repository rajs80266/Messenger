<?php
	$conn = mysqli_connect('fdb21.awardspace.net','2768726_messenger','Aa#12345','2768726_messenger');
	session_start();
        
	if($_SESSION['logout'][$_COOKIE["log"]]==1)
		header('Location: index.php');
	$m=$_COOKIE["log"];
	$cpasswordErr=$passwordErr=$err='';
	
	if(isset($_POST['cancel']))
	{
		header('Location:settings.php');
	}
	
	if(isset($_POST['save']))
	{
		$op=htmlentities($_POST['op']);
		$password=htmlentities($_POST['password']);
		$cpassword=htmlentities($_POST['cpassword']);
		$flag=1;
		
		$details = [];
        $result = mysqli_query($conn,'SELECT * FROM details');
        while ($row = $result->fetch_assoc()) {
            $details[] = $row;
        }
		foreach($details as $detail)
		{
			if($detail['id']==$m)
			{
				if($detail['Password']!=$op)
					$flag=0;
				break;
			}
		}		
		
		if($flag==0)
		{
			$err="Wrong Password";
		}
		else if(empty($password))
		{
			$passwordErr = "Required field";
		}
		else if(empty($cpassword))
		{
			$cpasswordErr = "Required field";
		}
		else if($password!=$cpassword)
		{
			$cpasswordErr = "Password not matched";
		}
		else
		{
			mysqli_query($conn,"UPDATE details SET Password='$password' WHERE id='$m'");
			header('Location:settings.php');
		}
	}
?>

<!DOCTYPE html>
<html>
	<head>
		<title>Change Password</title>
		<style>
			form
			{
				font-size:50px;
				margin-top:30%;
			}
			input
			{
				font-size:40px;
				margin:35px 0px 35px 0px;
			}
			span
			{
				color:red;
			}
		</style>
	</head>
	<body>
		<center>
		<form method="POST" action="<?php echo $_SERVER['PHP_SELF'];?>">
			<h1>Change Password</h1>
			<label>Old Password : <span><?php echo $err;?></span></label>
			<input type="password" name="op" value="<?php echo isset($_POST['op']) ? $op : ''; ?>">
			<br>
			<label>New Password : <span><?php echo $passwordErr;?></span></label>
			<input type="password" name="password" value="<?php echo isset($_POST['password']) ? $password : ''; ?>">
			<br>
			<label>Confirm Password : <span><?php echo $cpasswordErr;?></span></label>
			<input type="password" name="cpassword" value="<?php echo isset($_POST['cpassword']) ? $cpassword : ''; ?>">
			<br>
			<input type="submit" name="cancel" value="cancel">
			<input type="submit" name="save" value="save">
		</form>
		</center>
	</body>
</html>