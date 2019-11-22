<?php

class Product
{
    private $price;
    private $type;
    private $actionPrice = 0;

    public function __construct($price = 100, $type)
    {
        $this->price = $price;
        $this->type = $type;

    }

    public function getPrice()
    {
        return $this->price;
    }

    public function getType()
    {
        return $this->type;
    }

    public function getCount()
    {
        return $this->count;
    }

    public function setActionPrice($actionPrice) //скорее всего убрать
    {
        $this->actionPrice = $actionPrice;
    }

    public function setPrice($price)
    {
        $this->price = $price;
        $this->actionPrice = 1;
    }

    public function getActionPrice()
    {
        return $this->actionPrice;
    }

}

