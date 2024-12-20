import { useEffect, useState } from 'react';
import Image from 'next/image';

interface AddTodoFormProps {
  onSubmit: (data: FormData) => void;
}

export default function AddTodoForm({ onSubmit }: AddTodoFormProps) {
  const [image, setImage] = useState<string | null>(null);
  const [preview, setPreview] = useState<string>('');
  const [dueDate, setDueDate] = useState(new Date().toISOString().split('T')[0]);

  const handleSubmit = async (e: React.FormEvent<HTMLFormElement>) => {
    e.preventDefault();
    const formData = new FormData();
    
    const form = e.currentTarget;
    formData.append('title', (form.querySelector('[name="title"]') as HTMLInputElement).value);
    formData.append('description', (form.querySelector('[name="description"]') as HTMLTextAreaElement).value);
    formData.append('dueDate', dueDate);
    formData.append('priority', (form.querySelector('[name="priority"]') as HTMLSelectElement).value);
    
    if (image) {
      formData.append('image', image);
    }
    
    onSubmit(formData);
  };

  const handleImageChange = async (e: React.ChangeEvent<HTMLInputElement>) => {
    const file = e.target.files?.[0];
    if (file) {
      const reader = new FileReader();
      reader.onloadend = () => {
        const base64String = reader.result as string;
        setImage(base64String);
        setPreview(base64String);
      };
      reader.readAsDataURL(file);
    }
  };

  const handleDateChange = (e: React.ChangeEvent<HTMLInputElement>) => {
    setDueDate(e.target.value);
  };

  return (
    <form onSubmit={handleSubmit} className="max-w-lg mx-auto" encType="multipart/form-data">
      <div className="mb-4">
        <label className="block mb-2">Title</label>
        <input
          type="text"
          name="title"
          required
          className="w-full p-2 border rounded"
          placeholder="Todo title"
        />
      </div>

      <div className="mb-4">
        <label className="block mb-2">Description</label>
        <textarea
          name="description"
          required
          className="w-full p-2 border rounded"
          placeholder="Describe your todo"
        />
      </div>

      <div className="grid grid-cols-2 gap-4 mb-4">
        <div>
          <label className="block mb-2">Due Date</label>
          <input
            type="date"
            name="due_date"
            value={dueDate}
            onChange={(e) => setDueDate(e.target.value)}
            required
            className="w-full p-2 border rounded"
          />
        </div>
        <div>
          <label className="block mb-2">Priority</label>
          <select 
            name="priority" 
            required
            defaultValue="Medium"
            className="w-full p-2 border rounded"
          >
            <option value="Low">Low</option>
            <option value="Medium">Medium</option>
            <option value="High">High</option>
          </select>
        </div>
      </div>

      <div className="mb-4">
        <label className="block mb-2">Attachment</label>
        <input
          type="file"
          name="image"
          onChange={handleImageChange}
          accept="image/*"
          className="w-full p-2 border rounded"
        />
        {preview && (
          <div className="mt-2">
            <Image src={preview} alt="Preview" width={200} height={200} />
          </div>
        )}
      </div>

      <div className="flex justify-end gap-4">
        <button
          type="button"
          onClick={() => window.history.back()}
          className="px-4 py-2 border rounded"
        >
          Cancel
        </button>
        <button
          type="submit"
          className="px-4 py-2 bg-purple-600 text-white rounded"
        >
          Create Todo
        </button>
      </div>
    </form>
  );
} 