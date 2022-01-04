<?php
namespace App\Classes\Discount\Rules;

use App\Classes\Discount\Discount;
use App\Classes\Discount\Stats;

class DiscountOverBuyFiveGetOneRule extends Discount implements \SplObserver, Rule
{
    use Stats;
    public \SplSubject $basket;

    public array $selectedItems = [];
    public array $itemsInScope = [];
    public array $targetItems = [];

    public int $categoryCondition = 2;
    public int $condition = 6;

    public int $discountRatio = 100;

    public float $discountAmount = 0;

    public string $discountReason = 'DiscountOverBuyFiveGetOneRule';
    /**
     * İndirime sebep olan ürünleri seç
     * @return array
     */
    public function select(): array
    {
        foreach ($this->basket->items as  $item)
            if( $item['categoryId'] === $this->categoryCondition ) $this->selectedItems[] = $item;

        return $this->selectedItems;
    }

    public function checkCondition(): bool
    {
        foreach ($this->selectedItems as $key => $item )
            if( $item['quantity'] < $this->condition ) unset($this->selectedItems[$key]);


        return ( count($this->selectedItems) > 0 );
    }

    public function scope(): array
    {
        $this->itemsInScope = $this->selectedItems;

        return $this->itemsInScope;
    }

    public function target(): array
    {
        $this->targetItems[] = $this->itemsInScope[0];

        return $this->targetItems;
    }

    public function discount(): float
    {
        foreach ($this->targetItems as $item) {
            $this->discountAmount += $item['unitPrice'] * $this->discountRatio / 100;
        }

        return $this->discountAmount;
    }
}
