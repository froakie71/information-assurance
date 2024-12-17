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
    fetchTodos();
  }, []);

  const fetchTodos = async () => {
    try {
      const token = localStorage.getItem('token');
      const userId = localStorage.getItem('id');

      if (!token || !userId) {
        router.push('/login');
        return;
      }

      const response = await fetch(`http://localhost:8000/api/todos/${userId}`, {
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

  const calculateTaskStats = (todoList: Todo[]) => {
    const total = todoList.length;
    const completed = todoList.filter(todo => todo.is_completed).length;
    const pending = total - completed;
    setTaskStats({ total, completed, pending });
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

  const handleToggle = async (id: number) => {
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

  return (
    <div className="space-y-6">
      <StatsCard stats={taskStats} />
      <div className="grid gap-4">
        {todos.map((todo) => (
          <TodoCard
            key={todo.id}
            todo={todo}
            onToggle={handleToggle}
            onDelete={handleDelete}
          />
        ))}
      </div>
    </div>
  );
}