<?php

namespace App\Classes;

use App\Models\ProductModel;

class Basket implements \SplSubject
{
    private $_observers;

    /**
     * Products
     * @var array
     */
    public array $items = [];

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

    public array $discounts = [
        'discounts' => [],
        'totalDiscount' => 0,
        'discountedTotal' => 0
    ];

    public function __construct()
    {
        $this->_observers = new \SplObjectStorage();
    }

    public function attach(\SplObserver $observer) {
        $this->_observers->attach($observer);
    }

    public function detach(\SplObserver $observer) {
        $this->_observers->detach($observer);
    }

    public function notify() {
        foreach ($this->_observers as $observer) {
            $observer->update($this);
        }
    }

    /**
     * Check product stock
     * @param ProductModel $product
     * @param int $quantity
     * @return bool
     */
    public function checkStock(ProductModel $product, int $quantity, bool $increase): bool
    {
        $currentQuantity = ( $this->currentItemKey === false ? 0 : $this->items[$this->currentItemKey]['quantity'] );

        $orderQuantity =  $increase ? $currentQuantity + $quantity : $quantity;

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

        $this->total = $this->getTotalAmont();
        $this->notify();

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
        $this->total = $this->getTotalAmont();
        $this->notify();

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
        $this->total = $this->getTotalAmont();
        $this->notify();

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
     * Total amonth
     * @return float
     */
    public function getTotalAmont(): float
    {
        $total = 0;
        foreach ($this->items as $item) $total += $item['total'];

        return $total;
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
