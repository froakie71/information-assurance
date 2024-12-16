import { Todo } from '../../types/todo';
import Link from 'next/link';

interface TodoCardProps {
  todo: Todo;
  onToggle: (id: number) => Promise<void>;
  onDelete: (id: number) => Promise<void>;
}

export default function TodoCard({ todo, onToggle, onDelete }: TodoCardProps) {
  return (
    <div className="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow duration-200 p-6">
      <div className="flex items-start justify-between">
        <div className="flex items-start space-x-4 flex-grow">
          <input
            type="checkbox"
            checked={todo.is_completed}
            onChange={() => onToggle(todo.id)}
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
                Due: {new Date(todo.due_date).toLocaleDateString()}
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
              Edit
            </Link>
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