<?php

namespace Domain\Admin\Controllers;

use App\Http\Controllers\Controller;
use Domain\Admin\Models\AdminUser;
use Domain\Admin\Models\AdminTodo;
use App\Models\User;
use App\Models\Todo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    public function index()
    {
        try {
            // Get admin statistics
            $totalAdmins = AdminUser::count();
            $adminTodos = AdminTodo::count();
            $adminCompletedTodos = AdminTodo::where('status', 'completed')->count();
            $adminPendingTodos = AdminTodo::where('status', 'pending')->count();

            // Get regular user statistics
            $totalUsers = User::count();
            $userTodos = Todo::count();
            $userCompletedTodos = Todo::where('is_completed', true)->count();
            $userPendingTodos = Todo::where('is_completed', false)->count();

            // Calculate total statistics
            $totalTodos = $adminTodos + $userTodos;
            $completedTodos = $adminCompletedTodos + $userCompletedTodos;
            $pendingTodos = $adminPendingTodos + $userPendingTodos;

            return view('admin.dashboard', compact(
                'totalAdmins',
                'totalUsers',
                'totalTodos',
                'completedTodos',
                'pendingTodos'
            ));
        } catch (\Exception $e) {
            // Log the error and return with a user-friendly message
            \Log::error('Dashboard Error: ' . $e->getMessage());
            return view('admin.dashboard', [
                'totalAdmins' => AdminUser::count(),
                'totalUsers' => User::count(),
                'totalTodos' => 0,
                'completedTodos' => 0,
                'pendingTodos' => 0,
                'error' => 'There was an error loading some statistics. Please try again later.'
            ]);
        }
    }
} 