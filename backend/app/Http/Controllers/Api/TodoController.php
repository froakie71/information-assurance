<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Todo;

class TodoController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'dueDate' => 'required|date',
            'priority' => 'required|string|in:Low,Medium,High',
            'image' => 'nullable|image|max:2048'
        ]);

        $todo = new Todo();
        $todo->title = $request->title;
        $todo->description = $request->description;
        $todo->due_date = $request->dueDate;
        $todo->priority = $request->priority;
        
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('todo-images', 'public');
            $todo->image = $path;
        }
        
        $todo->save();

        return response()->json($todo, 201);
    }

    public function index()
    {
        $todos = Todo::orderBy('created_at', 'desc')->get();
        return response()->json($todos);
    }
} 