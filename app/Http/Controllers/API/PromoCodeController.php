<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\AssignCodeRequest;
use App\Http\Requests\Api\PromoCodeRequest;
use App\Models\PromoCode;
use App\Support\PromoCodeService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PromoCodeController extends Controller
{
    public function index(PromoCodeService $promoCodeService) :JsonResponse
    {
        return $promoCodeService->getPromoCodes();
    }

    public function find($id,PromoCodeService $promoCodeService)
    {
        return $promoCodeService->find($id);
    }

    public function create(PromoCodeRequest $promoCodeRequest, PromoCodeService $promoCodeService, PromoCode $promoCode):JsonResponse
    {
        return $promoCodeService->create($promoCodeRequest,$promoCode);
    }

    public function assign(AssignCodeRequest $assignCodeRequest, PromoCodeService $promoCodeService, PromoCode $promoCode)
    {
        return $promoCodeService->assign($assignCodeRequest,$promoCode);
    }
}
