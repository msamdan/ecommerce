<?php
namespace App\Classes\Discount\Rules;

use App\Classes\Discount\Discount;
use App\Classes\Discount\Stats;
use SplSubject;

class DiscountOverTwoOrMoreRule extends Discount implements \SplObserver, Rule
{
    use Stats;

    public SplSubject $basket;

    /**
     * items that cause discounts
     * @var array
     */
    public array $selectedItems = [];

    /**
     * Items that fall under the potential discount
     * @var array
     */
    public array $itemsInScope = [];

    /**
     * Target products in scope (max, min, all, etc..)
     * @var array
     */
    public array $targetItems = [];

    /**
     * Rule spesific conditions
     * @var int
     */
    public int $categoryCondition = 2;

    /**
     * Rule specific discount inf.
     * @var int
     */
    public int $discountRatio = 20;
    public int $condition = 1;

    public string $discountReason = 'DiscountOverTwoOrMoreRule';
    /**
     * Discount amount
     * @var float|int
     */
    public float $discountAmount = 0;

    public function select(): array
    {
        foreach ($this->basket->items as  $item)
            if( $item['categoryId'] === $this->categoryCondition ) $this->selectedItems[] = $item;

        return $this->selectedItems;
    }

    public function checkCondition(): bool
    {
        $total = $this->getCategoryTotalQuantity($this->categoryCondition);

        return ( $total > $this->condition );
    }

    public function scope(): array
    {
        $this->itemsInScope = $this->basket->items;

        return $this->itemsInScope;
    }

    public function target(): array
    {
        $unitPrices = array_column($this->itemsInScope, 'unitPrice');
        $key = array_search(min($unitPrices), $unitPrices, true);
        $this->targetItems[] = $this->basket->items[$key];

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
