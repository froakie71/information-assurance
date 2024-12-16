import { useState, useEffect } from 'react';
import { Todo } from '../types/todo';
import TodoCard from './Todo/TodoCard';
import StatsCard from './Todo/StatsCard';
import { useRouter } from 'next/router';

export default function TodoList() {
  const [todos, setTodos] = useState<Todo[]>([]);
  const [stats, setStats] = useState({
    total: 0,
    completed: 0,
    pending: 0
  });
  const router = useRouter();

  useEffect(() => {
    fetchTodos();
  }, []);

  const fetchTodos = async () => {
    try {
      const token = localStorage.getItem('token');
      const response = await fetch('http://localhost:8000/api/todos', {
        headers: {
          'Authorization': `Bearer ${token}`,
          'Content-Type': 'application/json'
        }
      });
      
      if (response.ok) {
        const data = await response.json();
        setTodos(data);
        updateStats(data);
      }
    } catch (error) {
      console.error('Error fetching todos:', error);
    }
  };

  const updateStats = (todoList: Todo[]) => {
    setStats({
      total: todoList.length,
      completed: todoList.filter(todo => todo.is_completed).length,
      pending: todoList.filter(todo => !todo.is_completed).length
    });
  };

  const toggleComplete = async (id: number) => {
    try {
      const token = localStorage.getItem('token');
      const response = await fetch(`http://localhost:8000/api/todos/${id}/toggle`, {
        method: 'PATCH',
        headers: {
          'Authorization': `Bearer ${token}`,
          'Content-Type': 'application/json'
        }
      });

      if (response.ok) {
        await fetchTodos();
      }
    } catch (error) {
      console.error('Error toggling todo:', error);
    }
  };

  const handleLogout = async () => {
    try {
      localStorage.removeItem('token');
      router.push('/login');
    } catch (error) {
      console.error('Error logging out:', error);
    }
  };

  const handleDelete = async (id: number) => {
    try {
      const token = localStorage.getItem('token');
      const response = await fetch(`http://localhost:8000/api/todos/${id}`, {
        method: 'DELETE',
        headers: {
          'Authorization': `Bearer ${token}`,
          'Content-Type': 'application/json'
        }
      });

      if (response.ok) {
        await fetchTodos();
      }
    } catch (error) {
      console.error('Error deleting todo:', error);
    }
  };

  return (
    <div className="container mx-auto p-4">
      <div className="flex justify-between items-center mb-6">
        <h1 className="text-2xl font-bold">Todo List</h1>
        <div className="flex gap-2">
          <button
            onClick={() => router.push('/history')}
            className="px-4 py-2 bg-gray-200 rounded-md"
          >
            History
          </button>
          <button
            onClick={handleLogout}
            className="px-4 py-2 bg-red-500 text-white rounded-md"
          >
            Logout
          </button>
        </div>
      </div>

      <StatsCard stats={stats} />

      <div className="grid gap-4 mt-6">
        {todos.map(todo => (
          <TodoCard
            key={todo.id}
            todo={todo}
            onToggle={toggleComplete}
            onDelete={handleDelete}
          />
        ))}
      </div>
    </div>
  );
}
