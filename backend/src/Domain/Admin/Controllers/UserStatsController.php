<?php

namespace Domain\Admin\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Domain\Admin\Models\AdminUser;
use Illuminate\Support\Facades\DB;

class UserStatsController extends Controller
{
    public function getUserRankings()
    {
        // Get regular user rankings with simpler query
        $userRankings = DB::table('users')
            ->select([
                'users.name',
                'users.email',
                DB::raw('COUNT(DISTINCT CASE WHEN todos.is_completed = 1 THEN todos.id END) as completed_todos'),
                DB::raw('COUNT(DISTINCT todos.id) as total_todos')
            ])
            ->leftJoin('todos', 'users.id', '=', 'todos.owner_id')
            ->groupBy('users.id', 'users.name', 'users.email')
            ->get()
            ->map(function ($user) {
                $user->completion_rate = $user->total_todos > 0 
                    ? round(($user->completed_todos * 100.0) / $user->total_todos, 1)
                    : 0.0;
                return $user;
            });

        // Get admin rankings with simpler query
        $adminRankings = DB::table('admin_users')
            ->select([
                'admin_users.name',
                'admin_users.email',
                DB::raw('COUNT(DISTINCT CASE WHEN admin_todos.status = "completed" THEN admin_todos.id END) as completed_todos'),
                DB::raw('COUNT(DISTINCT admin_todos.id) as total_todos')
            ])
            ->leftJoin('admin_todos', 'admin_users.id', '=', 'admin_todos.admin_user_id')
            ->groupBy('admin_users.id', 'admin_users.name', 'admin_users.email')
            ->get()
            ->map(function ($admin) {
                $admin->completion_rate = $admin->total_todos > 0 
                    ? round(($admin->completed_todos * 100.0) / $admin->total_todos, 1)
                    : 0.0;
                return $admin;
            });

        // Debug information
        \Log::info('User Rankings:', ['data' => $userRankings]);
        \Log::info('Admin Rankings:', ['data' => $adminRankings]);

        return view('admin.rankings', compact('userRankings', 'adminRankings'));
    }
} 