<?php 
require_once('common.php');
$msg = "";
if($_SERVER["REQUEST_METHOD"] == "POST") {
	$inputUser = test_user_input($_POST["username"]);
	$inputPass = sha1(test_user_input($_POST["password"]));
	if(isset($inputUser, $inputPass)) {
		$sql = $conn->prepare("SELECT * FROM login_admin where username=? and password =?");
		$sql->bind_param("ss", $inputUser, $inputPass);
		$sql->execute();
		$result = mysqli_stmt_get_result($sql);	
		if(mysqli_num_rows($result) == 1) {
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