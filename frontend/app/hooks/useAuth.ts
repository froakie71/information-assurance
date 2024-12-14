import { useState, useEffect, useCallback } from 'react';
import { useRouter } from 'next/navigation';

interface User {
    id: number;
    name: string;
    email: string;
    role?: string;
}

interface AuthResponse {
    user: User;
    token: string;
}

interface LoginCredentials {
    email: string;
    password: string;
}

interface RegisterCredentials extends LoginCredentials {
    name: string;
    password_confirmation: string;
}

export const useAuth = () => {
    const [user, setUser] = useState<User | null>(null);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState<string | null>(null);
    const router = useRouter();

    // API URLs
    const API_URL = process.env.NEXT_PUBLIC_API_URL || 'http://localhost:8000';
    const LOGIN_URL = `${API_URL}/api/login`;
    const REGISTER_URL = `${API_URL}/api/register`;
    const LOGOUT_URL = `${API_URL}/api/logout`;
    const USER_URL = `${API_URL}/api/user`;

    // Default fetch options
    const defaultHeaders = {
        'Content-Type': 'application/json',
        'Accept': 'application/json'
    };

    // Set auth token in headers
    const setAuthToken = (token: string) => {
        if (token) {
            localStorage.setItem('token', token);
        } else {
            localStorage.removeItem('token');
        }
    };

    // Get headers with auth token
    const getHeaders = () => {
        const token = localStorage.getItem('token');
        return {
            ...defaultHeaders,
            ...(token ? { 'Authorization': `Bearer ${token}` } : {})
        };
    };

    // Check if user is authenticated
    const checkAuth = useCallback(async () => {
        try {
            const token = localStorage.getItem('token');
            if (token) {
                const response = await fetch(USER_URL, {
                    method: 'GET',
                    headers: getHeaders(),
                    credentials: 'include'
                });

                if (!response.ok) {
                    throw new Error('Authentication failed');
                }

                const userData = await response.json();
                setUser(userData);
            }
        } catch (err) {
            setUser(null);
            setAuthToken('');
        } finally {
            setLoading(false);
        }
    }, []);

    // Login function
    const login = async (credentials: LoginCredentials) => {
        try {
            setError(null);
            setLoading(true);
            
            const response = await fetch(LOGIN_URL, {
                method: 'POST',
                headers: defaultHeaders,
                credentials: 'include',
                body: JSON.stringify(credentials)
            });

            if (!response.ok) {
                const errorData = await response.json();
                throw new Error(errorData.message || 'Login failed');
            }

            const data: AuthResponse = await response.json();
            setAuthToken(data.token);
            setUser(data.user);
            router.push('/dashboard');
            return data;
        } catch (err: any) {
            setError(err.message || 'An error occurred during login');
            throw err;
        } finally {
            setLoading(false);
        }
    };

    // Register function
    const register = async (credentials: RegisterCredentials) => {
        try {
            setError(null);
            setLoading(true);

            const response = await fetch(REGISTER_URL, {
                method: 'POST',
                headers: defaultHeaders,
                credentials: 'include',
                body: JSON.stringify(credentials)
            });

            if (!response.ok) {
                const errorData = await response.json();
                throw new Error(errorData.message || 'Registration failed');
            }

            const data: AuthResponse = await response.json();
            setAuthToken(data.token);
            setUser(data.user);
            router.push('/dashboard');
            return data;
        } catch (err: any) {
            setError(err.message || 'An error occurred during registration');
            throw err;
        } finally {
            setLoading(false);
        }
    };

    // Logout function
    const logout = async () => {
        try {
            setLoading(true);
            const response = await fetch(LOGOUT_URL, {
                method: 'POST',
                headers: getHeaders(),
                credentials: 'include'
            });

            if (!response.ok) {
                throw new Error('Logout failed');
            }

            setUser(null);
            setAuthToken('');
            router.push('/login');
        } catch (err: any) {
            setError(err.message || 'An error occurred during logout');
        } finally {
            setLoading(false);
        }
    };

    // Check authentication status on mount
    useEffect(() => {
        checkAuth();
    }, [checkAuth]);

    return {
        user,
        loading,
        error,
        login,
        register,
        logout,
        isAuthenticated: !!user,
    };
};

export default useAuth;
