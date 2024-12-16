<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Todo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TodoController extends Controller
{
    public function store(Request $request)
    {
        try {
            $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'required|string',
                'dueDate' => 'required|date',
                'priority' => 'required|string|in:Low,Medium,High',
                'image' => 'nullable|string',
            ]);

            $todo = new Todo();
            $todo->owner_id = auth()->id();
            $todo->title = $request->title;
            $todo->description = $request->description;
            $todo->due_date = $request->dueDate;
            $todo->priority = $request->priority;
            $todo->is_completed = false;

            if ($request->has('image') && !empty($request->image)) {
                try {
                    if (str_starts_with($request->image, 'data:image')) {
                        $image_parts = explode(";base64,", $request->image);
                        $image_type_aux = explode("image/", $image_parts[0]);
                        $image_type = $image_type_aux[1];
                        $image_base64 = base64_decode($image_parts[1]);
                        
                        $filename = 'todo-image-' . time() . '.' . $image_type;
                        Storage::disk('public')->put('todo-images/' . $filename, $image_base64);
                        $todo->image = 'todo-images/' . $filename;
                    }
                } catch (\Exception $e) {
                    \Log::error('Error processing image: ' . $e->getMessage());
                    return response()->json([
                        'message' => 'Error processing image',
                        'error' => $e->getMessage()
                    ], 422);
                }
            }

            $todo->save();

            return response()->json([
                'message' => 'Todo created successfully',
                'todo' => $todo
            ], 201);
        } catch (\Exception $e) {
            \Log::error('Error creating todo: ' . $e->getMessage());
            return response()->json([
                'message' => 'Error creating todo',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function index(int $ownerId)
    {
        $todos = Todo::where('owner_id', $ownerId)->orderBy('created_at', 'desc')->get();
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
