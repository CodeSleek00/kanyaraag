<?php
session_start();
if (isset($_GET['index'])) {
    $index = intval($_GET['index']);
    unset($_SESSION['cart'][$index]);
    $_SESSION['cart'] = array_values($_SESSION['cart']); // reindex
}
header("Location: cart.php");
exit();
