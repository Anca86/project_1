<?php
require_once('common.php');
$sql = "SELECT * FROM productsnew";
$result = $conn->query($sql);

// if(isset($_SESSION["cart"])) {
//     $stringIds = implode(", ", $_SESSION["cart"]);
//     if(count($_SESSION["cart"]) == 0) {
//        $stringIds = count($_SESSION["cart"]);
//     }
//     $sql = "SELECT * FROM productsnew WHERE Id NOT IN ($stringIds)";
// } 
// $result = array();

if(isset($_SESSION["cart"])) {
    $stringIds = implode(", ", $_SESSION["cart"]);
    $stmt =$conn->prepare("SELECT * FROM productsnew WHERE Id NOT IN ($stringIds)");
    $stmt->bind_param("s", $_SESSION["cart"]);
    $stmt->execute();
    $result = mysqli_stmt_get_result($stmt);
    print_r($result);
} 

if(isset($_POST["add_to_cart"])) {
    if(!isset($_SESSION["cart"])) {
        $_SESSION["cart"] = array();
    } 
    if(!in_array($_POST["hidden_id"], $_SESSION["cart"])) {
        array_push($_SESSION["cart"], $_POST["hidden_id"]);
    }
}



// if(isset($_SESSION["cart"])) {
//     global $conn;
//     $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
//     $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
//     $in = "";

//     foreach ($_SESSION["cart"] as $i => $item) {
//         $key = ":id".$i;
//         $in .= "$key, ";
//         $in_params[$key] = $item; 
//     }

//     $in = rtrim($in,",");
//     //$stmt = $conn->prepare("SELECT * FROM productsnew WHERE Id NOT IN (" . rtrim($in,",") . ")");
//     $arr = explode(",", $in);
//     array_pop($arr);
//     foreach (array_combine($arr, $_SESSION["cart"]) as $id => $value ) { 
//         $stmt = $conn->prepare("SELECT * FROM productsnew WHERE Id NOT IN ($id)");    
//         $stmt->bindParam($id, $value);
//         $stmt->execute();
//     }
// }

// if(isset($_SESSION["cart"])) {
// $stmt = mysqli_stmt_init($conn);
// function myFunc() {
//     global $stmt;
//     foreach ($_SESSION["cart"] as $value) { 
//         mysqli_stmt_prepare($stmt, "SELECT * FROM productsnew WHERE Id NOT IN (?)");
//         mysqli_stmt_bind_param($stmt, "i", $value);
//     }
// }
// call_user_func_array("myFunc", $_SESSION["cart"]);
// mysqli_stmt_execute($stmt);
// $result = mysqli_stmt_get_result($stmt);
// }

$conn->close();
?>
<!DOCTYPE html>
<html>
<head>
    <title><?= translate("Products") ?></title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
<?php if($result->num_rows > 0): ?>
    <?php while($row = $result->fetch_assoc()): ?>
        <div class="product">
            <form method="post" action="index.php">
                <div class="image">
                    <img src="<?= $row["Image"]; ?>">
                </div>
                <div class="productdetails">
                    <div class="productTitle"><?= $row["Title"] ?></div>
                    <div class="productDescription"><?= $row["Description"] ?></div>
                    <div class="productPrice"><?= $row["Price"] ?></div>
                    <input type="hidden" name="hidden_id" value="<?= $row["Id"] ?>">
                    <input type="submit" name="add_to_cart" value="<?= translate("Add") ?>">
                </div>
            </form>
        </div>               
    <?php endwhile; ?>
<?php endif; ?>
<div class="linkToCart">
    <a href="cart.php"><?= translate("Go to cart") ?></a>
</div>
</body>
</html>