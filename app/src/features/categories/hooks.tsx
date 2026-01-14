import { useQuery, useMutation, useQueryClient } from '@tanstack/react-query';
import { categoryApi } from './api';
import { CreateProductCategoryRequest, UpdateProductCategoryRequest } from './types';

export const useCategoriesQuery = () => {
  return useQuery({
    queryKey: ['categories'],
    queryFn: categoryApi.getAll,
  });
};

export const useCategoryQuery = (id: number) => {
  return useQuery({
    queryKey: ['categories', id],
    queryFn: () => categoryApi.getById(id),
    enabled: !!id,
  });
};

export const useCreateCategoryMutation = () => {
  const queryClient = useQueryClient();
  
  return useMutation({
    mutationFn: (data: CreateProductCategoryRequest) => categoryApi.create(data),
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ['categories'] });
    },
  });
};

export const useUpdateCategoryMutation = () => {
  const queryClient = useQueryClient();
  
  return useMutation({
    mutationFn: ({ id, data }: { id: number; data: UpdateProductCategoryRequest }) =>
      categoryApi.update(id, data),
    onSuccess: (_, variables) => {
      queryClient.invalidateQueries({ queryKey: ['categories'] });
      queryClient.invalidateQueries({ queryKey: ['categories', variables.id] });
    },
  });
};

export const useDeleteCategoryMutation = () => {
  const queryClient = useQueryClient();
  
  return useMutation({
    mutationFn: (id: number) => categoryApi.delete(id),
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ['categories'] });
    },
  });
};
