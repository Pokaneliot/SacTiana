// Session-based authentication storage (stateful)
// Since we're using Symfony sessions, we don't store tokens client-side
// The session cookie is automatically managed by the browser

export interface AuthUser {
  id: number;
  name: string;
  login: string;
  role: 'ROLE_ADMIN' | 'ROLE_USER';
}

const AUTH_USER_KEY = 'auth_user';

export const authStorage = {
  // Store user info in localStorage for quick access (optional)
  setUser: (user: AuthUser | null): void => {
    if (user) {
      localStorage.setItem(AUTH_USER_KEY, JSON.stringify(user));
    } else {
      localStorage.removeItem(AUTH_USER_KEY);
    }
  },

  getUser: (): AuthUser | null => {
    const userStr = localStorage.getItem(AUTH_USER_KEY);
    if (!userStr) return null;
    
    try {
      return JSON.parse(userStr) as AuthUser;
    } catch {
      return null;
    }
  },

  clearUser: (): void => {
    localStorage.removeItem(AUTH_USER_KEY);
  },

  isAuthenticated: (): boolean => {
    return authStorage.getUser() !== null;
  },

  hasRole: (role: 'ROLE_ADMIN' | 'ROLE_USER'): boolean => {
    const user = authStorage.getUser();
    return user?.role === role;
  },

  isAdmin: (): boolean => {
    return authStorage.hasRole('ROLE_ADMIN');
  },
};
