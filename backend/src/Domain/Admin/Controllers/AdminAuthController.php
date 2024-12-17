<?php

namespace Domain\Admin\Controllers;

use App\Http\Controllers\Controller;
use Domain\Admin\Models\AdminUser;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class AdminAuthController extends Controller
{
    public function showLoginForm()
    {
        return view('admin.auth.login');
    }

    public function showRegistrationForm()
    {
        return view('admin.auth.register');
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:admin_users',
            'password' => 'required|string|min:8',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        $admin = AdminUser::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        Auth::guard('admin')->login($admin);

        if ($request->wantsJson()) {
            $token = $admin->createToken('admin-token', ['admin'])->plainTextToken;
            return response()->json([
                'admin' => $admin,
                'token' => $token
            ], 201);
        }

        return redirect()->route('admin.dashboard');
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            if ($request->wantsJson()) {
                return response()->json($validator->errors(), 422);
            }
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        $credentials = $request->only('email', 'password');

        if (Auth::guard('admin')->attempt($credentials)) {
            $request->session()->regenerate();

            if ($request->wantsJson()) {
                $admin = Auth::guard('admin')->user();
                $token = $admin->createToken('admin-token', ['admin'])->plainTextToken;
                return response()->json([
                    'admin' => $admin,
                    'token' => $token
                ]);
            }

            return redirect()->intended(route('admin.dashboard'));
        }

        $error = ['email' => ['The provided credentials are incorrect.']];
        
        if ($request->wantsJson()) {
            throw ValidationException::withMessages($error);
        }
        
        return back()
            ->withErrors($error)
            ->withInput();
    }

    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        if ($request->wantsJson()) {
            return response()->json(['message' => 'Logged out successfully']);
        }

        return redirect()->route('admin.login');
    }
} 