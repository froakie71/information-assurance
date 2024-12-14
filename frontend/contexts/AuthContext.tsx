import { createContext, useContext, useState, useEffect, ReactNode } from 'react';
import axios from 'axios';

interface AuthContextType {
  user: any;
  login: (email: string, password: string) => Promise<void>;
  register: (name: string, email: string, password: string) => Promise<void>;
  logout: () => Promise<void>;
  isLoading: boolean;
}

const AuthContext = createContext<AuthContextType | undefined>(undefined);

export function AuthProvider({ children }: { children: ReactNode }) {
  const [user, setUser] = useState(null);
  const [isLoading, setIsLoading] = useState(true);

  useEffect(() => {
    checkAuth();
  }, []);

  const checkAuth = async () => {
    try {
      const token = localStorage.getItem('token');
      if (token) {
        axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
        const { data } = await axios.get('http://localhost:8000/api/user');
        setUser(data);
      }
    } catch (error) {
      localStorage.removeItem('token');
    }
    setIsLoading(false);
  };

  const login = async (email: string, password: string) => {
    const { data } = await axios.post('http://localhost:8000/api/login', {
      email,
      password,
    });

    localStorage.setItem('token', data.token);
    localStorage.setItem('id', data?.user?.id);
    axios.defaults.headers.common['Authorization'] = `Bearer ${data.token}`;
    setUser(data.user);
  };

  const register = async (name: string, email: string, password: string) => {
    const { data } = await axios.post('http://localhost:8000/api/register', {
      name,
      email,
      password,
      password_confirmation: password,
    });
    localStorage.setItem('token', data.token);
    axios.defaults.headers.common['Authorization'] = `Bearer ${data.token}`;
    setUser(data.user);
  };

  const logout = async () => {
    await axios.post('http://localhost:8000/api/logout');
    localStorage.removeItem('token');
    localStorage.removeItem('id');
    delete axios.defaults.headers.common['Authorization'];
    setUser(null);
  };

  return (
    <AuthContext.Provider value={{ user, login, register, logout, isLoading }}>
      {children}
    </AuthContext.Provider>
  );
}

export function useAuth() {
  const context = useContext(AuthContext);
  if (context === undefined) {
    throw new Error('useAuth must be used within an AuthProvider');
  }
  return context;
} 