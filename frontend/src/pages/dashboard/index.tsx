import TodoList from '../../components/Todo/TodoList';
import Navbar from '../../components/Navigation/Navbar';

export default function Dashboard() {
  return (
    <div className="min-h-screen bg-gray-50">
      <Navbar />
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        {/* Stats Cards */}
        <div className="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
          <div className="bg-white rounded-lg shadow p-6">
            <h3 className="text-gray-500">Total Tasks</h3>
            <p className="text-3xl font-bold">24</p>
          </div>
          <div className="bg-white rounded-lg shadow p-6">
            <h3 className="text-gray-500">Completed Tasks</h3>
            <p className="text-3xl font-bold">18</p>
          </div>
          <div className="bg-white rounded-lg shadow p-6">
            <h3 className="text-gray-500">Pending Tasks</h3>
            <p className="text-3xl font-bold">6</p>
          </div>
        </div>

        {/* Graph Section */}
        <div className="bg-white rounded-lg shadow p-6 mb-8">
          <h3 className="text-lg font-semibold mb-4">Weekly Todo Statistics</h3>
          {/* Your graph component here */}
        </div>

        {/* Todo List */}
        <TodoList />
      </div>
    </div>
  );
} 