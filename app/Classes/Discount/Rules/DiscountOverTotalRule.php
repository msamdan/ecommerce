<?php
namespace App\Classes\Discount\Rules;

use App\Classes\Discount\Stats;

class DiscountOverTotalRule implements Rule
{
    use Stats;

    public array $items;
    public float $subtotal;

    public array $selectedItems = [];
    public array $itemsInScope = [];
    public array $targetItems = [];

    public int $discountRatio = 10;
    public int $condition = 1000;

    public float $discountAmount = 0;

    public function __construct(array $items, float $subtotal)
    {
        $this->items = $items;
        $this->subtotal = $subtotal;
    }

    /**
     * İndirime sebep olan ürünleri seç
     * @return array
     */
    public function select(): array
    {

        $this->selectedItems = $this->items;

        return $this->selectedItems;
    }

    public function checkCondition(): bool
    {
        return ( $this->getTotalAmont() > $this->condition );
    }

    public function scope(): array
    {
        $this->itemsInScope = $this->items;

        return $this->itemsInScope;
    }

    public function target(): array
    {
        $this->targetItems = $this->itemsInScope;

        return $this->targetItems;
    }

    public function discount(): array
    {
        foreach ($this->targetItems as $item) {
            $this->discountAmount += $item['total'] * $this->discountRatio / 100;
        }

        return [
            "discountReason" => '10_PERCENT_OVER_1000',
            "discountAmount" => $this->discountAmount,
            "subtotal" => $this->subtotal - $this->discountAmount
        ];
    }
}
