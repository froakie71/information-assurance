<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Domain\Admin\Repositories\AdminUserRepositoryInterface;
use Illuminate\Http\JsonResponse;

class UserStatsController extends Controller
{
    public function __construct(
        private AdminUserRepositoryInterface $adminUserRepository
    ) {}

    public function getUserRankings(): JsonResponse
    {
        $rankings = $this->adminUserRepository->getUsersWithCompletedTasksCount();
        return response()->json([
            'rankings' => $rankings
        ]);
    }
} 