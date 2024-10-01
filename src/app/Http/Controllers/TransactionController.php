<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\DepositRequest;
use App\Http\Requests\TransferRequest;
use App\Services\TransactionService;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use OpenApi\Annotations as OA;

class TransactionController extends Controller
{
    protected TransactionService $transactionService;

    public function __construct(TransactionService $transactionService)
    {
        $this->transactionService = $transactionService;
    }

    /**
     * @OA\Post(
     *     path="/api/users/{id}/deposit",
     *     summary="Пополнение баланса пользователя",
     *     tags={"Пользователи"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID пользователя",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"amount"},
     *             @OA\Property(property="amount", type="number", format="float", example=500.00)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Успешное пополнение",
     *         @OA\JsonContent(ref="#/components/schemas/UserResource")
     *     ),
     *     @OA\Response(response=404, description="Пользователь не найден"),
     *     @OA\Response(response=400, description="Некорректные данные")
     * )
     */
    public function deposit(DepositRequest $request, User $user): UserResource
    {
        $this->transactionService->deposit($user, $request->validated()['amount']);

        return new UserResource($user);
    }

    /**
     * @OA\Post(
     *     path="/api/users/{id}/transfer",
     *     summary="Перевод средств между пользователями",
     *     tags={"Пользователи"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID отправителя",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"recipient_id", "amount"},
     *             @OA\Property(property="recipient_id", type="integer", example=2),
     *             @OA\Property(property="amount", type="number", example=50.00)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Перевод успешно выполнен",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Transfer successful"),
     *             @OA\Property(property="sender", ref="#/components/schemas/UserResource"),
     *             @OA\Property(property="recipient", ref="#/components/schemas/UserResource")
     *         )
     *     ),
     *     @OA\Response(response=400, description="Ошибка перевода средств")
     * )
     */
    public function transfer(TransferRequest $request, User $user): JsonResponse
    {
        $recipient = User::find($request->validated()['recipient_id']);
        $this->transactionService->transfer($user, $recipient, $request->validated()['amount']);

        return response()->json([
            'message' => 'Transfer successful',
            'sender' => new UserResource($user),
            'recipient' => new UserResource($recipient)
        ]);
    }
}
