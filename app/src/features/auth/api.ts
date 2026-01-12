// API functions for authentication

import { apiClient } from '../../services/apiClient';
import type { LoginCredentials, LoginResponse } from './types';

export const authApi = {
  login: async (credentials: LoginCredentials): Promise<LoginResponse> => {
    const response = await apiClient.post<LoginResponse>(
      '/api/auth/login',
      credentials
    );
    return response.data!;
  },

  logout: async (): Promise<void> => {
    await apiClient.post('/api/auth/logout');
  },

  checkAuth: async (): Promise<LoginResponse> => {
    const response = await apiClient.get<LoginResponse>('/api/auth/check');
    return response.data!;
  },
};
