<?php
require_once("common.php");
if(isset($_SESSION["cart"])) {
    $stringIds = implode(", ", $_SESSION["cart"]);
    $stmt =$conn->prepare("SELECT * FROM productsnew WHERE Id IN ($stringIds)");
    $stmt->bind_param("s", $_SESSION["cart"]);
    $stmt->execute();
    $result = mysqli_stmt_get_result($stmt);
    if(!empty($_GET["action"]) && $_GET["action"] == "remove") {  
        foreach ($_SESSION["cart"] as $key => $value) {
            if ($value === $_GET["id"]) {
                unset($_SESSION["cart"][$key]);
            }
        }
    }
    if(count($_SESSION["cart"])== 0) {
        session_unset();
    }
}
$cartProducts = array();
if($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $cartProducts[] = $row;
    }
}
$i = 0;
$nameErr = $contactDetailsErr = "";
if(isset($_POST["checkout"])) {
    $order = "";
    $totalsum = 0;
    $i = 0;
    function imageUrl($i) {
        global $cartProducts;
        return "http://".$_SERVER['HTTP_HOST'].substr($_SERVER['SCRIPT_NAME'], 0, strrpos($_SERVER['SCRIPT_NAME'], "/")+1) . $cartProducts[$i]['Image'];
    }
    while ($i < count($cartProducts)) {
        $order .= "<tr>";
        $order .= "<td>" . $cartProducts[$i]['Title'] . "</td>";
        $order .= "<td>" . $cartProducts[$i]['Description'] . "</td>";
        $order .= "<td>" . $cartProducts[$i]['Price'] . "</td>"; 
        $order .= "<td>" . imageUrl($i) . "<td>";
        $order .= "</tr>";
        $totalsum += $cartProducts[$i]["Price"];
        $i++;
    }
	$contactDetails = test_user_input($_POST["contactDetails"]);
    $name = test_user_input($_POST["name"]);
    if (!preg_match("/^[a-zA-Z ]*$/",$name)) {
        $nameErr = translate("Only letters and white space allowed"); 
    } 
    if (!filter_var($contactDetails, FILTER_VALIDATE_EMAIL)) {
        $contactDetailsErr = translate("Email is not valid");
    }
    $comments = test_user_input($_POST["comments"]);
	$subject = translate("Form submision");
	$headers = translate("From: ") . $contactDetails . "\r\n";
    $headers .= "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    $message = "<html><body>";
    $message .= "<table border='1'";
    $message .= $order;
    $message .= "<tr><td colspan='2'>" . translate('Total') . "</td><td colspan='2'>" . $totalsum. "</td></tr>";
    $message .= "</table>";
    $message .= "</html></body>";
    if(preg_match("/^[a-zA-Z ]*$/",$name) && (filter_var($contactDetails, FILTER_VALIDATE_EMAIL))) {
	   mail($to, $subject, $message, $headers);
    }
}
?>
<!DOCTYPE html>
<html>
<head>
	<title></title>
	<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
<?php while ($i < count($cartProducts)): ?>
    <div class="product"> 
        <div class="image">
            <img src="<?= "uploads/". $cartProducts[$i]["Image"] ?>">
        </div>
        <div class="productdetails">
            <div class="productTitle"><?= $cartProducts[$i]["Title"] ?></div>
            <div class="productDescription"><?= $cartProducts[$i]["Description"] ?></div>
            <div class="productPrice"><?= $cartProducts[$i]["Price"] ?></div>
            <a href="cart.php?action=remove&amp;id=<?= $cartProducts[$i]["Id"] ?>" class="remove"><?= translate("Remove") ?></a>
        </div>
    </div>
<?php $i++; endwhile; ?>
<div class="linkToIndex">
    <a href="index.php"><?= translate("Go to index") ?></a>
</div>
<form action="<?= htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
	<input type="text" name="name" placeholder="Name" required="required">
    <span><?= $nameErr ?></span><br />
	<input type="text" name="contactDetails" placeholder="Contact details" required="required">
    <span><?= $contactDetailsErr ?></span><br />
	<input type="text" name="comments" placeholder="Comments"><br />
	<input type="submit" name="checkout" value="Checkout">
</form>
</body>
</html>