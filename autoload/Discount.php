<?php

abstract class Discount implements Calculator //1.2.3
{
    protected $products;
    protected $discountPercent;
    protected $priority;

    public function __construct($products, $discountPercent, $priority)
    {
        $this->products = $products;
        $this->discountPercent = $discountPercent;
        $this->priority = $priority;
    }

    public function getPriority()
    {
        return $this->priority;
    }
}

class DiscountSimple extends Discount //5.6.7
{
    protected $countDiscountProducts;
    protected $countProducts;


    public function __construct($products, $discountPercent, $countDiscountProducts, $priority)
    {
        parent::__construct($products, $discountPercent, $priority);
        $this->countDiscountProducts = $countDiscountProducts;
    }

    public function calculateDiscount(array $cart)
    {
        $productsInAction = array();
        foreach ($cart as $key => $cartProduct) {
            if ($cartProduct->getActionPrice() == 0) {
                $productsInAction[] = $cartProduct->getType();
            }
        }
        foreach ($productsInAction as $key => $product) {
            if (in_array($product, $this->products)) {
                unset($productsInAction[$key]);
            }
        }
        $count = count($productsInAction);
        foreach ($this->countDiscountProducts as $key => $value) {
            if ($count >= $value) {
                foreach ($cart as $value) {
                    $price = $value->getPrice() - ($value->getPrice() * $this->discountPercent[$key] / 100);
                    $value->setPrice($price);
                }
                break;
            }

        }


    }

}

class DiscountVariable extends Discount //4
{
    protected $baseProduct;

    public function __construct($products, $discountPercent, $priority, $baseProduct)
    {
        parent::__construct($products, $discountPercent, $priority);
        $this->baseProduct = $baseProduct;

    }

    public function calculateDiscount(array $cart)
    {
        $typeProductsInCart = array();
        foreach ($cart as $key => $cartProduct) {
            $typeProductsInCart[] = $cartProduct->getType();
        }
        $typeProductsInCart = array_unique($typeProductsInCart);

        if (array_intersect($typeProductsInCart, $this->products) && in_array($this->baseProduct, $typeProductsInCart)) {
            foreach ($cart as $key => $cartProduct) {
                if ($cartProduct->getType() == $this->baseProduct && $cartProduct->getActionPrice() != 1) {
                    $price = $cartProduct->getPrice() - ($cartProduct->getPrice() * $this->discountPercent / 100);
                    $cartProduct->setPrice($price);
                    break;
                }
            }
        }

    }

}

class DiscountSum extends Discount
{
    public function calculateDiscount(array $cart)
    {
        $actionProducts = array();
        $cnt = 0;
        $cartProducts = $cart;
        $prices = array();
        $countActionProducts = array();
        $discountApplication = 0;
        foreach ($this->products as $product) {

            foreach ($cartProducts as $key => $cartProduct) {
                if ($product == $cartProduct->getType()) {
                    $actionProducts[$cnt][] = array('type' => $cartProduct->getType(), 'price' => $cartProduct->getPrice(), 'position' => $key);
                }
            }
            $cnt++;
        }

        if (count($this->products == count($actionProducts))) {
            for ($i = 0; $i < count($this->products); $i++) {
                $prices[] = $actionProducts[$i][0]["price"];
                $countActionProducts[] = count($actionProducts[$i]);
            }
            $countActionProducts = array_unique($countActionProducts);
            if (count($countActionProducts) >= 1) {//усли меньше скидка не применяется
                $discountApplication = min($countActionProducts);
                if ($discountApplication > 0) { //каждый продуктт уменьшаем на цену скидки
                    for ($i = 0; $i < $discountApplication; $i++) {
                        foreach ($actionProducts[$i] as $key => $value) {
                            if ($key <= $discountApplication - 1) {
                                $number = $value["position"];
                                $price = $value["price"] - ($value["price"] * $this->discountPercent / 100);
                                $cart[$number]->setPrice($price);
                            }

                        }
                    }
                } else {
                    for ($i = 0; $i < $discountApplication; $i++) {
                        foreach ($actionProducts[$i] as $key => $value) {
                            $number = $value["position"];
                            $price = $value["price"] - ($value["price"] * $this->discountPercent / 100);
                            $cart[$number]->setPrice($price);
                        }

                    }

                }
            }
        }
    }

}