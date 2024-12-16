import { Todo } from '../types/todo';
import Image from 'next/image';
import { useRouter } from 'next/router';

interface Props {
  todo: Todo;
  onToggle: (id: number) => void;
  onRefresh: () => void;
}

export default function TodoCard({ todo, onToggle, onRefresh }: Props) {
  const router = useRouter();

  const handleDelete = async () => {
    if (!confirm('Are you sure you want to delete this todo?')) return;

    try {
      const token = localStorage.getItem('token');
      const response = await fetch(`http://localhost:8000/api/todos/${todo.id}`, {
        method: 'DELETE',
        headers: {
          'Authorization': `Bearer ${token}`,
          'Content-Type': 'application/json'
        }
      });

      if (response.ok) {
        onRefresh();
      }
    } catch (error) {
      console.error('Error deleting todo:', error);
    }
  };

  return (
    <div className="bg-white p-4 rounded-lg shadow">
      <div className="flex items-start gap-4">
        {todo.image && (
          <Image
            src={todo.image}
            alt={todo.title}
            width={100}
            height={100}
            className="rounded-md object-cover"
          />
        )}
        <div className="flex-1">
          <div className="flex items-center gap-2">
            <input
              type="checkbox"
              checked={todo.is_completed}
              onChange={() => onToggle(todo.id)}
              className="h-4 w-4"
            />
            <h3 className={`font-medium ${todo.is_completed ? 'line-through text-gray-500' : ''}`}>
              {todo.title}
            </h3>
          </div>
          <p className="text-gray-600 mt-1">{todo.description}</p>
          <div className="flex items-center gap-4 mt-2">
            <span className="text-sm text-gray-500">
              {new Date(todo.due_date).toLocaleDateString()}
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
        <div className="flex gap-2">
          <button
            onClick={() => router.push(`/edit/${todo.id}`)}
            className="text-blue-600 hover:text-blue-800"
          >
            Edit
          </button>
          <button
            onClick={handleDelete}
            className="text-red-600 hover:text-red-800"
          >
            Delete
          </button>
        </div>
      </div>
    </div>
  );
}