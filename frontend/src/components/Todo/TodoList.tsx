import { useState, useEffect } from 'react';
import { useRouter } from 'next/router';
import { Todo } from '../../types/todo';
import TodoCard from './TodoCard';
import StatsCard from './StatsCard';

export default function TodoList() {
  const [todos, setTodos] = useState<Todo[]>([]);
  const [taskStats, setTaskStats] = useState({ total: 0, completed: 0, pending: 0 });
  const router = useRouter();

  useEffect(() => {
    const id = localStorage.getItem('id');
    if (id) {
      fetchTodos(id);
    }
  }, []);

  const calculateTaskStats = (todoList: Todo[]) => {
    const total = todoList.length;
    const completed = todoList.filter(todo => todo.is_completed).length;
    const pending = total - completed;
    setTaskStats({ total, completed, pending });
  };

  const fetchTodos = async (id: string) => {
    try {
      const token = localStorage.getItem('token');
      const response = await fetch(`http://localhost:8000/api/todos/${id}`, {
        headers: {
          'Authorization': `Bearer ${token}`,
          'Content-Type': 'application/json'
        }
      });
      if (response.ok) {
        const data = await response.json();
        setTodos(data);
        calculateTaskStats(data);
      }
    } catch (error) {
      console.error('Error fetching todos:', error);
    }
  };

  const handleToggleComplete = async (id: number) => {
    try {
      const todo = todos.find(t => t.id === id);
      const token = localStorage.getItem('token');
      const response = await fetch(`http://localhost:8000/api/todos/${id}/toggle`, {
        method: 'PATCH',
        headers: {
          'Authorization': `Bearer ${token}`,
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({ is_completed: !todo?.is_completed }),
      });

      if (response.ok) {
        const updatedTodos = todos.map(todo =>
          todo.id === id ? { ...todo, is_completed: !todo.is_completed } : todo
        );
        setTodos(updatedTodos);
        calculateTaskStats(updatedTodos);
      }
    } catch (error) {
      console.error('Error updating todo:', error);
    }
  };

  const handleDelete = async (id: number) => {
    if (!confirm('Are you sure you want to delete this todo?')) return;

    try {
      const token = localStorage.getItem('token');
      const userId = localStorage.getItem('id');
      const response = await fetch(`http://localhost:8000/api/todos/${id}`, {
        method: 'DELETE',
        headers: {
          'Authorization': `Bearer ${token}`,
          'Content-Type': 'application/json'
        }
      });

      if (response.ok && userId) {
        await fetchTodos(userId);
      }
    } catch (error) {
      console.error('Error deleting todo:', error);
    }
  };

  return (
    <div className="max-w-7xl mx-auto mt-8 px-4 sm:px-6 lg:px-8">
      <div className="flex justify-between items-center mb-6">
        <h1 className="text-2xl font-bold">Todo List</h1>
        <button
          onClick={() => router.push('/add')}
          className="px-4 py-2 bg-indigo-600 text-white rounded-md"
        >
          Add Todo
        </button>
      </div>
      <StatsCard stats={taskStats} />
      <div className="space-y-6">
        <div className="grid gap-4 mt-6">
          {todos.map(todo => (
            <TodoCard
              key={todo.id}
              todo={todo}
              onToggle={handleToggleComplete}
              onDelete={handleDelete}
            />
          ))}
        </div>
      </div>
    </div>
  );
}