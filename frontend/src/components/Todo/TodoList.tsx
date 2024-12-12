import { useState, useEffect } from 'react';

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
                  className="mt-1.5 h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                  readOnly
                />
                <div className="min-w-0 flex-1">
                  <h3 className="text-lg font-semibold text-gray-900">{todo.title}</h3>
                  <p className="mt-1 text-gray-600">{todo.description}</p>
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
              {todo.image && (
                <div className="ml-4 flex-shrink-0">
                  <img
                    src={`http://localhost:8000/storage/${todo.image}`}
                    alt="Todo attachment"
                    className="h-24 w-24 rounded-lg object-cover shadow-sm"
                  />
                </div>
              )}
            </div>
          </div>
        ))}
      </div>
    </div>
  );
} 