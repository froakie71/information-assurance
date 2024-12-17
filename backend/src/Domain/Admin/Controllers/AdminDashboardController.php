<?php

namespace Domain\Admin\Controllers;

use App\Http\Controllers\Controller;
use Domain\Admin\Models\AdminUser;
use Domain\Admin\Models\AdminTodo;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // Get statistics for the dashboard
        $totalAdmins = AdminUser::count();
        $totalTodos = AdminTodo::count();
        $completedTodos = AdminTodo::where('status', 'completed')->count();
        $pendingTodos = AdminTodo::where('status', 'pending')->count();

        return view('admin.dashboard', compact(
            'totalAdmins',
            'totalTodos',
            'completedTodos',
            'pendingTodos'
        ));
    }
} 