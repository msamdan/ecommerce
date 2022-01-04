<?php

namespace App\Classes\Discount\Rules;

interface Rule
{
    /**
     * Identify items that cause discounts
     * @return array
     */
    public function select(): array;

    /**
     * Make sure the conditions are satisfied
     * @return array
     */
    public function checkCondition(): bool;

    /**
     * Items eligible for a discount
     * @return array
     */
    public function scope(): array;

    /**
     * Find target products in scope (max, min, all, etc..)
     * @return array
     */
    public function target(): array;

    /**
     * Apply discounts to targets
     * @return array
     */
    public function discount(): float;
}
