// API functions for authentication

import { apiClient } from '../../services/apiClient';
import type { LoginCredentials, LoginResponse } from './types';

export const authApi = {
  login: async (credentials: LoginCredentials): Promise<LoginResponse> => {
    const response = await apiClient.post<LoginResponse>(
      '/auth/login',
      credentials
    );
    return response.data!;
  },

  logout: async (): Promise<void> => {
    await apiClient.post('/auth/logout');
  },

  checkAuth: async (): Promise<LoginResponse> => {
    const response = await apiClient.get<LoginResponse>('/auth/check');
    return response.data!;
  },
};
