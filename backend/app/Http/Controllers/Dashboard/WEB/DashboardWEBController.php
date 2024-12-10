<?php

namespace App\Http\Controllers\Dashboard\WEB;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Application\Todo\RegisterTodo;


class DashboardWEBController extends Controller
{
    public function __construct(private RegisterTodo $registerTodo)
    {
        $this->registerTodo = $registerTodo;
    }
    public function totalTasks(){
        return $this->registerTodo->findall();
    }
    public function completedTasks(){
        return $this->registerTodo->findCompleted();
    }
    public function pendingTasks(){
        return $this->registerTodo->findPending();
    }
    public function index(){
        $data = [
            'totalTasks' => count($this->totalTasks()),
            'completedTasks' => count($this->completedTasks()),
            'pendingTasks' => count($this->pendingTasks()),
        ];
        return view('dashboard.index', compact('data'));
    }
    public function leaderboard(){
        return view('dashboard.leaderboard');
    }
}
