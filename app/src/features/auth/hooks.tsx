// Custom hooks for authentication

import { createContext, useContext, useState, useEffect, ReactNode } from 'react';
import { authApi } from './api';
import { authStorage } from '../../services/authStorage';
import type { AuthUser, LoginCredentials, AuthContextType } from './types';

const AuthContext = createContext<AuthContextType | undefined>(undefined);

export function AuthProvider({ children }: { children: ReactNode }) {
  const [user, setUser] = useState<AuthUser | null>(authStorage.getUser());
  const [isLoading, setIsLoading] = useState(true);

  const checkAuth = async () => {
    try {
      const userData = await authApi.checkAuth();
      const authUser: AuthUser = {
        id: userData.id,
        name: userData.name,
        login: userData.login,
        role: userData.role,
      };
      setUser(authUser);
      authStorage.setUser(authUser);
    } catch (error) {
      setUser(null);
      authStorage.clearUser();
    } finally {
      setIsLoading(false);
    }
  };

  const login = async (credentials: LoginCredentials) => {
    const userData = await authApi.login(credentials);
    const authUser: AuthUser = {
      id: userData.id,
      name: userData.name,
      login: userData.login,
      role: userData.role,
    };
    setUser(authUser);
    authStorage.setUser(authUser);
  };

  const logout = async () => {
    try {
      await authApi.logout();
    } finally {
      setUser(null);
      authStorage.clearUser();
    }
  };

  useEffect(() => {
    checkAuth();
  }, []);

  const contextValue: AuthContextType = {
    user,
    isAuthenticated: !!user,
    isLoading,
    login,
    logout,
    checkAuth,
  };

  return (
    <AuthContext.Provider value={contextValue}>
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
