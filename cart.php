<?php
require_once("common.php");
$result = $conn->query($sql);
if(!empty($_GET["action"]) && $_GET["action"] == "remove") {  
    foreach ($_SESSION["cart"] as $keys => $values) {
        if ($values["Id"] == $_GET["id"]) {
            unset($_SESSION["cart"][$keys]);
            $_SESSION["cart"] = array_merge($_SESSION["cart"]);
        }
    }
}
$cartProducts = array();
if(!empty($_SESSION["cart"]) && $result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        foreach (array_column($_SESSION["cart"], "Id") as $keys => $values) {
            if($values == $row["Id"]) {
                $cartProducts[] = array(
                    "id" => $row["Id"],
                    "title" => $row["Title"],
                    "description" => $row["Description"],
                    "price" => $row["Price"],
                    "image" => $row["Image"]
                );
            }
        }
    }
}
$order = "";
$totalsum = 0;
$i = 0;
while ($i < count($cartProducts)) {
    $order .= $cartProducts[$i]["title"] . " " . $cartProducts[$i]["price"] . "\r\n";
    $totalsum += $cartProducts[$i]["price"];
    $i++;
}
$nameErr = $contactDetailsErr = "";
if(isset($_POST["checkout"])) {
	$to = "ancarusu2000@gmail.com";
	$contactDetails = test_user_input($_POST["contactDetails"]);
    $name = test_user_input($_POST["name"]);
    if (!preg_match("/^[a-zA-Z ]*$/",$name)) {
        $nameErr = "Only letters and white space allowed"; 
    } 
    if (!filter_var($contactDetails, FILTER_VALIDATE_EMAIL)) {
        $contactDetailsErr = "Email is not valid";
    }
    $comments = test_user_input($_POST["comments"]);
	$subject = "Form submision";
	$headers = "From: " . $contactDetails;
    $message = $name . " wrote: " . $comments . "\r\n". "Order: " . "\r\n" . $order ."\r\n" . 
    "Total: " . $totalsum;
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
<?php foreach (array_column($_SESSION["cart"], "Id") as $keys => $values): $i = 0; ?> 
    <?php while ($i < count($_SESSION["cart"])): ?>
        <?php if($values == $cartProducts[$i]["id"]): ?>
            <div class="product"> 
                <div class="image">
                    <img src="<?= $cartProducts[$i]["image"] ?>">
                </div>
                <div class="productdetails">
                    <div class="productTitle"><?= $cartProducts[$i]["title"] ?></div>
                    <div class="productDescription"><?= $cartProducts[$i]["description"] ?></div>
                    <div class="productPrice"><?= $cartProducts[$i]["price"] ?></div>
                    <a href="cart.php?action=remove&amp;id=<?= $cartProducts[$i]["id"] ?>" class="remove"><?= translate("Remove") ?></a>
                </div>
            </div>
        <?php endif; ?>
    <?php $i++; endwhile; ?>
<?php endforeach;?> 
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