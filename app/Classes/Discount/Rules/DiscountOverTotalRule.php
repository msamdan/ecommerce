<?php
namespace App\Classes\Discount\Rules;

use App\Classes\Discount\Discount;
use App\Classes\Discount\Stats;

class DiscountOverTotalRule extends Discount implements \SplObserver, Rule
{
    use Stats;

    public \SplSubject $basket;

    public array $selectedItems = [];
    public array $itemsInScope = [];
    public array $targetItems = [];

    public int $condition = 1000;

    public int $discountRatio = 10;

    public float $discountAmount = 0;

    public string $discountReason = 'DiscountOverTotalRule';

    public function select(): array
    {

        $this->selectedItems = $this->basket->items;

        return $this->selectedItems;
    }

    public function checkCondition(): bool
    {
        return ( $this->getTotalAmont() > $this->condition );
    }

    public function scope(): array
    {
        $this->itemsInScope = $this->basket->items;

        return $this->itemsInScope;
    }

    public function target(): array
    {
        $this->targetItems = $this->itemsInScope;

        return $this->targetItems;
    }

    public function discount(): float
    {
        foreach ($this->targetItems as $item) {
            $this->discountAmount += $item['total'] * $this->discountRatio / 100;
        }

        return $this->discountAmount;
    }
}
