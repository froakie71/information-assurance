<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Todo;
use Illuminate\Support\Facades\Storage;

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

    public function toggle(Todo $todo)
    {
        $todo->is_completed = !$todo->is_completed;
        $todo->save();
        
        return response()->json($todo);
    }

    public function destroy(Todo $todo)
    {
        $todo->delete();
        return response()->json(null, 204);
    }

    public function show(Todo $todo)
    {
        return response()->json($todo);
    }

    public function update(Request $request, Todo $todo)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'due_date' => 'required|date',
            'priority' => 'required|string|in:Low,Medium,High',
            'image' => 'nullable|image|max:2048'
        ]);

        $todo->title = $request->title;
        $todo->description = $request->description;
        $todo->due_date = $request->due_date;
        $todo->priority = $request->priority;

        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($todo->image) {
                Storage::disk('public')->delete($todo->image);
            }
            $todo->image = $request->file('image')->store('todo-images', 'public');
        }

        $todo->save();

        return response()->json($todo);
    }
} 