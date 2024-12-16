interface StatsCardProps {
  stats: {
    total: number;
    completed: number;
    pending: number;
  };
}

export default function StatsCard({ stats }: StatsCardProps) {
  return (
    <div className="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
      <div className="bg-white rounded-lg shadow p-6">
        <h3 className="text-gray-500">Total Tasks</h3>
        <p className="text-3xl font-bold">{stats.total}</p>
      </div>
      <div className="bg-white rounded-lg shadow p-6">
        <h3 className="text-gray-500">Completed Tasks</h3>
        <p className="text-3xl font-bold">{stats.completed}</p>
      </div>
      <div className="bg-white rounded-lg shadow p-6">
        <h3 className="text-gray-500">Pending Tasks</h3>
        <p className="text-3xl font-bold">{stats.pending}</p>
      </div>
    </div>
  );
} 