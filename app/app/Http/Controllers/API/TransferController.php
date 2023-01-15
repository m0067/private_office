<?php

declare(strict_types=1);

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTransferRequest;
use App\Models\Transfer;
use App\Models\User;
use App\Services\TransferService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Class TransferController
 * @package App\Http\Controllers\API
 */
class TransferController extends Controller
{
    /**
     * TransferController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth:api');
        $this->authorizeResource(Transfer::class);
    }

    /**
     * @param  Request  $request
     *
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $transfers = Transfer::byUserRole($request->user())->get();

        return response()->json($transfers);
    }

    /**
     * @param  StoreTransferRequest  $request
     * @param  TransferService  $transferService
     *
     * @return JsonResponse
     * @throws \Exception
     */
    public function store(StoreTransferRequest $request, TransferService $transferService): JsonResponse
    {
        $validatedData = $request->validated();
        $sender        = $request->user();
        $recipient     = User::findOrFail($validatedData['recipient_id']);
        $transfer      = $transferService->transmit($sender, $recipient, $validatedData['amount']);

        return response()->json($transfer, 201);
    }

    /**
     * @param  Transfer  $transfer
     *
     * @return JsonResponse
     */
    public function show(Transfer $transfer): JsonResponse
    {
        return response()->json($transfer);
    }
}
