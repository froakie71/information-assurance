import { useRouter } from 'next/router';
import { useState, useEffect } from 'react'; // Importing useState and useEffect

interface Todo {
  title: string;
  description: string;
  due_date: string;
  priority: string;
  completed: boolean;
  image: string | null;
}

export default function EditTodo() {
  const router = useRouter();
  const { id } = router.query;
  const [todo, setTodo] = useState<Todo>({
    title: '',
    description: '',
    due_date: '',
    priority: '',
    completed: false, // Initialize completed property
    image: null
  });
  const [todos, setTodos] = useState<Todo[]>([]);
  const [taskCounts, setTaskCounts] = useState({ total: 0, completed: 0, pending: 0 });

  useEffect(() => {
    if (id) {
      fetchTodo();
      fetchTodos(); // Fetch all todos when the component mounts
    }
  }, [id]);

  const fetchTodo = async () => {
    try {
      const token = localStorage.getItem('token');
      if (!token) {
        console.error('No token found');
        router.push('/login');
        return;
      }

      const response = await fetch(`http://localhost:8000/api/todos/${id}`, {
        method: 'GET',
        headers: {
          'Authorization': `Bearer ${token}`,
          'Content-Type': 'application/json',
        },
        credentials: 'include'
      });

      if (response.ok) {
        const data: Todo = await response.json();
        setTodo({
          title: data.title,
          description: data.description,
          due_date: data.due_date ? data.due_date.split('T')[0] : '',
          priority: data.priority,
          completed: data.completed,
          image: data.image
        });
      }
    } catch (error) {
      console.error('Error fetching todo:', error);
    }
  };

  const fetchTodos = async () => {
    try {
      const userId = localStorage.getItem('id');
      const token = localStorage.getItem('token');
      
      if (!userId || !token) {
        console.error('No user ID or token found');
        return;
      }
      
      const response = await fetch(`http://localhost:8000/api/todos/${userId}`, {
        method: 'GET',
        headers: {
          'Authorization': `Bearer ${token}`,
          'Content-Type': 'application/json',
        }
      });
      
      if (response.ok) {
        const data = await response.json();
        if (Array.isArray(data)) {
          setTodos(data);
          calculateTaskCounts(data);
        } else {
          console.error('Invalid todos data received:', data);
        }
      }
    } catch (error) {
      console.error('Error fetching todos:', error);
    }
  };

  const calculateTaskCounts = (todoList: Todo[]) => {
    if (!Array.isArray(todoList)) {
      console.error('Invalid todos data:', todoList);
      return;
    }
    const total = todoList.length;
    const completed = todoList.filter(todo => todo.completed).length;
    const pending = total - completed;
    setTaskCounts({ total, completed, pending });
  };

  const handleSubmit = async (e: React.FormEvent<HTMLFormElement>) => {
    e.preventDefault();
    const form = e.currentTarget;
    const token = localStorage.getItem('token');

    if (!token) {
      console.error('No token found');
      router.push('/login');
      return;
    }

    try {
      // Create a new FormData object
      const formData = new FormData();
      
      // Add form fields manually to ensure proper naming
      formData.append('title', (form.querySelector('[name="title"]') as HTMLInputElement).value);
      formData.append('description', (form.querySelector('[name="description"]') as HTMLTextAreaElement).value);
      formData.append('due_date', (form.querySelector('[name="due_date"]') as HTMLInputElement).value);
      formData.append('priority', (form.querySelector('[name="priority"]') as HTMLSelectElement).value);
      
      // If there's an image, append it
      if (todo.image) {
        formData.append('image', todo.image);
      }

      // Add _method field to simulate PATCH request
      formData.append('_method', 'PATCH');

      const response = await fetch(`http://localhost:8000/api/todos/${id}`, {
        method: 'POST', // Using POST with _method: PATCH for Laravel
        headers: {
          'Authorization': `Bearer ${token}`,
          'Accept': 'application/json'
        },
        body: formData
      });

      if (!response.ok) {
        const errorData = await response.json();
        throw new Error(errorData.message || 'Failed to update todo');
      }

      router.push('/dashboard');
    } catch (error) {
      console.error('Error updating todo:', error);
    }
  };

  return (
    <div className="max-w-2xl mx-auto mt-8 p-4">
      <h1 className="text-2xl font-bold mb-6">Edit Todo</h1>
      {todo.image && (
        <div className="mb-4">
          <img
            src={todo.image}
            alt="Todo Image"
            className="w-full h-auto rounded-md"
          />
        </div>
      )}
      <form onSubmit={handleSubmit} className="space-y-6">
        <div>
          <label className="block text-sm font-medium text-gray-700">Title</label>
          <input
            type="text"
            name="title"
            defaultValue={todo.title}
            className="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
            required
          />
        </div>

        <div>
          <label className="block text-sm font-medium text-gray-700">Description</label>
          <textarea
            name="description"
            defaultValue={todo.description}
            className="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
            rows={4}
            required
          />
        </div>

        <div>
          <label className="block text-sm font-medium text-gray-700">Due Date</label>
          <input
            type="date"
            name="due_date"
            defaultValue={todo.due_date}
            className="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
            required
          />
        </div>

        <div>
          <label className="block text-sm font-medium text-gray-700">Priority</label>
          <select
            name="priority"
            defaultValue={todo.priority}
            className="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
            required
          >
            <option value="Low">Low</option>
            <option value="Medium">Medium</option>
            <option value="High">High</option>
          </select>
        </div>

        <div className="flex justify-end space-x-4">
          <button
            type="button"
            onClick={() => router.back()}
            className="px-4 py-2 border rounded-md text-gray-700 hover:bg-gray-50"
          >
            Cancel
          </button>
          <button
            type="submit"
            className="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700"
          >
            Update Todo
          </button>
        </div>
      </form>
    </div>
  );
}
