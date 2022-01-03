<?php

namespace App\Http\Controllers;

use App\Services\OrderService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OrderController extends BaseController
{
    /**
     * Display a listing of the Orders.
     * @param OrderService $service
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function list(OrderService $service): JsonResponse
    {
        $response = $service->list();

        return response()->json($response, $response->status);
    }

    /**
     * Store a newly created order.
     * @param OrderService $service
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(OrderService $service, Request $request): JsonResponse
    {
        $response = $service->create($request->all());

        return response()->json($response, $response->status);
    }

    /**
     * Return the specified order.
     * @param OrderService $service
     * @return \Illuminate\Http\JsonResponse
     */
    public function get(int $id, OrderService $service): JsonResponse
    {
        $response = $service->get($id);

        return response()->json($response, $response->status);
    }

    /**
     * @param OrderService $service
     * @param Request $request
     * @return JsonResponse
     */
    public function update(int $id, OrderService $service, Request $request)
    {
        $response = $service->update( $id, $request->all());

        return response()->json($response, $response->status);
    }

    /**
     * Remove the specified order.
     * @param int $id
     * @param OrderService $service
     * @return JsonResponse
     */
    public function delete(int $id,  OrderService $service)
    {
        $response = $service->delete($id);

        return response()->json($response, $response->status);
    }
}
