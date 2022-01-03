<?php

namespace App\Classes;

use App\Classes\Discount\Discount;
use App\Models\ProductModel;

class Basket extends Discount
{
    /**
     * Products
     * @var array
     */
    public array $items = [];

    public array $errors = [];

    /**
     * Basket total amount
     * @var float|int
     */
    public float $total = 0;

    /**
     * Item that current processing on
     * @var bool|int
     */
    public bool|int $currentItemKey = false;

    public array $discounts = [];

    /**
     * Check product stock
     * @param ProductModel $product
     * @param int $quantity
     * @return bool
     */
    public function checkStock(ProductModel $product, int $quantity, bool $increase): bool
    {
        $currentQuantity = ( $this->currentItemKey === false ? 0 : $this->items[$this->currentItemKey]['quantity'] );

        $orderQuantity =  $increase ? $quantity : $currentQuantity + $quantity;

        return ( $product->stock > $orderQuantity );
    }

    /**
     * Remove item from basket and update discount and other...
     * @param $product
     * @return array|string[]
     */
    public function removeItem($product)
    {
        if( !$this->checkItemExist($product->id) ) return ['false', 'Product not found in basket'];

        unset($this->items[$this->currentItemKey]);

        $this->discounts = $this->getDiscount($this->items);
        $this->total = $this->getTotalAmont();

        return [true, 'Item removed'];
    }

    /**
     * Update item and dicount etc.
     * @param $product
     * @param $quantity
     * @return array|string[]
     */
    public function updateItem($product, $quantity)
    {
        if( !$this->checkItemExist($product->id) ) return ['false', 'Product not found in basket'];

        if( $product->stock < $quantity ) return [false, 'Not enough items in stock!'];

        $this->updateItemQuantity($quantity, false);
        $this->updateItemTotalPrice($product->price);
        $this->discounts = $this->getDiscount($this->items);
        $this->total = $this->getTotalAmont();

        return [true, 'Item added'];
    }

    /**
     * Add item to basket and update dicount
     * @param $product
     * @param $quantity
     * @return array
     */
    public function addItem($product, $quantity): array
    {
        $this->checkItemExist($product->id);

        if( !$this->checkStock($product, $quantity, true) ) return [false, 'Not enough items in stock!'];

        if( $this->currentItemKey === false ){
            $this->items[] = array(
                'quantity' => $quantity,
                'categoryId' => $product->category,
                'productId' => $product->id,
                'unitPrice' => $product->price,
                'total' => 0,
                'discount' => [],
            );
        } else {
            $this->updateItemQuantity($quantity, true);
        }

        $this->updateItemTotalPrice($product->price);
        $this->discounts = $this->getDiscount($this->items);
        $this->total = $this->getTotalAmont();

        return [true, 'Item added'];
    }

    /**
     * Update items total amount
     * @param float $price
     * @return bool
     */
    public function updateItemTotalPrice(float $price): bool
    {
        $this->items[$this->currentItemKey]['total'] = $price * $this->items[$this->currentItemKey]['quantity'];

        return true;
    }

    /**
     * Update items quantity
     * @param int $quantity
     * @param bool $increase
     * @return bool
     */
    public function updateItemQuantity(int $quantity, bool $increase): bool
    {
        $this->items[$this->currentItemKey]['quantity'] = $increase ? $this->items[$this->currentItemKey]['quantity'] + $quantity : $quantity;

        return true;
    }

    /**
     * Check item exist in basket
     * @param $productId
     * @return bool|int
     */
    public function checkItemExist($productId): bool|int
    {
        if( $this->currentItemKey === false || $this->items[$this->currentItemKey]['productId'] != $productId ){
            $this->currentItemKey = array_search($productId, array_column($this->items, 'productId'));
        }

        return $this->currentItemKey !== false;
    }

    /**
     * Get basket state
     * @return array
     */
    public function getState(): array
    {
        return ['items' => $this->items, 'total' => $this->total, 'discounts' => $this->discounts];
    }
}
