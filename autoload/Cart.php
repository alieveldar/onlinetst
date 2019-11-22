<?php


class Cart
{
    private $products;

    public function addProducts(array $products)
    {
        $this->products = $products;
    }

    public function getProducts()
    {
        return $this->products;
    }
}