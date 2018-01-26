<?php 
require_once('common.php');
$msg = "";

if($_SERVER["REQUEST_METHOD"] == "POST") {
    $inputUser = clean_user_input($_POST["username"]);
    $inputPass = clean_user_input($_POST["password"]);
    if(isset($inputUser, $inputPass)) {
        if($inputUser == _ADMIN_USERNAME && $inputPass == _ADMIN_PASSWORD) {
            $_SESSION["admin"] = $inputUser; 
            header("location:products.php");
            die();
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
<form method="post">
    <span><?= $msg ?></span><br />
    <input type="text" name="username" placeholder="<?= translate("Username") ?>" autocomplete="off">
    <input type="password" name="password" placeholder="<?= translate("Password") ?>" autocomplete="off"><br />
    <input type="submit" name="submit" value="<?= translate("Submit") ?>">
</form>
</body>
</html>