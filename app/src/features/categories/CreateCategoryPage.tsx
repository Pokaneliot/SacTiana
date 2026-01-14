import { useNavigate } from 'react-router-dom';
import { useForm } from 'react-hook-form';
import { zodResolver } from '@hookform/resolvers/zod';
import { z } from 'zod';
import { useCreateCategoryMutation } from './hooks';
import './CreateCategoryPage.css';

const categorySchema = z.object({
  name: z.string().min(1, 'Name is required').max(50, 'Name must be less than 50 characters'),
});

type CategoryFormData = z.infer<typeof categorySchema>;

export function CreateCategoryPage() {
  const navigate = useNavigate();
  const createMutation = useCreateCategoryMutation();
  
  const {
    register,
    handleSubmit,
    formState: { errors, isSubmitting },
  } = useForm<CategoryFormData>({
    resolver: zodResolver(categorySchema),
  });

  const onSubmit = async (data: CategoryFormData) => {
    try {
      await createMutation.mutateAsync(data);
      navigate('/categories');
    } catch (error) {
      console.error('Failed to create category:', error);
    }
  };

  return (
    <div className="create-category-page">
      <div className="form-container">
        <div className="form-header">
          <h1>Create New Category</h1>
          <button
            type="button"
            className="btn btn-secondary"
            onClick={() => navigate('/categories')}
          >
            Cancel
          </button>
        </div>

        {createMutation.isError && (
          <div className="error-message">
            {createMutation.error instanceof Error
              ? createMutation.error.message
              : 'Failed to create category'}
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
              disabled={isSubmitting || createMutation.isPending}
            >
              {isSubmitting || createMutation.isPending ? 'Creating...' : 'Create Category'}
            </button>
          </div>
        </form>
      </div>
    </div>
  );
}
