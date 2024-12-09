<?php

namespace App\Http\Controllers\Todo\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Application\Todo\RegisterTodo;
class TodoAPIController extends Controller
{
    public function __construct(private RegisterTodo $registerTodo)
    {
        $this->registerTodo = $registerTodo;
    }

    public function findall()
    {
        $data = $this->registerTodo->findall();
        if($data){
            return response()->json(compact('data'));
        }else{
            return response()->json(['message' => 'Data not found']);
        }
    }
}
