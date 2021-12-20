<?php

namespace App\Support;

use App\Http\Requests\Api\LoginRequest;
use App\Http\Requests\Api\RegisterRequest;
use App\Models\User;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthService
{
protected $result = [];
    public function __construct()
    {
        $this->result = [
            'status' => false,
            'code'   => 401,
            'message' => '',
            'data'    => null,
        ];
    }

    public function login(LoginRequest $request) :JsonResponse
    {
        $credentials = $request->only(['email','password']);
        try {
            if (!$token = JWTAuth::attempt($credentials)) {
                throw new Exception('invalid_credentials');
            }
            $this->result = [
                'status' => true,
                'code' => 200,
                'data' => [
                    '_token' => $token
                ],
               'message' => 'Login successful',
            ];
        } catch (Exception $e) {
            $this->result['message'] = $e->getMessage();
            $this->result['code'] = 401;
        } catch (JWTException $e) {
            $this->result['message'] = 'Could not create token';
            $this->result['code'] = 500;
        }
        return response()->json($this->result, $this->result['code']);
    }
    public function register(RegisterRequest $request)
    {

        try{
            $registerData = array(
                'name'      => $request->input('name'),
                'email'     => $request->input('email'),
                'password'  => Hash::make($request->input('password'))
            );
            $userData = User::create($registerData);
            if($userData){
                $this->result = [
                    'status' => true,
                    'code' => 200,
                    'data' => [
                        'record' => $userData
                    ],
                    'message' => 'Register Successful'
                ];
                return response()->json($this->result, $this->result['code']);
            }
            $this->result['message'] = "Register is unsuccessful";
            return response()->json($this->result, $this->result['code']);

        }catch(\Exception $e){
            $this->result['message'] = $e->getFile()." ".$e->getLine()." ".$e->getMessage();
            return response()->json($this->result, $this->result['code']);
        }

    }

    public function logout() :JsonResponse
    {
        try{
            auth()->logout();
            $this->result['status']  = true;
            $this->result['code']    = 200;
            $this->result['message'] = 'Logout is successful';
            return response()->json($this->result,$this->result['code']);
        }catch(\Exception $e){
            $this->result['message'] = $e->getFile()." ".$e->getLine()." ".$e->getMessage();
            return response()->json($this->result,$this->result['code']);

        }
    }
    public function renew() :JsonResponse
    {
        $data = [
            'status' => true,
            'code' => 200,
            'data' => [
                '_token' => auth()->refresh()
            ],
            'message' => '',
        ];
        return response()->json($data, $data['code']);
    }


}
