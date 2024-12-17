import { useRouter } from 'next/router';

interface Props {
  todo: {
    id: number;
    title: string;
    description: string;
    due_date: string;
    priority: string;
    is_completed: boolean;
    image: string | null;
  };
  onToggle: (id: number) => void;
  onDelete: (id: number) => void;
}

export default function TodoCard({ todo, onToggle, onDelete }: Props) {
  const router = useRouter();

  return (
    <div className="bg-white p-4 rounded-lg shadow">
      <div className="flex items-start gap-4">
        {todo.image && (
          <img
            src={todo.image}
            alt={todo.title}
            className="w-24 h-24 object-cover rounded-lg"
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
              todo.priority === 'High' ? 'bg-red-100 text-red-800' :
              todo.priority === 'Medium' ? 'bg-yellow-100 text-yellow-800' :
              'bg-green-100 text-green-800'
            }`}>
              {todo.priority}
            </span>
          </div>
          <div className="flex gap-2 mt-2">
            <button
              onClick={() => router.push(`/dashboard/edit-todo/${todo.id}`)}
              className="text-blue-600 hover:text-blue-800"
            >
              Edit
            </button>
            <button
              onClick={() => onDelete(todo.id)}
              className="text-red-600 hover:text-red-800"
            >
              Delete
            </button>
          </div>
        </div>
      </div>
    </div>
  );
} 