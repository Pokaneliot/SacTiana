import { ProductCategory, CreateProductCategoryRequest, UpdateProductCategoryRequest } from './types';
import { apiClient } from '../../services/apiClient';

export const categoryApi = {
  async getAll(): Promise<ProductCategory[]> {
    const response = await apiClient.get<ProductCategory[]>('/categories');
    return response.data || [];
  },

  async getById(id: number): Promise<ProductCategory> {
    const response = await apiClient.get<ProductCategory>(`/categories/${id}`);
    if (!response.data) {
      throw new Error('Category not found');
    }
    return response.data;
  },

  async create(data: CreateProductCategoryRequest): Promise<ProductCategory> {
    const response = await apiClient.post<ProductCategory>('/categories', data);
    if (!response.data) {
      throw new Error('Failed to create category');
    }
    return response.data;
  },

  async update(id: number, data: UpdateProductCategoryRequest): Promise<ProductCategory> {
    const response = await apiClient.put<ProductCategory>(`/categories/${id}`, data);
    if (!response.data) {
      throw new Error('Failed to update category');
    }
    return response.data;
  },

  async delete(id: number): Promise<void> {
    await apiClient.delete(`/categories/${id}`);
  },
};
