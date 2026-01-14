import { ProductCategory, CreateProductCategoryRequest, UpdateProductCategoryRequest } from './types';

const API_URL = 'http://localhost:8000/api';

interface ApiResponse<T> {
  success: boolean;
  message: string;
  data: T | null;
  errors: Record<string, string[]> | null;
}

export const categoryApi = {
  async getAll(): Promise<ProductCategory[]> {
    const response = await fetch(`${API_URL}/categories`);
    const result: ApiResponse<ProductCategory[]> = await response.json();
    
    if (!result.success || !result.data) {
      throw new Error(result.message || 'Failed to fetch categories');
    }
    
    return result.data;
  },

  async getById(id: number): Promise<ProductCategory> {
    const response = await fetch(`${API_URL}/categories/${id}`);
    const result: ApiResponse<ProductCategory> = await response.json();
    
    if (!result.success || !result.data) {
      throw new Error(result.message || 'Failed to fetch category');
    }
    
    return result.data;
  },

  async create(data: CreateProductCategoryRequest): Promise<ProductCategory> {
    const response = await fetch(`${API_URL}/categories`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify(data),
      credentials: 'include',
    });
    
    const result: ApiResponse<ProductCategory> = await response.json();
    
    if (!result.success || !result.data) {
      if (result.errors) {
        const errorMessages = Object.values(result.errors).flat().join(', ');
        throw new Error(errorMessages);
      }
      throw new Error(result.message || 'Failed to create category');
    }
    
    return result.data;
  },

  async update(id: number, data: UpdateProductCategoryRequest): Promise<ProductCategory> {
    const response = await fetch(`${API_URL}/categories/${id}`, {
      method: 'PUT',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify(data),
      credentials: 'include',
    });
    
    const result: ApiResponse<ProductCategory> = await response.json();
    
    if (!result.success || !result.data) {
      if (result.errors) {
        const errorMessages = Object.values(result.errors).flat().join(', ');
        throw new Error(errorMessages);
      }
      throw new Error(result.message || 'Failed to update category');
    }
    
    return result.data;
  },

  async delete(id: number): Promise<void> {
    const response = await fetch(`${API_URL}/categories/${id}`, {
      method: 'DELETE',
      credentials: 'include',
    });
    
    const result: ApiResponse<{ message: string }> = await response.json();
    
    if (!result.success) {
      throw new Error(result.message || 'Failed to delete category');
    }
  },
};
