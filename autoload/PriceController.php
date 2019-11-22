<?php


final class PriceController
{

    public static function addDiscounts(array $discounts, array $cart)
    {
        self::sortDiscounts($discounts);
        $result = self::getCalculating($discounts, $cart);
        return "Общая соимость товаров ". $result;

    }

    private function sortDiscounts(array $discounts)
    {
        usort($discounts, function ($discount1, $discount2) {
            if ($discount1->getPriority() == $discount2->getPriority()) {
                return 0;
            }
            return ($discount1->getPriority() > $discount2->getPriority()) ? -1 : 1;
        });

    }

    private function getCalculating(array $discounts, $cart)
    {
        $sum=0;
        $cartProducts = $cart;
        foreach ($discounts as $discount) {
            $discount->calculateDiscount($cartProducts);
        }

        foreach ($cart as $product){
            $sum += summ + ($product->getPrice());
        }
        return $sum;
    }


}