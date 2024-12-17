<?php

namespace Domain\Admin\Controllers;

use App\Http\Controllers\Controller;
use Domain\Admin\Models\AdminUser;
use Domain\Admin\Repositories\AdminUserRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class AdminAuthController extends Controller
{
    private $adminUserRepository;

    public function __construct(AdminUserRepository $adminUserRepository)
    {
        $this->adminUserRepository = $adminUserRepository;
    }

    public function register(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:admin_users',
            'password' => 'required|string|min:8',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $admin = $this->adminUserRepository->create($request->all());
        $token = $admin->createToken('admin-token', ['admin'])->plainTextToken;

        return response()->json([
            'admin' => $admin,
            'token' => $token
        ], 201);
    }

    public function login(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $admin = $this->adminUserRepository->findByEmail($request->email);

        if (!$admin || !Hash::check($request->password, $admin->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        $token = $admin->createToken('admin-token', ['admin'])->plainTextToken;

        return response()->json([
            'admin' => $admin,
            'token' => $token
        ]);
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user('admin')->currentAccessToken()->delete();
        return response()->json(['message' => 'Logged out successfully']);
    }
} 