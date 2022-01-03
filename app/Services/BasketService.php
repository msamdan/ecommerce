<?php

namespace App\Services;

use App\Classes\Basket;
use App\Helpers\ServiceResponse;
use App\Models\BasketModel;
use App\Models\ProductModel;
use Illuminate\Support\Facades\Session;

class BasketService extends BaseService
{
    private Basket $basket;

    private BasketModel $model;

    public function __construct()
    {
        $this->model = BasketModel::where('customerId', $this->getCustomerId() )->firstOr( function() {
            return BasketModel::create([
                'customerId' => $this->getCustomerId(),
                'items' => [],
                'total' => 0,
                'discount' => [],
            ]);
        });

        // TODO: We have to check "unitPrices" updated or "stock" changed each time with $this->addItem method...
        $this->basket = new Basket();
        $this->basket->items = $this->model->items;
        $this->basket->total = $this->model->total;
        $this->basket->discount = $this->model->discount;
    }

    /**
     * @return ServiceResponse
     */
    public function listItems(): ServiceResponse
    {
        return $this->setResponse(200, 'Success', $this->basket->items);
    }

    /**
     * @param array $params
     * @return ServiceResponse
     */
    public function addItem(array $params ): ServiceResponse
    {
        try {
            $quantity = $params['quantity'];
            $product = ProductModel::find($params['productId']);

            if( empty( $product) ) throw new \Exception('Product not found!', 400);

            list($success, $message) = $this->basket->addItem($product, $quantity);
            if( $success !== true ) throw new \Exception($message, 400);

            $this->storeState();

            return $this->setResponse(200, 'Success', $this->basket->getState() );

        } catch ( \Exception $e ){
            return $this->setResponse(400, 'Error',  $e->getMessage());
        }
    }

    /**
     * Return the Basket
     * @return ServiceResponse
     */
    public function get(): ServiceResponse
    {
         return $this->setResponse(200, 'Success', $this->basket->getState() );
    }

    /**
     * Return the Discount
     * @return ServiceResponse
     */
    public function discount(): ServiceResponse
    {

        return $this->setResponse(200, 'Success', $this->model->discount );
    }

    /**
     * @param int $id
     * @param array $params
     * @return ServiceResponse
     */
    public function updateItem(int $productId, array $params): ServiceResponse
    {
        try {

            $quantity = $params['quantity'];
            $product = ProductModel::find($productId);

            if( empty( $product) ) throw new \Exception('Product not found!', 400);

            list($success, $message) = $this->basket->updateItem($product, $quantity);
            if( $success !== true ) throw new \Exception($message, 400);

            $this->storeState();

            return $this->setResponse(200, 'Success', $this->basket->getState() );

        } catch ( \Exception $e ){
            return $this->setResponse(400, 'Error',  $e->getMessage());
        }
    }

    /**
     * @param int $id
     * @return ServiceResponse
     */
    public function removeItem(int $productId): ServiceResponse
    {
        try {
            $product = ProductModel::find($productId);

            if( empty( $product) ) throw new \Exception('Product not found!', 400);

            list($success, $message) = $this->basket->removeItem($product);
            if( $success !== true ) throw new \Exception($message, 400);

            $this->storeState();

            return $this->setResponse(200, 'Success', $this->basket->getState() );

        } catch ( \Exception $e ){
            return $this->setResponse(400, 'Error',  $e->getMessage());
        }
    }

    /**
     * Store state to db or session or somewhere
     */
    private function storeState()
    {
        $this->model->items = $this->basket->items;
        $this->model->total = $this->basket->total;
        $this->model->discount = $this->basket->discounts;
        $this->model->save();

        return true;
    }
}
