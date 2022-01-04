<?php

namespace App\Classes\Discount;


class Discount
{
    use Stats;

    public function update(\SplSubject $subject): bool
    {
        $this->basket = $subject;
        if( count( $this->select() ) == 0 ) return false;
        if( !$this->checkCondition() ) return false;
        if( count( $this->scope() ) == 0 ) return false;
        if( count( $this->target() ) == 0 ) return false;

        $this->discount();

        $subtotal = $this->basket->total - $this->basket->discounts['discountedTotal'] - $this->discountAmount;
        $this->basket->discounts['discounts'][] = [
            "discountReason" => $this->discountReason,
            "discountAmount" => $this->discountAmount,
            "subtotal" => $subtotal
        ];
        $this->basket->discounts['totalDiscount'] += $this->discountAmount;
        $this->basket->discounts['discountedTotal'] = $this->basket->total - $this->basket->discounts['totalDiscount'];
        return true;
    }
}
