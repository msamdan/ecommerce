<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\AuthService;

class AuthController extends BaseController
{
    public function login(Request $request, AuthService $service)
    {
        $response = $service->login($request->all());

        return response()->json($response, $response->status);
    }

    public function logOut(AuthService $service)
    {
        $response = $service->logOut();
        return response()->json($response, $response->status);
    }

    public function unauthorized()
    {
        return response()->json(['success' => false, 'message' => 'unauthorized ', 'data' => null], 401);
    }
}
