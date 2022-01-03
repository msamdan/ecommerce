<?php
namespace App\Classes\Discount\Rules;

use App\Classes\Discount\Stats;

class DiscountOverBuyFiveGetOneRule implements Rule
{
    use Stats;

    public array $items;
    public float $subtotal;

    public array $selectedItems = [];
    public array $itemsInScope = [];
    public array $targetItems = [];

    public int $discountRatio = 100;
    public int $categoryCondition = 2;
    public int $condition = 6;

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
        foreach ($this->items as  $item)
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

    public function discount(): array
    {
        foreach ($this->targetItems as $item) {
            $this->discountAmount += $item['unitPrice'] * $this->discountRatio / 100;
        }

        return [
            "discountReason" => 'BUY_5_GET_1',
            "discountAmount" => $this->discountAmount,
            "subtotal" => $this->subtotal - $this->discountAmount
        ];
    }
}
