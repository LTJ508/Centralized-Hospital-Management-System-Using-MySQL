<?php
session_start();
include("connection.php");
include("css/customized.css");
?>

<!DOCTYPE html>
<html>
<body>
	<div id="joy" align="center">
		<b><font size="20">CHMS</font></b>
		<span style="float:right;">
			<?php
				if(isset($_SESSION['User_ID'])){
    			$User_Name = $_SESSION['User_Name'];
             	echo
                    '
                    <ul class="menu" style="padding:5px">
                        <li>'.'Welcome '.$User_Name.'
                        <ul class="sub-menu">
                            <li><a href="/chms/common/profile.php">Dashboard</a></li>
                            <li><a href="/chms/common/edit-profile.php">Profile</a></li>
                            <li><a href="/chms/common/logout.php">Log Out</a></li>
                        </ul>
                        </li>
                    </ul>
                    ';
                }
				else{
 					echo
 						'Welcome Guest'.'<br>'.
 						'<a href="/chms/common/login.php">Login</a>'.'||'.
 						'<a href="/chms/common/signup.php">Signup</a>';
				}
			?>
		</span>
	</div>

	<div id="joy" align="center">
	<ul class="menu">
        <li><a href="/chms/index.php">Home</a></li>
        <li>
            <a href="#">Doctor</a>
            <ul class="sub-menu">
                <li><a href="/chms/common/find-doctor.php">Find a Doctor</a></li>
            </ul>
        </li>
        <li>
            <a href="#">Pricing</a>
            <ul class="sub-menu">
                <li><a href="/chms/common/test-price.php">Test Pricing Info</a></li>
                <li><a href="/chms/common/medicine-price.php">Medicine Pricing Info</a></li>
            </ul>
        </li>
    </ul>
	</div>
    <br>
</body>
</html>