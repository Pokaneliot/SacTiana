import { useForm } from 'react-hook-form';
import { zodResolver } from '@hookform/resolvers/zod';
import { z } from 'zod';
import { useNavigate, useParams } from 'react-router-dom';
import { useProduct, useUpdateProduct } from './hooks';
import { Loader } from '../../components/Loader';
import { ErrorAlert } from '../../components/ErrorAlert';
import '../products/ProductsPage.css';

const productSchema = z.object({
  ref: z.string().max(50, 'Reference must be at most 50 characters').optional(),
  name: z.string().max(50, 'Name must be at most 50 characters').optional(),
  purchasePrice: z.number().positive('Purchase price must be positive').optional(),
  sellingPrice: z.number().positive('Selling price must be positive').optional(),
});

type ProductFormData = z.infer<typeof productSchema>;

export function EditProductPage() {
  const { id } = useParams<{ id: string }>();
  const navigate = useNavigate();
  const productId = Number(id);

  const { data: product, isLoading, error } = useProduct(productId);
  const updateMutation = useUpdateProduct();

  const {
    register,
    handleSubmit,
    formState: { errors },
  } = useForm<ProductFormData>({
    resolver: zodResolver(productSchema),
    mode: 'onBlur',
    values: product
      ? {
          ref: product.ref,
          name: product.name || '',
          purchasePrice: product.purchasePrice,
          sellingPrice: product.sellingPrice,
        }
      : undefined,
  });

  const onSubmit = async (data: ProductFormData) => {
    // Only send fields that have values
    const updateData: ProductFormData = {};
    if (data.ref) updateData.ref = data.ref;
    if (data.name) updateData.name = data.name;
    if (data.purchasePrice) updateData.purchasePrice = data.purchasePrice;
    if (data.sellingPrice) updateData.sellingPrice = data.sellingPrice;

    try {
      await updateMutation.mutateAsync({ id: productId, data: updateData });
      navigate('/products');
    } catch (error) {
      console.error('Update failed:', error);
    }
  };

  if (isLoading) {
    return <Loader />;
  }

  if (error || !product) {
    return <ErrorAlert message={error?.message || 'Product not found'} />;
  }

  return (
    <div className="products-page">
      <div className="page-header">
        <h1>Edit Product</h1>
      </div>

      <div className="form-card">
        {updateMutation.error && (
          <ErrorAlert message={updateMutation.error.message} />
        )}

        <form onSubmit={handleSubmit(onSubmit)}>
          <div className="form-group">
            <label htmlFor="ref">Reference</label>
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
            <label htmlFor="purchasePrice">Purchase Price</label>
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
            <label htmlFor="sellingPrice">Selling Price</label>
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

          <div className="info-row" style={{ margin: '1rem 0', padding: '1rem', background: '#f7fafc', borderRadius: '8px' }}>
            <span className="info-label">Current Category:</span>
            <span className="info-value">{product.category.name}</span>
          </div>

          <div className="info-row" style={{ margin: '1rem 0', padding: '0.5rem', background: '#fff5e6', borderRadius: '8px', fontSize: '0.875rem', color: '#744210' }}>
            ℹ️ Note: Only filled fields will be updated. Empty fields will keep their current values.
          </div>

          <div className="form-actions">
            <button
              type="button"
              className="btn btn-secondary"
              onClick={() => navigate('/products')}
              disabled={updateMutation.isPending}
            >
              Cancel
            </button>
            <button
              type="submit"
              className="btn btn-primary"
              disabled={updateMutation.isPending}
            >
              {updateMutation.isPending ? <Loader /> : 'Update Product'}
            </button>
          </div>
        </form>
      </div>
    </div>
  );
}
