<?php

namespace Domain\Admin\Controllers;

use App\Http\Controllers\Controller;
use Domain\Admin\Models\AdminTodo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminTodoController extends Controller
{
    public function index()
    {
        $todos = AdminTodo::with('admin')
            ->latest()
            ->paginate(10);

        return view('admin.todos.index', compact('todos'));
    }

    public function create()
    {
        return view('admin.todos.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'required|date',
            'status' => 'required|in:pending,completed'
        ]);

        $todo = new AdminTodo();
        $todo->admin_user_id = Auth::guard('admin')->id();
        $todo->title = $request->title;
        $todo->description = $request->description;
        $todo->due_date = $request->due_date;
        $todo->status = $request->status;
        $todo->save();

        return redirect()
            ->route('admin.todos.index')
            ->with('success', 'Todo created successfully');
    }

    public function edit(AdminTodo $todo)
    {
        return view('admin.todos.edit', compact('todo'));
    }

    public function update(Request $request, AdminTodo $todo)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'required|date',
            'status' => 'required|in:pending,completed'
        ]);

        $todo->update($request->all());

        return redirect()
            ->route('admin.todos.index')
            ->with('success', 'Todo updated successfully');
    }

    public function destroy(AdminTodo $todo)
    {
        $todo->delete();

        return redirect()
            ->route('admin.todos.index')
            ->with('success', 'Todo deleted successfully');
    }
} 