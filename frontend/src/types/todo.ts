export interface Todo {
  id: number;
  owner_id: number;
  title: string;
  description: string;
  due_date: string;
  priority: string;
  image: string | null;
  is_completed: boolean;
  created_at: string;
  updated_at: string;
}