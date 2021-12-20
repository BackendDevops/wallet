<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\LoginRequest;
use App\Http\Requests\Api\RegisterRequest;
use App\Support\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{

    protected $result = [];

    public function __construct()
    {
         $this->result = array(
            'status' => false,
            'code'   => 401,
            'message' => "",
            'data'    => null,
        );
    }

    public function login(LoginRequest $request , AuthService $authService):JsonResponse
    {
        return $authService->login($request);
    }
    public function register( RegisterRequest  $request, AuthService $authService) :JsonResponse
    {
        return $authService->register($request);
    }

    public function renew(AuthService $authService)
    {
        $authService->renew();
    }

    public function logout(AuthService $authService)
    {
        return $authService->logout();
    }

}
