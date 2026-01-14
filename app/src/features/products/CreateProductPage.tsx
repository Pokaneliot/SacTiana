import { useForm } from 'react-hook-form';
import { zodResolver } from '@hookform/resolvers/zod';
import { z } from 'zod';
import { useNavigate } from 'react-router-dom';
import { useCreateProduct } from './hooks';
import { Loader } from '../../components/Loader';
import { ErrorAlert } from '../../components/ErrorAlert';
import '../products/ProductsPage.css';

const productSchema = z.object({
  ref: z.string().min(1, 'Reference is required').max(50, 'Reference must be at most 50 characters'),
  name: z.string().max(50, 'Name must be at most 50 characters').optional(),
  purchasePrice: z.number().positive('Purchase price must be positive'),
  sellingPrice: z.number().positive('Selling price must be positive'),
  categoryId: z.number().positive('Category is required'),
});

type ProductFormData = z.infer<typeof productSchema>;

export function CreateProductPage() {
  const navigate = useNavigate();
  const createMutation = useCreateProduct();

  const {
    register,
    handleSubmit,
    formState: { errors },
  } = useForm<ProductFormData>({
    resolver: zodResolver(productSchema),
    mode: 'onBlur',
  });

  const onSubmit = async (data: ProductFormData) => {
    try {
      await createMutation.mutateAsync(data);
      navigate('/products');
    } catch (error) {
      console.error('Create failed:', error);
    }
  };

  return (
    <div className="products-page">
      <div className="page-header">
        <h1>Create New Product</h1>
      </div>

      <div className="form-card">
        {createMutation.error && (
          <ErrorAlert message={createMutation.error.message} />
        )}

        <form onSubmit={handleSubmit(onSubmit)}>
          <div className="form-group">
            <label htmlFor="ref">Reference *</label>
            <input
              id="ref"
              type="text"
              {...register('ref')}
              placeholder="e.g., PROD001"
            />
            {errors.ref && (
              <div className="form-error">{errors.ref.message}</div>
            )}
          </div>

          <div className="form-group">
            <label htmlFor="name">Name</label>
            <input
              id="name"
              type="text"
              {...register('name')}
              placeholder="Product name"
            />
            {errors.name && (
              <div className="form-error">{errors.name.message}</div>
            )}
          </div>

          <div className="form-group">
            <label htmlFor="purchasePrice">Purchase Price *</label>
            <input
              id="purchasePrice"
              type="number"
              step="0.01"
              {...register('purchasePrice', { valueAsNumber: true })}
              placeholder="0.00"
            />
            {errors.purchasePrice && (
              <div className="form-error">{errors.purchasePrice.message}</div>
            )}
          </div>

          <div className="form-group">
            <label htmlFor="sellingPrice">Selling Price *</label>
            <input
              id="sellingPrice"
              type="number"
              step="0.01"
              {...register('sellingPrice', { valueAsNumber: true })}
              placeholder="0.00"
            />
            {errors.sellingPrice && (
              <div className="form-error">{errors.sellingPrice.message}</div>
            )}
          </div>

          <div className="form-group">
            <label htmlFor="categoryId">Category ID *</label>
            <input
              id="categoryId"
              type="number"
              {...register('categoryId', { valueAsNumber: true })}
              placeholder="1"
            />
            {errors.categoryId && (
              <div className="form-error">{errors.categoryId.message}</div>
            )}
          </div>

          <div className="form-actions">
            <button
              type="button"
              className="btn btn-secondary"
              onClick={() => navigate('/products')}
              disabled={createMutation.isPending}
            >
              Cancel
            </button>
            <button
              type="submit"
              className="btn btn-primary"
              disabled={createMutation.isPending}
            >
              {createMutation.isPending ? <Loader /> : 'Create Product'}
            </button>
          </div>
        </form>
      </div>
    </div>
  );
}
