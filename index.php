<?php
	session_start();
	$conn = mysqli_connect('fdb21.awardspace.net','2768726_messenger','Aa#12345','2768726_messenger');
	//$details = mysqli_fetch_all(mysqli_query($conn,'SELECT * FROM details'),MYSQLI_ASSOC);
	
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

	$fnameErr = $lnameErr = $emailErr = $dateErr = $unameErr = $passwordErr = $cpasswordErr = "";
	//if(!isset($_SESSION['count']))
		//$_SESSION['count']=0;
	/*for($j=1;$j<=$_SESSION['count'];$j++)
	{
		if($_SESSION['logout'][$j]==0)
		{
			$_SESSION['log']=$j;
			$_SESSION['x'.$_SESSION['log']]=0;
			header('Location: chats.php');	
		}
	}*/
	
	function testname($name)
	{
		for($i=0;$i<strlen($name);$i++)
			if(!ctype_alpha($name[$i]))
				break;
			
		if($i==strlen($name))
			return false;
		else
			return true;
	}
	
	function testnum($num)
	{
		for($i=0;$i<strlen($num);$i++)
			if(!is_numeric($num[$i]))
				break;
			
		if($i==strlen($num))
			return false;
		else
			return true;
	}
	
	function testuname($name)
	{
		for($i=1;$i<=$_SESSION['count'];$i++)
			if($name==$_SESSION['funame'][$i])
				break;
			
		if($i<=$_SESSION['count'])
			return true;
		else
			return false;
	}
        
        function testdate($day,$month,$year)
        {
                $flag=1;
                if($day>31 || $month>12 || ($day==31 &&($month==4 || $month==6 || $month==9 || $month==11)) || ($day>29&&$month==2)  || ($month==2&&$day==29&&(($year%4!=0||$year%100==0)&&($year%400!=0))))
                        $flag=0;
                $bod = strtotime($month.'/'.$day.'/'.$year);
                $now  = strtotime('today');
                if($now<$bod || $flag==0)
                        return true;
                else
                        return false;
        }
	
	if(isset($_POST['sign']))
	{
		$fname=htmlentities($_POST['fname']);
		$lname=htmlentities($_POST['lname']);
		$gender=htmlentities($_POST['gender']);
		$email=htmlentities($_POST['email']);
		$day=htmlentities($_POST['day']);
		$month=htmlentities($_POST['month']);
		$year=htmlentities($_POST['year']);
		$uname=htmlentities($_POST['uname']);
		$password=htmlentities($_POST['password']);
		$cpassword=htmlentities($_POST['cpassword']);
		
		if (empty($fname)) 
		{
			$fnameErr = "Required field";
		}
		else if(testname($fname))
		{
			$fnameErr = "Invalid!";
		}
		else if(testname($lname))
		{
			$lnameErr = "Invalid!";
		}
		else if (!filter_var($email, FILTER_VALIDATE_EMAIL) && !empty($email)) 
		{
			$emailErr = "Invalid email!"; 
		}
		else if(empty($day) || empty($month) || empty($year))
		{
			$dateErr = "Required Field";
		}
		else if(testnum($day) || testnum($month) || testnum($year))
		{
			$dateErr = "Invalid!";
		}
		else if(testdate($day,$month,$year))
		{
			$dateErr = "Invalid!";
		}
		else if(empty($uname))
		{
			$unameErr = "Required field";
		}
		else if(testuname($uname))
		{
			$unameErr = "Already taken";
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
			$_SESSION['count']++;
			$x=$_SESSION['count'];
			$_SESSION['ffname'][$_SESSION['count']]=$fname;
			$_SESSION['flname'][$_SESSION['count']]=$lname;
			$_SESSION['fgender'][$_SESSION['count']]=$gender;
			$_SESSION['femail'][$_SESSION['count']]=$email;
			$_SESSION['fday'][$_SESSION['count']]=$day;
			$_SESSION['fmonth'][$_SESSION['count']]=$month;
			$_SESSION['fyear'][$_SESSION['count']]=$year;
			$_SESSION['funame'][$_SESSION['count']]=$uname;
			$_SESSION['fpassword'][$_SESSION['count']]=$password;
			$_SESSION['ffriend_count'][$_SESSION['count']]=0;
			$_SESSION['ffriend_req_count'][$_SESSION['count']]=0;
			$_SESSION['logout'][$_SESSION['count']]=1;
					
			mysqli_query($conn,"INSERT INTO details(FName,LName,Gender,Email,Day,Month,Year,UName,Password,Friend_Count,Friend_Req_Count) VALUES('$fname','$lname','$gender','$email','$day','$month','$year','$uname','$password','0','0')");
				
			$x=$_SESSION['count'];
			foreach($details as $detail)
			{
				$y=$detail['id'];
				mysqli_query($conn,"INSERT INTO friend_reqs(id1,id2,flag) VALUES ('$y','$x','0')");
				mysqli_query($conn,"INSERT INTO friends(id1,id2,flag) VALUES ('$y','$x','0')");
				mysqli_query($conn,"INSERT INTO notification(id1,id2,flag) VALUES ('$y','$x','0')");
				mysqli_query($conn,"INSERT INTO online(id1,id2,flag) VALUES ('$y','$x','0')");
				mysqli_query($conn,"INSERT INTO messages(id1,id2) VALUES ('$y','$x')");
			}
                        $fname='';
                        $lname='';
                        $gender='';
                        $email='';
                        $day='';
                        $month='';
                        $year='';
                        $uname='';
                        $password='';
                        $cpassword='';
			echo '<script language="javascript">';
			echo 'alert("Successfully created your account")';
			echo '</script>';                             
		}	
	}
	
	if(isset($_POST['log']))
	{
		if(!isset($_SESSION['count']))
			$_SESSION['count']=0;
		$uname=htmlentities($_POST['luname']);
		$password=htmlentities($_POST['lpassword']);
		for($i=1;$i<=$_SESSION['count'];$i++)
		{
			if($_SESSION['funame'][$i]==$uname && $_SESSION['fpassword'][$i]==$password)
				break;
		}
		
		if($i<=$_SESSION['count'])
		{
			//$_SESSION['log']=$i;
			setcookie("log", $i, time() + (86400 * 30), "/");	
			//$_SESSION['x'.$_SESSION['log']]=0;
			$_SESSION['x'.$_COOKIE["log"]]=0;
			$_SESSION['logout'][$i]=0;
			header('Location: chats.php');
		}
		else
			echo 'Invalid username or wrong Password';
	}
?>

<!DOCTYPE html>
<html>
	<head>
		<title>Signup|Login</title>
		<link rel="stylesheet" href="stylesssss.css">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	</head>
	<body class="bg">
		
		<div id="login">
			<center>
				<form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
					<br><label>User Name:<span class="star">*</span></label> 
					<input type="text" name="luname" value="<?php echo isset($_POST['luname']) ? $uname : ''; ?>">
					<br><br>
					<label>Password:<span class="star">*</span></label>
					<input type="password" name="lpassword" value="<?php echo isset($_POST['lpassword']) ? $password : ''; ?>">
					<br><br>
					<input type="submit" name="log" value="Log In">
					<br><br>
				</form>
			</center>
		</div>
		
		<div id="signup">
			<center>
				<form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
					<br>
					<label>First Name:<span class="star">* <?php echo $fnameErr;?></span></label>
					<input type="text" name="fname" value="<?php echo isset($_POST['fname']) ? $fname : ''; ?>">
					<br><br>
					<label>Last Name:<span class="star"> <?php echo $lnameErr;?></span></label>
					<input type="text" name="lname" value="<?php echo isset($_POST['lname']) ? $lname : ''; ?>">
					<br><br>
					<label>Gender:</label><br>
					<input type="radio" name="gender" value="male" checked> Male
					<input type="radio" name="gender" value="female"> Female
					<input type="radio" name="gender" value="other"> Other  
					<br><br>
					<label>Email id:<span class="star"> <?php echo $emailErr;?></span></label>
					<input type="text" name="email" placeholder="xyz@gmail.com" value="<?php echo isset($_POST['email']) ? $email : ''; ?>">
					<br><br>
					<label>Birth Date:<span class="star">* <?php echo $dateErr;?></span></label>
					<input type="text" name="day" placeholder="DD" size="1" value="<?php echo isset($_POST['day']) ? $day : ''; ?>"> /
					<input type="text" name="month" placeholder="MM" size="1" value="<?php echo isset($_POST['month']) ? $month : ''; ?>"> /
					<input type="text" name="year" placeholder="YYYY" size="1" value="<?php echo isset($_POST['year']) ? $year : ''; ?>">
					<br><br>
					<label>User Name:<span class="star">* <?php echo $unameErr;?></span></label>
					<input type="text" name="uname" value="<?php echo isset($_POST['uname']) ? $uname : ''; ?>">
					<br><br>
					<label>Create Password:<span class="star">* <?php echo $passwordErr;?></span></label>
					<input type="password" name="password" value="<?php echo isset($_POST['password']) ? $password : ''; ?>">
					<br><br>
					<label>Confirm Password:<span class="star">* <?php echo $cpasswordErr;?></span></label>
					<input type="password" name="cpassword" value="<?php echo isset($_POST['cpassword']) ? $cpassword : ''; ?>">
					<br><br>
					<input type="submit" name="sign" value="Sign In">
					<br><br>
				</form>
			</center>
		</div>
	</body>
</html>