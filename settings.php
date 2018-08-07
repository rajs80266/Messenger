<?php
	$conn = mysqli_connect('fdb21.awardspace.net','2768726_messenger','Aa#12345','2768726_messenger');
	session_start();
	$m=$_COOKIE["log"];
	if(isset($_POST['back']))
	{
		header('Location: chats.php');
	}
	if(isset($_POST['cancel']))
	{
		header('Location: settings.php');
	}
	
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
	
	if(isset($_POST['i1']))
	{
		$fname=htmlentities($_POST['fname']);
		$lname=htmlentities($_POST['lname']);
		$gender=htmlentities($_POST['gender']);
		$email=htmlentities($_POST['email']);
		$day=htmlentities($_POST['day']);
		$month=htmlentities($_POST['month']);
		$year=htmlentities($_POST['year']);
		$uname=htmlentities($_POST['uname']);
		if(empty($fname)) 
		{
			$fname=$_SESSION['ffname'][$m];
		}
		if(testname($fname))
		{
			$fname=$_SESSION['ffname'][$m];
		}
		if(testname($lname))
		{
			$lname=$_SESSION['flname'][$m];
		}
		if (!filter_var($email, FILTER_VALIDATE_EMAIL) && !empty($email)) 
		{
			$email=$_SESSION['femail'][$m];
		}
		if(empty($day) || empty($month) || empty($year))
		{
			$day=$_SESSION['fday'][$m];
			$month=$_SESSION['fmonth'][$m];
			$year=$_SESSION['fyear'][$m];
		}
		if(testnum($day) || testnum($month) || testnum($year))
		{
			$day=$_SESSION['fday'][$m];
			$month=$_SESSION['fmonth'][$m];
			$year=$_SESSION['fyear'][$m];
		}
		if(testdate($day,$month,$year))
		{
			$day=$_SESSION['fday'][$m];
			$month=$_SESSION['fmonth'][$m];
			$year=$_SESSION['fyear'][$m];
		}
		if(empty($uname))
		{
			$uname=$_SESSION['funame'][$m];
		}
		$_SESSION['ffname'][$m]=$fname;
		$_SESSION['flname'][$m]=$lname;
		$_SESSION['fgender'][$m]=$gender;
		$_SESSION['femail'][$m]=$email;
		$_SESSION['fday'][$m]=$day;
		$_SESSION['fmonth'][$m]=$month;
		$_SESSION['fyear'][$m]=$year;
		$_SESSION['funame'][$m]=$uname;
		
		mysqli_query($conn,"UPDATE details SET FName='$fname' WHERE id='$m'");
		mysqli_query($conn,"UPDATE details SET LName='$lname' WHERE id='$m'");
		mysqli_query($conn,"UPDATE details SET Gender='$gender' WHERE id='$m'");
		mysqli_query($conn,"UPDATE details SET Email='$email' WHERE id='$m'");
		mysqli_query($conn,"UPDATE details SET Day='$day' WHERE id='$m'");
		mysqli_query($conn,"UPDATE details SET Month='$month' WHERE id='$m'");
		mysqli_query($conn,"UPDATE details SET Year='$year' WHERE id='$m'");
		mysqli_query($conn,"UPDATE details SET UName='$uname' WHERE id='$m'");	
	}
	if(isset($_POST['cp']))
	{
		header('Location: changePassword.php');
	}
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Account Settings</title>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
		<script>
			function view1()
			{
				document.getElementById("e1").style.display = "none";
                document.getElementById("s1").style.display = "block";
			}
		</script>
		<style>
			#e1
			{
				background-color:rgba(100,200,150,0.7);
				padding:100px 0px 100px 0px;
			}
			#s1
			{
				background-color:rgba(200,100,150,0.7);
				padding:0px 0px 100px 0px;
				display:none;
				font-size:40px;
			}
			input
			{
				font-size:40px;
			}
		</style>
	</head>
	<body>
		<form id="lo" method="POST" action="<?php echo $_SERVER['PHP_SELF'];?>">
            <input type="submit" name="back" value="Back" style="float:right;">
		</form>
		<h1 style="font-size:50px;margin:0 0 0 30%">Account Settings</h1>
		<form method="POST" action="<?php echo $_SERVER['PHP_SELF'];?>" id="s1">
		<center>
			<br><br>
			<label style="font-size:50px;">First Name : </label>
			<input type="text" name="fname" value=<?php echo $_SESSION['ffname'][$m];?>>
			<br><br><br>
			<label style="font-size:50px;">Last Name : </label>
			<input type="text" name="lname" value=<?php echo $_SESSION['flname'][$m]; ?>>
			<br><br><br>
			<label style="font-size:50px;">Gender:</label><br>
			<input type="radio" name="gender" value="male"  checked><span style="font-size:50px">Male</span>
			<input type="radio" name="gender" value="female" ><span style="font-size:50px">Female</span>
			<input type="radio" name="gender" value="other"><span style="font-size:50px">Other</span>
			<br><br><br>
			<label style="font-size:50px;">Email id:</label>
			<input type="text" name="email" value=<?php echo $_SESSION['femail'][$m]; ?>>
			<br><br><br>
			<label style="font-size:50px;">Birth Date:</label>
			<input type="text" name="day" placeholder="DD" size="1" value=<?php echo $_SESSION['fday'][$m]; ?> style="font-size:40px"> /
			<input type="text" name="month" placeholder="MM" size="1" value=<?php echo $_SESSION['fmonth'][$m]; ?> style="font-size:40px"> /
			<input type="text" name="year" placeholder="YYYY" size="1" value=<?php echo $_SESSION['fyear'][$m]; ?> style="font-size:40px">
			<br><br><br>
			<label style="font-size:50px;">User Name:</label>
			<input type="text" name="uname" value=<?php echo $_SESSION['funame'][$m]; ?>>
			<br><br><br>			
			<input type="submit" name="cp" value="Change Password" style="font-size:50px;">		
			<br><br><br>
			<input type="submit" name="i1" value="save" style="font-size:50px; margin:0px 25px 0px 0px;">
			<input type="submit" name="cancel" value="cancel" style="font-size:50px;margin:0px 0px 0px 25px;">
		</center>
		</form>
		<br>
		<center>
			<p id="e1" style="font-size:50px;margin:1%">
				First Name : <?php echo $_SESSION['ffname'][$m]; ?>
				<br><br>
				Last Name : <?php echo $_SESSION['flname'][$m]; ?>
				<br><br>
				Gender : <?php echo $_SESSION['fgender'][$m]; ?>
				<br><br>
				Email : <?php echo $_SESSION['femail'][$m]; ?>
				<br><br>
				Birthday : <?php echo $_SESSION['fday'][$m]; ?>/<?php echo $_SESSION['fmonth'][$m]; ?>/<?php echo $_SESSION['fyear'][$m]; ?>
				<br><br>
				User Name: <?php echo $_SESSION['funame'][$m]; ?>
				<br><br>
				<button style="font-size:50px;margin:0px 0px 0px 10px" onclick="view1()">Edit</button>
			</p>
		</center>
	</body>
</html>