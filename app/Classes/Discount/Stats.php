<?php

namespace App\Classes\Discount;

trait Stats
{
    public function getCategoryTotalAmonth(int $categoryId): float
    {
        $total = 0;
        foreach ($this->basket->items as $item) {
            if( $item['categoryId'] === $categoryId) $total += $item['total'];
        }

        return $total;
    }

    public function getCategoryTotalQuantity(int $categoryId): int
    {
        $total = 0;
        foreach ($this->basket->items as $item) {
            if( $item['categoryId'] === $categoryId) $total += $item['quantity'];
        }

        return $total;
    }

    public function getCategoryUniqueProductCount(int $categoryId): int
    {
        $total = 0;
        foreach ($this->basket->items as $item) {
            if( $item['categoryId'] === $categoryId) $total += 1;
        }

        return $total;
    }

    public function getTotalAmont(): float
    {
        $total = 0;
        foreach ($this->basket->items as $item) $total += $item['total'];

        return $total;
    }

    public function getTotalQuantity(): int
    {
        $total = 0;
        foreach ($this->basket->items as $item) $total += $item['quantity'];

        return $total;
    }

    public function getTotalUniqueProductCount(): int
    {
        $total = 0;
        foreach ($this->basket->items as $item) $total += 1;

        return $total;
    }
}
