<?php

namespace App\Http\Controllers\Todo\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Application\Todo\RegisterTodo;
use App\Infrastructure\Persistence\Eloquent\Todo\TodoModel;

class TodoAPIController extends Controller
{
    public function __construct(private RegisterTodo $registerTodo)
    {
        $this->registerTodo = $registerTodo;
    }

    public function findall()
    {
        try{
            $todoModel = $this->registerTodo->findall();
            if(!$todoModel){
                return response()->json(['message' => 'Data not found']);
            }
            $data = array_map(fn($todoModel)=>$todoModel->toArray(), $todoModel);

            return response()->json(compact('data'));
        }catch(\Exception $e){
            return response()->json(['message' => 'Data not found']);
        }
    }
    public function addTodo(Request $request)
    {
        $data = $request->all();

        return response()->json(compact('data'));
    }
    public function updateTodo(Request $request){
        $data = $request->all();

        return response()->json(compact('data'));
    }
    public function deleteTodo(Request $request){
        $data = $request->all();

        return response()->json(compact('data'));
    }
}
