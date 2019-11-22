<?php
require_once 'autoload/init.php';
$cart = New Cart();
$products = array(New Product(100, "A"), New Product(200,"B"),
    New Product(200,"B"),New Product(100, "A"),New Product(100,"A"),New Product(200,"C"), New Product(300,"K"),New Product(200,"C"),New Product(300,"K"),New Product(300,"K"),New Product(300,"K"));
$cart->addProducts($products);
$discounts = array(New DiscountSum(array("A","B"),10,1),New DiscountVariable(array("K", "L", "M"),5,2,"A"), New DiscountSimple(array("A","C"),array(20,10,5),array(5,4,3),3));
echo PriceController::addDiscounts($discounts, $cart->getProducts());
