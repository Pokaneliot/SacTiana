import { useQuery, useMutation, useQueryClient } from '@tanstack/react-query';
import { productApi } from './api';
import type {
  Product,
  CreateProductRequest,
  UpdateProductRequest,
  ProductFilter,
} from './types';

const PRODUCTS_QUERY_KEY = 'products';

/**
 * Hook to fetch all products with optional filters
 */
export function useProducts(filters?: ProductFilter) {
  return useQuery<Product[], Error>({
    queryKey: [PRODUCTS_QUERY_KEY, filters],
    queryFn: () => productApi.getProducts(filters),
  });
}

/**
 * Hook to fetch a single product by ID
 */
export function useProduct(id: number) {
  return useQuery<Product, Error>({
    queryKey: [PRODUCTS_QUERY_KEY, id],
    queryFn: () => productApi.getProduct(id),
    enabled: !!id,
  });
}

/**
 * Hook to create a new product
 */
export function useCreateProduct() {
  const queryClient = useQueryClient();

  return useMutation<Product, Error, CreateProductRequest>({
    mutationFn: productApi.createProduct,
    onSuccess: () => {
      // Invalidate products list to refetch
      queryClient.invalidateQueries({ queryKey: [PRODUCTS_QUERY_KEY] });
    },
  });
}

/**
 * Hook to update a product
 */
export function useUpdateProduct() {
  const queryClient = useQueryClient();

  return useMutation<Product, Error, { id: number; data: UpdateProductRequest }>({
    mutationFn: ({ id, data }) => productApi.updateProduct(id, data),
    onSuccess: (data) => {
      // Invalidate both the list and the specific product
      queryClient.invalidateQueries({ queryKey: [PRODUCTS_QUERY_KEY] });
      queryClient.invalidateQueries({ queryKey: [PRODUCTS_QUERY_KEY, data.id] });
    },
  });
}

/**
 * Hook to delete a product
 */
export function useDeleteProduct() {
  const queryClient = useQueryClient();

  return useMutation<void, Error, number>({
    mutationFn: productApi.deleteProduct,
    onSuccess: () => {
      // Invalidate products list to refetch
      queryClient.invalidateQueries({ queryKey: [PRODUCTS_QUERY_KEY] });
    },
  });
}
