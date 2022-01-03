<?php

namespace App\Http\Controllers;

use App\Helpers\ServiceResponse;
use App\Services\BasketService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BasketController extends BaseController
{
    /**
     * Display a listing of the items.
     * @param BasketService $service
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function listItems(BasketService $service): JsonResponse
    {
        $response = $service->listItems();

        return response()->json($response, $response->status);
    }

    /**
     * Add item to Basket.
     * @param BasketService $service
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function addItem(BasketService $service, Request $request): JsonResponse
    {
        $response = $service->addItem( $request->all() );

        return response()->json($response, $response->status);
    }

    /**
     * Return the Basket.
     * @param BasketService $service
     * @return \Illuminate\Http\JsonResponse
     */
    public function get(BasketService $service): JsonResponse
    {
        $response = $service->get();

        return response()->json($response, $response->status);
    }

    public function discount(BasketService $service)
    {
        $response = $service->discount();

        return response()->json($response, $response->status);
    }

    /**
     * @param BasketService $service
     * @param Request $request
     * @return JsonResponse
     */
    public function updateItem(int $id, BasketService $service, Request $request)
    {
        $response = $service->updateItem( $id, $request->all());

        return response()->json($response, $response->status);
    }

    /**
     * Remove the specified item from Basket.
     * @param int $id
     * @param BasketService $service
     * @return JsonResponse
    */
    public function removeItem(int $id,  BasketService $service)
    {
        $response = $service->removeItem($id);

        return response()->json($response, $response->status);
    }
}
