<?php
namespace App\Classes\Discount\Rules;

use App\Classes\Discount\Stats;

class DiscountOverTwoOrMoreRule implements Rule
{
    use Stats;

    /**
     * items data
     * @var array
     */
    public array $items;
    public float $subtotal;

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
    public int $categoryCondition = 1;


    /**
     * Rule specific discount inf.
     * @var int
     */
    public int $discountRatio = 20;
    public int $condition = 1;

    /**
     * Discount amount
     * @var float|int
     */
    public float $discountAmount = 0;

    public function __construct(array $items, float $subtotal)
    {
        $this->items = $items;
        $this->subtotal = $subtotal;
    }

    public function select(): array
    {
        foreach ($this->items as  $item)
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
        $this->itemsInScope = $this->items;

        return $this->itemsInScope;
    }

    public function target(): array
    {
        $unitPrices = array_column($this->itemsInScope, 'unitPrice');
        $key = array_search(min($unitPrices), $unitPrices, true);
        $this->targetItems[] = $this->items[$key];

        return $this->targetItems;
    }

    public function discount(): array
    {
        foreach ($this->targetItems as $item) {
            $this->discountAmount += $item['unitPrice'] * $this->discountRatio / 100;
        }

        return [
            "discountReason" => 'BUY_2_OR_MORE_GET_20_RATIO',
            "discountAmount" => $this->discountAmount,
            "subtotal" => $this->subtotal - $this->discountAmount
        ];
    }
}
