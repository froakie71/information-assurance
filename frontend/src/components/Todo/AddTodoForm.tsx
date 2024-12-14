import { useEffect, useState } from 'react';
import Image from 'next/image';

interface AddTodoFormProps {
  onSubmit: (data: FormData) => void;
}

export default function AddTodoForm({ onSubmit }: AddTodoFormProps) {
  const [image, setImage] = useState<File | null>(null);
  const [preview, setPreview] = useState<string>('');
  const [ownerId, setOwnerId] = useState<string>('');

  useEffect(() => {
    const id = localStorage.getItem('id');
    if (id) {
      setOwnerId(id);
    }
  }, []);

  const handleSubmit = (e: React.FormEvent<HTMLFormElement>) => {
    e.preventDefault();
    const formData = new FormData(e.currentTarget);
    if (image) {
      formData.append('image', image);
    }
    onSubmit(formData);
  };



  const handleImageChange = (e: React.ChangeEvent<HTMLInputElement>) => {
    const file = e.target.files?.[0];
    if (file) {
      setImage(file);
      setPreview(URL.createObjectURL(file));
    }
  };

  return (
    <form onSubmit={handleSubmit} className="max-w-lg mx-auto">
      <input type="hidden" name="owner_id" value={ownerId || ''} />
      <div className="mb-4">
        <label className="block mb-2">Title</label>
        <input
          type="text"
          name="title"

          className="w-full p-2 border rounded"
          placeholder="Todo title"
        />
      </div>

      <div className="mb-4">
        <label className="block mb-2">Description</label>
        <textarea
          name="description"

          className="w-full p-2 border rounded"
          placeholder="Describe your todo"
        />
      </div>

      <div className="grid grid-cols-2 gap-4 mb-4">
        <div>
          <label className="block mb-2">Due Date</label>
          <input
            type="date"
            name="dueDate"

            className="w-full p-2 border rounded"
          />
        </div>
        <div>
          <label className="block mb-2">Priority</label>
          <select name="priority" className="w-full p-2 border rounded">
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