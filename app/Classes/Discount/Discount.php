<?php

namespace App\Classes\Discount;

use App\Classes\Discount\Rules\DiscountOverBuyFiveGetOneRule;
use App\Classes\Discount\Rules\DiscountOverTotalRule;
use App\Classes\Discount\Rules\DiscountOverTwoOrMoreRule;

class Discount
{
    use Stats;

    /**
     * Discount list
     * @var array
     */
    public array $discounts = [];

    /**
     * Total discount amount
     * @var float|int
     */
    public float $totalDiscount = 0;

    /**
     * Items in basket
     * @var array
     */
    public array $items = [];

    /**
     * Subtotal after rules exec.
     * @var float|int
     */
    public float $subtotal = 0;

    /**
     * Registered rules
     * @var array|string[]
     */
    private array $rules = [
        DiscountOverTotalRule::class,
        DiscountOverBuyFiveGetOneRule::class,
        DiscountOverTwoOrMoreRule::class,
    ];

    /**
     * Calculate discount for given items
     * @param $items
     * @return array
     */
    public function getDiscount($items): array
    {
        $this->items = $items;
        $this->subtotal = $this->getTotalAmont();
        $this->discounts['discounts'] = [];

        foreach ( $this->rules as $rule){
            $rule = new  $rule($this->items, $this->subtotal);

            if( count( $rule->select() ) == 0 ) continue;

            if( !$rule->checkCondition() ) continue;

            if( count( $rule->scope() ) == 0 ) continue;

            if( count( $rule->target() ) == 0 ) continue;

            $discount =  $rule->discount();

            $this->discounts['discounts'][] = $discount;
            $this->totalDiscount += $discount['discountAmount'];
            $this->subtotal = $discount['subtotal'];
        }

        $this->discounts['totalDiscount'] = $this->totalDiscount;
        $this->discounts['discountedTotal'] = $this->subtotal;

        return $this->discounts;
    }
}
