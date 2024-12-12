import { useState } from 'react';
import { useRouter } from 'next/router';
import AddTodoForm from '../../components/Todo/AddTodoForm';

export default function AddTodo() {
  const router = useRouter();
  
  const handleSubmit = async (todoData: FormData) => {
    try {
      const response = await fetch('http://localhost:8000/api/todos', {
        method: 'POST',
        headers: {
          'Accept': 'application/json',
        },
        body: todoData,
        credentials: 'include'
      });
      
      if (response.ok) {
        router.push('/dashboard');
      } else {
        const error = await response.json();
        console.error('Server error:', error);
      }
    } catch (error) {
      console.error('Error creating todo:', error);
    }
  };

  return (
    <div className="container mx-auto p-4">
      <h1 className="text-2xl font-bold mb-4">Create a New Todo</h1>
      <AddTodoForm onSubmit={handleSubmit} />
    </div>
  );
} 