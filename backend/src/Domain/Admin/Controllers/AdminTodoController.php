<?php

namespace Domain\Admin\Controllers;

use App\Http\Controllers\Controller;
use Domain\Admin\Repositories\AdminTodoRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AdminTodoController extends Controller
{
    public function __construct(
        private AdminTodoRepositoryInterface $adminTodoRepository
    ) {}

    public function index(Request $request): JsonResponse
    {
        $perPage = $request->get('per_page', 10);
        $todos = $this->adminTodoRepository->getAllPaginated($perPage);
        return response()->json($todos);
    }

    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'nullable|date',
            'status' => 'required|in:pending,completed'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $data = $request->all();
        $data['admin_user_id'] = $request->user('admin')->id;
        
        $todo = $this->adminTodoRepository->create($data);
        return response()->json($todo, 201);
    }

    public function show(int $id): JsonResponse
    {
        $todo = $this->adminTodoRepository->findById($id);
        
        if (!$todo) {
            return response()->json(['message' => 'Todo not found'], 404);
        }

        return response()->json($todo);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $todo = $this->adminTodoRepository->findById($id);
        
        if (!$todo) {
            return response()->json(['message' => 'Todo not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'nullable|date',
            'status' => 'sometimes|required|in:pending,completed'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $todo = $this->adminTodoRepository->update($todo, $request->all());
        return response()->json($todo);
    }

    public function destroy(int $id): JsonResponse
    {
        $todo = $this->adminTodoRepository->findById($id);
        
        if (!$todo) {
            return response()->json(['message' => 'Todo not found'], 404);
        }

        $this->adminTodoRepository->delete($todo);
        return response()->json(['message' => 'Todo deleted successfully']);
    }
} 