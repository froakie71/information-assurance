import { useState, useEffect } from 'react';
import Link from 'next/link';

interface Todo {
  id: number;
  title: string;
  description: string;
  due_date: string;
  priority: string;
  image: string | null;
  is_completed: boolean;
}

export default function TodoList() {
  const [todos, setTodos] = useState<Todo[]>([]);

  useEffect(() => {
    fetchTodos();
  }, []);

  const fetchTodos = async () => {
    try {
      const response = await fetch('http://localhost:8000/api/todos', {
        credentials: 'include'
      });
      if (response.ok) {
        const data = await response.json();
        setTodos(data);
      }
    } catch (error) {
      console.error('Error fetching todos:', error);
    }
  };

  const handleToggleComplete = async (id: number) => {
    try {
      const todo = todos.find(t => t.id === id);
      const response = await fetch(`http://localhost:8000/api/todos/${id}/toggle`, {
        method: 'PATCH',
        credentials: 'include',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({ is_completed: !todo?.is_completed }),
      });

      if (response.ok) {
        setTodos(todos.map(todo => 
          todo.id === id ? { ...todo, is_completed: !todo.is_completed } : todo
        ));
      }
    } catch (error) {
      console.error('Error updating todo:', error);
    }
  };

  const handleDelete = async (id: number) => {
    if (!confirm('Are you sure you want to delete this todo?')) return;

    try {
      const response = await fetch(`http://localhost:8000/api/todos/${id}`, {
        method: 'DELETE',
        credentials: 'include',
      });

      if (response.ok) {
        setTodos(todos.filter(todo => todo.id !== id));
      }
    } catch (error) {
      console.error('Error deleting todo:', error);
    }
  };

  const formatDate = (dateString: string) => {
    return new Date(dateString).toLocaleDateString();
  };

  return (
    <div className="max-w-7xl mx-auto mt-8 px-4 sm:px-6 lg:px-8">
      <h2 className="text-2xl font-bold text-gray-900 mb-6">Recent Tasks</h2>
      <div className="space-y-6">
        {todos.map((todo) => (
          <div key={todo.id} className="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow duration-200 p-6">
            <div className="flex items-start justify-between">
              <div className="flex items-start space-x-4 flex-grow">
                <input
                  type="checkbox"
                  checked={todo.is_completed}
                  onChange={() => handleToggleComplete(todo.id)}
                  className="mt-1.5 h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 cursor-pointer"
                />
                <div className="min-w-0 flex-1">
                  <h3 className={`text-lg font-semibold ${todo.is_completed ? 'line-through text-gray-500' : 'text-gray-900'}`}>
                    {todo.title}
                  </h3>
                  <p className={`mt-1 ${todo.is_completed ? 'line-through text-gray-400' : 'text-gray-600'}`}>
                    {todo.description}
                  </p>
                  <div className="mt-2 flex items-center space-x-4">
                    <div className="text-sm text-gray-500">
                      Due: {formatDate(todo.due_date)}
                    </div>
                    <div className={`px-3 py-1 rounded-full text-sm font-medium ${
                      todo.priority === 'High' ? 'bg-red-100 text-red-800' :
                      todo.priority === 'Medium' ? 'bg-yellow-100 text-yellow-800' :
                      'bg-green-100 text-green-800'
                    }`}>
                      {todo.priority} Priority
                    </div>
                  </div>
                </div>
              </div>
              <div className="flex items-center space-x-4">
                {todo.image && (
                  <img
                    src={`http://localhost:8000/storage/${todo.image}`}
                    alt="Todo attachment"
                    className="h-24 w-24 rounded-lg object-cover shadow-sm"
                  />
                )}
                <div className="flex space-x-2">
                  <Link
                    href={`/dashboard/edit-todo/${todo.id}`}
                    className="text-blue-600 hover:text-blue-800"
                  >
                    <svg 
                      className="w-5 h-5" 
                      fill="none" 
                      stroke="currentColor" 
                      viewBox="0 0 24 24"
                    >
                      <path 
                        strokeLinecap="round" 
                        strokeLinejoin="round" 
                        strokeWidth="2" 
                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" 
                      />
                    </svg>
                  </Link>
                  <button
                    onClick={() => handleDelete(todo.id)}
                    className="text-red-600 hover:text-red-800"
                  >
                    <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                  </button>
                </div>
              </div>
            </div>
          </div>
        ))}
      </div>
    </div>
  );
} 