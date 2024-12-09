@extends('layouts.app')

@section('title', 'Leaderboard')

@section('content')
<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6 flex justify-between items-center">
            <div>
                <h3 class="text-lg leading-6 font-medium text-gray-900">Todo Champions</h3>
                <p class="mt-1 max-w-2xl text-sm text-gray-500">Users ranked by completed todos</p>
            </div>
            <div class="flex space-x-3">
                <select id="timeRange" class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    <option value="week">This Week</option>
                    <option value="month">This Month</option>
                    <option value="all">All Time</option>
                </select>
            </div>
        </div>
        <div class="border-t border-gray-200">
            <ul class="divide-y divide-gray-200">
                @forelse($leaderboard as $index => $user)
                    <li class="px-4 py-4 sm:px-6 hover:bg-gray-50">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-12 w-12 relative">
                                    @if($index < 3)
                                        <div class="absolute -top-2 -left-2 w-6 h-6 bg-{{ $index === 0 ? 'yellow' : ($index === 1 ? 'gray' : 'yellow') }}-400 rounded-full flex items-center justify-center">
                                            <span class="text-white font-bold text-sm">{{ $index + 1 }}</span>
                                        </div>
                                    @endif
                                    <div class="h-12 w-12 rounded-full bg-indigo-100 flex items-center justify-center">
                                        <span class="text-xl font-medium text-indigo-600">{{ strtoupper(substr($user->name, 0, 2)) }}</span>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $user->name }}
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        Member since {{ $user->created_at->format('M Y') }}
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-center space-x-8">
                                <div class="flex flex-col items-end">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $user->completed_todos_count }}
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        Completed Tasks
                                    </div>
                                </div>
                                <div class="flex flex-col items-end">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ number_format($user->completion_rate, 1) }}%
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        Completion Rate
                                    </div>
                                </div>
                                <div class="flex flex-col items-end">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $user->streak_days }}
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        Day Streak
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mt-2">
                            <div class="flex justify-between mb-1">
                                <span class="text-xs text-gray-500">Progress to next rank</span>
                                <span class="text-xs text-gray-500">{{ $user->tasks_to_next_rank }} tasks to go</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-indigo-600 h-2 rounded-full" style="width: {{ $user->progress_percentage }}%"></div>
                            </div>
                        </div>
                    </li>
                @empty
                    <li class="px-4 py-4 sm:px-6 text-center text-gray-500">
                        No users found
                    </li>
                @endforelse
            </ul>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.getElementById('timeRange').addEventListener('change', function() {
        window.location.href = `{{ route('leaderboard') }}?range=${this.value}`;
    });
</script>
@endpush
@endsection
