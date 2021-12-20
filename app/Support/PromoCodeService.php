<?php

namespace App\Support;

use App\Http\Requests\Api\AssignCodeRequest;
use App\Http\Requests\Api\PromoCodeRequest;
use App\Models\PromoCode;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;

class PromoCodeService
{
    protected $result = [];

    public function __construct()
    {
        $this->result = [
            'status' => false,
            'code'   => 401,
            'data'   => null,
            'message' => '',
         ];

    }

    public function getPromoCodes() :JsonResponse
    {
        try{
            $promoCodes = PromoCode::with('users','users.wallet')->get();
            $data = $promoCodes->map(function($promoCode){
                return [
                    'id' => $promoCode->id,
                    'code' => $promoCode->code,
                    'amount' => intval($promoCode->amount),
                    'start_date' => $promoCode->start_date,
                    'end_date'   => $promoCode->end_date,
                    'quota'      => $promoCode->quota,
                    'users'      => $promoCode->users->map(function($user){
                        return [
                            'id' => $user->id,
                            'first_name' => $user->first_name,
                            'last_name'  => $user->last_name,
                            'username'   => $user->username,
                            'email'      => $user->email,
                            'wallet'     => [
                                'id'   => $user->wallet->id,
                                'balance' => intval($user->wallet->balance),
                                'updated_at' => $user->wallet->updated_at,
                            ],
                        ];
                    })
                ];
            });
            $this->result = [
                'status'  => true,
                'code'    => 200,
                'data'    => $data,
            ];
        }catch(\Exception $e){
            $this->result['message'] = $e->getMessage();
        }
        return response()->json($this->result,$this->result['code']);

    }

    public function find($id): JsonResponse
    {
        try{
            $promoCode = PromoCode::with('users','users.wallet')->where('id',$id)->first();
            if(!$promoCode){
                $this->result['message'] = 'Code not found';
                $this->result['code']    = 404;
                return response()->json($this->result,$this->result['code']);
            }
            $data = [
                    'id' => $promoCode->id,
                    'code' => $promoCode->code,
                    'amount' => intval($promoCode->amount),
                    'start_date' => $promoCode->start_date,
                    'end_date'   => $promoCode->end_date,
                    'quota'      => $promoCode->quota,
                    'users'      => $promoCode->users->map(function($user){
                        return [
                            'id' => $user->id,
                            'first_name' => $user->first_name,
                            'last_name'  => $user->last_name,
                            'username'   => $user->username,
                            'email'      => $user->email,
                            'wallet'     => [
                                'id'   => $user->wallet->id,
                                'balance' => intval($user->wallet->balance),
                                'updated_at' => $user->wallet->updated_at,
                            ],
                        ];
                    })
                ];

            $this->result = [
                'status'  => true,
                'code'    => 200,
                'data'    => $data,
            ];
        }catch(\Exception $e){
            $this->result['message'] = $e->getMessage();
        }
        return response()->json($this->result,$this->result['code']);

    }

    public function create(PromoCodeRequest $promoCodeRequest, PromoCode $promoCode) :JsonResponse
    {
      $code = $this->generateCode($promoCode);
      $data = [
          'code' => $code,
          'start_date' => $promoCodeRequest->input('start_date'),
          'end_date'   => $promoCodeRequest->input('end_date'),
          'amount'     => $promoCodeRequest->input('amount'),
          'quota'      => $promoCodeRequest->input('quota'),
      ];
      $promoCodeRecord = $promoCode->create($data);
      auth()->user()->wallet->balance += $promoCodeRecord->amount;
      auth()->user()->wallet->save();
      if($promoCodeRecord){
          $this->result = [
              'status' => true,
              'code'   => 201,
              'data' => [
                 'id'           => $promoCodeRecord->id,
                  'code'        => $promoCodeRecord->code,
                  'start_date'  => $promoCodeRecord->start_date,
                  'end_date'    => $promoCodeRecord->end_date,
                  'amount'      => $promoCodeRecord->amount,
                  'quota'       => $promoCodeRecord->quota,
              ],
          ];
      }else{
          $this->result = [
              'message' => "Creating Record is not successful",
              'code'    => 500,
          ];
      }

        return response()->json($this->result,$this->result['code']);
    }
    public function isExists($code,PromoCode $promoCode):bool
    {
        $isExists = $promoCode->where('code',$code)->first();
        if(!$isExists)
        {
            return false;
        }
        return true;
    }
    public function generateCode(PromoCode $promoCode):string
    {
        $code = Str::upper(Str::random(12));
        if(!$this->isExists($code,$promoCode)){
            return $code;
        }
        return $this->generateCode($promoCode);
    }

    public function assign(AssignCodeRequest $assignCodeRequest,PromoCode $promoCode) :JsonResponse
    {
        $user = auth()->user();
        $codes = $user->promoCodes()->pluck('code')->toArray();
        if(in_array($assignCodeRequest->input('code'),$codes)){
            //if user has this code
            //find promo record
            $record = $promoCode->where('code',$assignCodeRequest->input('code'))->first();
            if($record->quota>0){
                // check if promo has quota
                $user->wallet->balance -= $record->amount;
                $user->wallet->save();
                $record->quota -=1;
                $record->save();
                $this->result = [
                    'status' => true,
                    'code'   => 200,
                    'message' => 'Promotion code applied'
                ];
            }

        }else{
            $this->result = [
                'message' => 'Promotion Code is not in your wallet',
                'code'   => 404
            ];
        }
        return response()->json($this->result,$this->result['code']);

    }


}
