import { useState } from 'react';
import { useRouter } from 'next/router';
import AddTodoForm from '../../components/Todo/AddTodoForm';

interface CreateTodoData {
  title: string;
  description: string;
  dueDate: string;
  priority: string;
  user_id: string;
  image?: string | null;
}

export default function AddTodo() {
  const router = useRouter();
  const [error, setError] = useState<string | null>(null);

  const handleSubmit = async (todoData: FormData) => {
    try {
      const token = localStorage.getItem('token');
      const userId = localStorage.getItem('id');
      
      if (!token || !userId) {
        setError('No authentication token or user ID found');
        router.push('/login');
        return;
      }

      // Log the received form data
      console.log('Received form data:', {
        title: todoData.get('title'),
        description: todoData.get('description'),
        dueDate: todoData.get('due_date'),
        priority: todoData.get('priority')
      });

      // Create todo data
      const createTodoData = {
        title: todoData.get('title'),
        description: todoData.get('description'),
        dueDate: new Date(todoData.get('due_date') as string).toISOString().split('T')[0],
        priority: todoData.get('priority'),
        user_id: userId,
        image: todoData.get('image') || null
      };

      // Additional validation
      if (!createTodoData.dueDate) {
        setError('Due date is required');
        return;
      }

      console.log('Image data being sent:', 
        typeof createTodoData.image === 'string' 
          ? createTodoData.image.substring(0, 50) + '...' 
          : 'No image'
      );

      const response = await fetch('http://localhost:8000/api/todos', {
        method: 'POST',
        headers: {
          'Authorization': `Bearer ${token}`,
          'Content-Type': 'application/json',
          'Accept': 'application/json',
        },
        body: JSON.stringify(createTodoData)
      });

      const responseData = await response.json();

      if (!response.ok) {
        throw new Error(responseData.error || responseData.message || 'Failed to create todo');
      }

      await router.push('/dashboard');
    } catch (error) {
      if (error instanceof Error) {
        setError(error.message);
      } else {
        setError('An unexpected error occurred');
      }
      console.error('Error creating todo:', error);
    }
  };

  return (
    <div className="container mx-auto p-4">
      <h1 className="text-2xl font-bold mb-4">Create a New Todo</h1>
      {error && (
        <div className="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
          {error}
        </div>
      )}
      <AddTodoForm onSubmit={handleSubmit} />
    </div>
  );
} 