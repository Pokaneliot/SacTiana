// Type definitions for authentication

export interface LoginCredentials {
  login: string;
  password: string;
}

export interface AuthUser {
  id: number;
  name: string;
  login: string;
  role: 'ROLE_ADMIN' | 'ROLE_USER';
}

export interface LoginResponse {
  id: number;
  name: string;
  login: string;
  role: 'ROLE_ADMIN' | 'ROLE_USER';
}

export interface AuthContextType {
  user: AuthUser | null;
  isAuthenticated: boolean;
  isLoading: boolean;
  login: (credentials: LoginCredentials) => Promise<void>;
  logout: () => Promise<void>;
  checkAuth: () => Promise<void>;
}
