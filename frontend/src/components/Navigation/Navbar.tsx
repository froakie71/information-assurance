import { useRouter } from 'next/router';
import Link from 'next/link';
import { useEffect } from 'react';
import { useState } from 'react';

export default function Navbar() {
  const router = useRouter();
  // const userName = "John Doe"; // Replace with actual user data
  const [userName, setUserName] = useState('');
  useEffect(() => {
    setUserName(localStorage.getItem('name') || '');
  }, []);

  const handleLogout = () => {
    localStorage.removeItem('token');
    localStorage.removeItem('id');
    router.push('/login');
  };

  return (
    <nav className="bg-white shadow-lg">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div className="flex justify-between h-16">
          <div className="flex items-center">
            <span className="text-xl font-semibold text-gray-800">
              {userName} enter
            </span>
          </div>

          <div className="flex items-center space-x-4">
            <Link
              href="/dashboard/add-todo"
              className="p-2 rounded-full hover:bg-gray-100"
            >
              <svg
                className="w-6 h-6 text-gray-600"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
              >
                <path
                  strokeLinecap="round"
                  strokeLinejoin="round"
                  strokeWidth={2}
                  d="M12 4v16m8-8H4"
                />
              </svg>
            </Link>

            <Link
              href="/history"
              className="px-4 py-2 text-gray-600 hover:text-gray-800 rounded-md hover:bg-gray-100"
            >
              History
            </Link>

            <button
              onClick={() => router.push('/auth/login')}
              className="flex items-center px-4 py-2 text-gray-600 hover:text-gray-800"
            >
              <svg
                className="w-5 h-5 mr-2"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
              >
                <path
                  strokeLinecap="round"
                  strokeLinejoin="round"
                  strokeWidth={2}
                  d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"
                />
              </svg>
              Logout
            </button>
          </div>
        </div>
      </div>
    </nav>
  );
} 