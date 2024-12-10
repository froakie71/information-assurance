@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        {{-- {{ dd($data) }} --}}
        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 gap-5 sm:grid-cols-3 mb-8">
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Total Tasks</dt>
                                <dd class="text-3xl font-semibold text-gray-900">{{ $data['totalTasks'] }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Completed Tasks</dt>
                                <dd class="text-3xl font-semibold text-gray-900">{{ $data['completedTasks'] }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Pending Tasks</dt>
                                <dd class="text-3xl font-semibold text-gray-900">{{ $data['pendingTasks'] }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Chart Section -->
        <div class="flex justify-center mb-6">
            <div class="bg-white rounded-lg shadow p-6 w-full max-w-4xl">
                <canvas id="taskChart" class="h-[400px] w-full"></canvas>
            </div>
        </div>

        <!-- Todo List Section -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Recent Tasks</h3>
            </div>
            <ul class="divide-y divide-gray-200">
                {{-- @forelse($todos as $todo)
                <li class="p-6">
                    <div class="flex items-center justify-between">
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center">
                                <input type="checkbox"
                                    class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded"
                                    {{ $todo->completed ? 'checked' : '' }}
                                    onchange="toggleTodo({{ $todo->id }})">
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-gray-900 {{ $todo->completed ? 'line-through' : '' }}">
                                        {{ $todo->title }}
                                    </p>
                                    <div class="mt-1">
                                        <p class="text-sm text-gray-500">{{ $todo->description }}</p>
                                        <p class="text-xs text-gray-400 mt-1">Due: {{ $todo->due_date->format('M d, Y') }}</p>
                                    </div>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $todo->priority === 'High' ? 'bg-red-100 text-red-800' : ($todo->priority === 'Medium' ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800') }} mt-2">
                                        {{ $todo->priority }} Priority
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center space-x-4 ml-4">
                            <a href="{{ route('todos.edit', $todo) }}" class="text-indigo-600 hover:text-indigo-900">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </a>
                            <form action="{{ route('todos.destroy', $todo) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </div>
                </li>
            @empty
                <li class="p-6 text-center text-gray-500">
                    No tasks found. <a href="{{ route('todos.create') }}" class="text-indigo-600 hover:text-indigo-500">Create one?</a>
                </li>
            @endforelse --}}
            </ul>
        </div>
    </div>

    @push('scripts')
        {{-- <script>
    const ctx = document.getElementById('taskChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: {!! json_encode($chartData['labels']) !!},
            datasets: [
                {
                    label: 'Tasks Completed',
                    data: {!! json_encode($chartData['completed']) !!},
                    borderColor: 'rgb(75, 192, 192)',
                    tension: 0.1,
                    fill: false
                },
                {
                    label: 'Tasks Added',
                    data: {!! json_encode($chartData['added']) !!},
                    borderColor: 'rgb(255, 99, 132)',
                    tension: 0.1,
                    fill: false
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'top',
                },
                title: {
                    display: true,
                    text: 'Weekly Todo Statistics'
                }
            }
        }
    });

    function toggleTodo(id) {
        fetch(`/todos/${id}/toggle`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        }).then(response => {
            if (response.ok) {
                window.location.reload();
            }
        });
    }
</script> --}}
    @endpush
@endsection
