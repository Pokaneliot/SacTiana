import { apiClient } from '../../services/apiClient';
import type {
  Product,
  CreateProductRequest,
  UpdateProductRequest,
  ProductFilter,
} from './types';

export const productApi = {
  /**
   * Get all products with optional filters
   */
  async getProducts(filters?: ProductFilter): Promise<Product[]> {
    const params = new URLSearchParams();
    
    if (filters?.ref) params.append('ref', filters.ref);
    if (filters?.sellingPrice) params.append('sellingPrice', filters.sellingPrice.toString());
    if (filters?.createdAt) params.append('createdAt', filters.createdAt);
    if (filters?.categoryId) params.append('categoryId', filters.categoryId.toString());

    const queryString = params.toString();
    const url = `/products${queryString ? `?${queryString}` : ''}`;
    
    const response = await apiClient.get<Product[]>(url);
    return response.data || [];
  },

  /**
   * Get a single product by ID
   */
  async getProduct(id: number): Promise<Product> {
    const response = await apiClient.get<Product>(`/products/${id}`);
    if (!response.data) {
      throw new Error('Product not found');
    }
    return response.data;
  },

  /**
   * Create a new product
   */
  async createProduct(data: CreateProductRequest): Promise<Product> {
    const response = await apiClient.post<Product>('/products', data);
    if (!response.data) {
      throw new Error('Failed to create product');
    }
    return response.data;
  },

  /**
   * Update an existing product
   */
  async updateProduct(id: number, data: UpdateProductRequest): Promise<Product> {
    const response = await apiClient.put<Product>(`/products/${id}`, data);
    if (!response.data) {
      throw new Error('Failed to update product');
    }
    return response.data;
  },

  /**
   * Delete a product
   */
  async deleteProduct(id: number): Promise<void> {
    await apiClient.delete(`/products/${id}`);
  },
};
