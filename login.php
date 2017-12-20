<?php 
require_once('common.php');
$msg = "";
if($_SERVER["REQUEST_METHOD"] == "POST") {
	$inputUser = test_user_input($_POST["username"]);
	$inputPass = test_user_input($_POST["password"]);
	$pass = sha1($inputPass);
	if(isset($inputUser, $inputPass)) {
		$s = "ss";
		$sql = $conn->prepare("SELECT * FROM login_admin where username=? and password =?");
		$sql->bind_param($s, $inputUser, $pass);
		$sql->execute();
		$result = mysqli_stmt_get_result($sql);	
		$count = mysqli_num_rows($result);
		print_r($count);
		if($count == 1) {
			$_SESSION["admin"] = $inputUser; 
			header("location:products.php");
		} else {
			$msg = translate("Wrong Username or Password. Please retry");
		}
	}
}
$conn->close();
?>
<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>
<form action="<?= htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
	<input type="Username" name="username" placeholder="Username" autocomplete="off">
	<span><?= $msg ?></span><br />
	<input type="Password" name="password" placeholder="Password" autocomplete="off"><br />
	<input type="submit" name="submit" value="<?= translate("Submit") ?>">
</form>
</body>
</html>