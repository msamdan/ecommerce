<?php

namespace App\Services;

use App\Classes\Basket;
use App\Helpers\ServiceResponse;
use App\Models\OrderModel;
use App\Models\ProductModel;

class OrderService extends BaseService
{
    /**
     * @return ServiceResponse
     */
    public function list(): ServiceResponse
    {
        $orders = OrderModel::all();

        return $this->setResponse(200, 'Success', $orders );
    }

    /**
     * Check order items
     * @param array $items
     * @return array
     */
    public function checkItems(array $items)
    {
        $basket = new Basket();
        foreach ($items as $item){
            $quantity = $item['quantity'];

            $product = ProductModel::find($item['productId']);

            if( empty( $product) ) return [false, 'Product not found!'];

            list($success, $message) = $basket->addItem($product, $quantity);

            if( $success !== true ) return [false, $message];
        }

        return [true, $basket];
    }

    public function updateProductStock($items)
    {
        foreach ($items as $item){
            $product = ProductModel::find($item['productId']);
            $product->stock -= $item['quantity'];
            $product->save();
        }
    }

    /**
     * @param array $params
     * @return ServiceResponse
     */
    public function create(array $data): ServiceResponse
    {
        if( empty($data['items']) ) return $this->setResponse(400, 'Add product for order', null);

        list($success, $basket ) = $this->checkItems($data['items']);

        if( !$success ) return $this->setResponse(400, 'Error', $basket);

        $order = new OrderModel($data);
        $order->customerId = $this->getCustomerId();
        $order->discount = $basket->discounts;
        $order->save();

        $this->updateProductStock($data['items']);

        return $this->setResponse(200, 'Success', $order->toArray() );
    }

    /**
     * @param int $id
     * @return ServiceResponse
     */
    public function get(int $id): ServiceResponse
    {
        $order = OrderModel::find($id);

        if( empty( $order ) ) return $this->setResponse(404, 'Error', null);

        return $this->setResponse(200, 'Success', $order->toArray() );
    }

    /**
     * @param int $id
     * @param array $params
     * @return ServiceResponse
     */
    public function update(int $id, array $data): ServiceResponse
    {
        // TODO: update order
        return $this->setResponse(400, 'Bad request', null );
    }

    /**
     * @param int $id
     * @return ServiceResponse
     */
    public function delete(int $id): ServiceResponse
    {
        $order = OrderModel::destroy($id);

        if( empty( $order ) ) return $this->setResponse(404, 'Error', null);


        return $this->setResponse(200, 'Success', null );
    }
}
