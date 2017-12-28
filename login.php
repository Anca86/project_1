<?php 
require_once('common.php');
$msg = "";
if($_SERVER["REQUEST_METHOD"] == "POST") {
	$inputUser = test_user_input($_POST["username"]);
	$inputPass = test_user_input($_POST["password"]);
	if(isset($inputUser, $inputPass)) {
			if($inputUser == $adminUsername && $inputPass == $adminPassword) {
			    $_SESSION["admin"] = $inputUser; 
			    header("location:products.php");
			} else {
				$msg = _LOGIN_ERR_MSG;
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