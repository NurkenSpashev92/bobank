<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Services\UserService;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use OpenApi\Annotations as OA;

/**
 * @OA\Info(
 *     version="1.0.0",
 *     title="User API",
 *     description="API for managing users"
 * )
 *
 * @OA\Tag(
 *     name="Users",
 *     description="API Endpoints for Users"
 * )
 */
class UserController extends Controller
{
    protected UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * @OA\Get(
     *     path="/api/users",
     *     summary="Получение всех пользователей с пагинацией",
     *     tags={"Пользователи"},
     *     @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         description="Количество пользователей на странице",
     *         required=false,
     *         @OA\Schema(type="integer", example=10)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Список пользователей",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/UserResource")),
     *             @OA\Property(property="name", type="string", description="John Doe"),
     *             @OA\Property(property="email", type="string", description="john@example.com"),
     *             @OA\Property(property="age", type="integer", description="30"),
     *             @OA\Property(property="balance", type="number", format="float", description="1000")
     *         )
     *     ),
     *     @OA\Response(response=500, description="Ошибка сервера")
     * )
     */
    public function index(): array
    {
        $perPage = request()->query('per_page', 10);
        $users = $this->userService->getPaginatedUsers((int)$perPage);

        return [
            'data' => UserResource::collection($users),
            'current_page' => $users->currentPage(),
            'last_page' => $users->lastPage(),
            'per_page' => $users->perPage(),
            'total' => $users->total(),
        ];
    }

    /**
     * @OA\Post(
     *     path="/api/users",
     *     tags={"Пользователи"},
     *     summary="Создание нового пользователя",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="name", type="string", description="John Doe"),
     *             @OA\Property(property="email", type="string", description="john@example.com"),
     *             @OA\Property(property="age", type="integer", description="30"),
     *             @OA\Property(property="balance", type="number", format="float", description="1000")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="User created successfully",
     *         @OA\JsonContent(ref="#/components/schemas/User")
     *     )
     * )
     */
    public function store(CreateUserRequest $request): JsonResponse
    {
        $user = $this->userService->createUser($request->validated());

        return response()->json(new UserResource($user), 201);
    }


    /**
     * @OA\Put(
     *     path="/api/users/{id}",
     *     summary="Обновить пользователя",
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
     *             required={"name", "email", "age"},
     *             @OA\Property(property="name", type="string", example="John Doe"),
     *             @OA\Property(property="email", type="string", format="email", example="john@example.com"),
     *             @OA\Property(property="age", type="integer", example=30)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Успешное обновление",
     *         @OA\JsonContent(ref="#/components/schemas/User")
     *     ),
     *     @OA\Response(response=404, description="Пользователь не найден")
     * )
     */
    public function update(UpdateUserRequest $request, User $user): UserResource
    {
        $this->userService->updateUser($user, $request->validated());

        return new UserResource($user);
    }
}
