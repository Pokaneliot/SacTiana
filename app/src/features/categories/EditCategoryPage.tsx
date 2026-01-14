import { useNavigate, useParams } from 'react-router-dom';
import { useForm } from 'react-hook-form';
import { zodResolver } from '@hookform/resolvers/zod';
import { z } from 'zod';
import { useCategoryQuery, useUpdateCategoryMutation } from './hooks';
import './CreateCategoryPage.css';
import { useEffect } from 'react';

const categorySchema = z.object({
  name: z.string().min(1, 'Name is required').max(50, 'Name must be less than 50 characters'),
});

type CategoryFormData = z.infer<typeof categorySchema>;

export function EditCategoryPage() {
  const { id } = useParams<{ id: string }>();
  const navigate = useNavigate();
  const categoryId = parseInt(id || '0');
  
  const { data: category, isLoading } = useCategoryQuery(categoryId);
  const updateMutation = useUpdateCategoryMutation();
  
  const {
    register,
    handleSubmit,
    formState: { errors, isSubmitting },
    reset,
  } = useForm<CategoryFormData>({
    resolver: zodResolver(categorySchema),
  });

  useEffect(() => {
    if (category) {
      reset({
        name: category.name,
      });
    }
  }, [category, reset]);

  const onSubmit = async (data: CategoryFormData) => {
    try {
      await updateMutation.mutateAsync({
        id: categoryId,
        data,
      });
      navigate('/categories');
    } catch (error) {
      console.error('Failed to update category:', error);
    }
  };

  if (isLoading) {
    return (
      <div className="edit-category-page">
        <div className="loading-state">Loading category...</div>
      </div>
    );
  }

  if (!category) {
    return (
      <div className="edit-category-page">
        <div className="error-message">Category not found</div>
      </div>
    );
  }

  return (
    <div className="edit-category-page">
      <div className="form-container">
        <div className="form-header">
          <h1>Edit Category</h1>
          <button
            type="button"
            className="btn btn-secondary"
            onClick={() => navigate('/categories')}
          >
            Cancel
          </button>
        </div>

        {updateMutation.isError && (
          <div className="error-message">
            {updateMutation.error instanceof Error
              ? updateMutation.error.message
              : 'Failed to update category'}
          </div>
        )}

        <form onSubmit={handleSubmit(onSubmit)} className="category-form">
          <div className="form-group">
            <label htmlFor="name">
              Category Name <span className="required">*</span>
            </label>
            <input
              id="name"
              type="text"
              {...register('name')}
              className={errors.name ? 'error' : ''}
              placeholder="Enter category name"
            />
            {errors.name && (
              <span className="error-text">{errors.name.message}</span>
            )}
          </div>

          <div className="form-actions">
            <button
              type="button"
              className="btn btn-secondary"
              onClick={() => navigate('/categories')}
            >
              Cancel
            </button>
            <button
              type="submit"
              className="btn btn-primary"
              disabled={isSubmitting || updateMutation.isPending}
            >
              {isSubmitting || updateMutation.isPending ? 'Updating...' : 'Update Category'}
            </button>
          </div>
        </form>
      </div>
    </div>
  );
}
