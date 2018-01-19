<?php
require_once("common.php");
$cartProducts = array();

//display cart products only
if(isset($_SESSION["cart"]) && count($_SESSION["cart"])) {
    //code for remove btn
    if(!empty($_GET["action"]) && $_GET["action"] == "remove") {
        if(($key = array_search($_GET["id"], $_SESSION["cart"])) !== false) {
            unset($_SESSION["cart"][$key]);
        }
    }

    $stmt =$conn->prepare("SELECT * FROM productsnew WHERE Id IN 
    (" . implode(", ", array_fill(0, count($_SESSION["cart"]), '?')) . ")");
    $params = array(
        implode("", array_fill(0, count($_SESSION["cart"]), 'i'))
    );
    $params = array_merge($params, $_SESSION["cart"]);
    $paramsRef = array();
    foreach ($params as $key => $value) {
        $paramsRef[] = &$params[$key];
    }
    call_user_func_array(array($stmt, 'bind_param'), $paramsRef);
    $stmt->execute();
    $result = mysqli_stmt_get_result($stmt);

    if($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $cartProducts[] = $row;
        }
    }
}

$i = 0;
$nameErr = $contactDetailsErr = $succes = "";
if(isset($_POST["checkout"])) {
    $order = "";
    $totalsum = 0;
    foreach ($cartProducts as $key => $value) {
        $order .= "<tr>";
        $order .= "<td>" . $cartProducts[$key]['Title'] . "</td>";
        $order .= "<td>" . $cartProducts[$key]['Description'] . "</td>";
        $order .= "<td>" . $cartProducts[$key]['Price'] . "</td>"; 
        $order .= "<td> <img src=\"" . "http://".$_SERVER['HTTP_HOST'] . substr($_SERVER['SCRIPT_NAME'], 0,
        strrpos($_SERVER['SCRIPT_NAME'], "/")+1) ."uploads/" . $cartProducts[$key]['Image'] . "\" /><td>";
        $order .= "</tr>";
        $totalsum += $cartProducts[$key]["Price"];
    }
    $contactDetails = clean_user_input($_POST["contactDetails"]);
    $name = clean_user_input($_POST["name"]);
    $comments = clean_user_input($_POST["comments"]);
    $subject = translate("Form submision");
    $headers = "From: " . $contactDetails . "\r\n";
    $headers .= "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    $message = "<html><body>";
    $message .= "<table border='1'";
    $message .= $order;
    $message .= "<tr><td colspan='2'>" . translate('Total') . "</td><td colspan='2'>" . $totalsum. "</td></tr>";
    $message .= "</table>";
    $message .= "</html></body>";

    $userName = preg_match("/^[a-zA-Z ]*$/",$name);
    $userMail = filter_var($contactDetails, FILTER_VALIDATE_EMAIL);

    if(!$userName) {
        $nameErr = translate("Only letters and white space allowed");
    }

    if(!$userMail) {
        $contactDetailsErr = translate("Email is not valid");
    }

    if($userName && $userMail) {
        mail(_EMAIL, $subject, $message, $headers);
       $succes = translate("Your email was sent succesfully!");
       session_unset($_SESSION["cart"]); 
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html>
<head>
    <title></title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
<?php foreach ($cartProducts as $key => $value) :?>
    <div class="product"> 
        <div class="image">
            <img src="uploads/<?= $cartProducts[$key]["Image"] ?>"> 
        </div>
        <div class="productdetails">
            <div class="productTitle"><?= $cartProducts[$key]["Title"] ?></div>
            <div class="productDescription"><?= $cartProducts[$key]["Description"] ?></div>
            <div class="productPrice"><?= $cartProducts[$key]["Price"] ?></div>
            <a href="cart.php?action=remove&amp;id=<?= $cartProducts[$key]["Id"] ?>" class="remove"><?= translate("Remove") ?></a>
        </div>
    </div>
<?php endforeach; ?>
<div class="linkToIndex">
    <a href="index.php"><?= translate("Go to index") ?></a>
</div>

<form method="post">
    <input type="text" name="name" placeholder="Name" required="required">
    <span><?= $nameErr ?></span><br />
    <input type="text" name="contactDetails" placeholder="Contact details" required="required">
    <span><?= $contactDetailsErr ?></span><br />
    <input type="text" name="comments" placeholder="Comments"><br />
    <input type="submit" name="checkout" value="Checkout">
    <span><?= $succes ?></span><br />
</form>
</body>
</html>