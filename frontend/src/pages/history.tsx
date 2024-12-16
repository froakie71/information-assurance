import { useState, useEffect } from 'react';
import { useRouter } from 'next/router';
import Image from 'next/image';

interface Todo {
  id: number;
  title: string;
  description: string;
  due_date: string;
  priority: string;
  is_completed: boolean;
  image: string | null;
  created_at: string;
}

export default function History() {
  const [completedTodos, setCompletedTodos] = useState<Todo[]>([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState<string | null>(null);
  const router = useRouter();

  useEffect(() => {
    fetchCompletedTodos();
  }, []);

  const fetchCompletedTodos = async () => {
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
          'Accept': 'application/json',
        }
      });

      if (!response.ok) {
        throw new Error('Failed to fetch todos');
      }

      const data = await response.json();
      // Filter completed todos, similar to Flutter implementation
      const completed = data.filter((todo: Todo) => todo.is_completed);
      setCompletedTodos(completed);
    } catch (error) {
      setError('Error fetching completed todos');
      console.error('Error fetching completed todos:', error);
    } finally {
      setLoading(false);
    }
  };

  if (loading) {
    return (
      <div className="flex justify-center items-center h-screen">
        <div className="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-blue-500"></div>
      </div>
    );
  }

  return (
    <div className="container mx-auto p-4">
      <div className="flex justify-between items-center mb-6">
        <h1 className="text-2xl font-bold">Completed Tasks History</h1>
        <button
          onClick={() => router.push('/dashboard')}
          className="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600"
        >
          Back to Dashboard
        </button>
      </div>

      {error && (
        <div className="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
          {error}
        </div>
      )}

      <div className="grid gap-4">
        {completedTodos.map((todo) => (
          <div key={todo.id} className="bg-white p-4 rounded-lg shadow">
            <div className="flex items-start gap-4">
              {todo.image && (
                <div className="w-24 h-24 relative">
                  <Image
                    src={todo.image.startsWith('data:image') ? 
                      todo.image : 
                      todo.image.startsWith('//') ? 
                        `http:${todo.image}` : 
                        `http://localhost:8000/storage/${todo.image}`}
                    alt={todo.title}
                    width={96}
                    height={96}
                    className="rounded-md object-cover"
                  />
                </div>
              )}
              <div className="flex-1">
                <h3 className="font-medium line-through text-gray-500">
                  {todo.title}
                </h3>
                <p className="text-gray-600 mt-1">{todo.description}</p>
                <div className="flex items-center gap-4 mt-2">
                  <span className="text-sm text-gray-500">
                    Due: {new Date(todo.due_date).toLocaleDateString()}
                  </span>
                  <span className={`px-2 py-1 rounded-full text-xs ${
                    todo.priority.toLowerCase() === 'high' ? 'bg-red-100 text-red-800' :
                    todo.priority.toLowerCase() === 'medium' ? 'bg-yellow-100 text-yellow-800' :
                    'bg-green-100 text-green-800'
                  }`}>
                    {todo.priority}
                  </span>
                </div>
              </div>
            </div>
          </div>
        ))}

        {completedTodos.length === 0 && (
          <div className="text-center py-8 text-gray-500">
            No completed tasks found in history.
          </div>
        )}
      </div>
    </div>
  );
} 