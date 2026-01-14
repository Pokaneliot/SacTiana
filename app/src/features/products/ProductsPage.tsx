import { useState } from 'react';
import { Link } from 'react-router-dom';
import { useProducts, useDeleteProduct } from './hooks';
import { Loader } from '../../components/Loader';
import { ErrorAlert } from '../../components/ErrorAlert';
import type { ProductFilter } from './types';
import './ProductsPage.css';

export function ProductsPage() {
  const [filters, setFilters] = useState<ProductFilter>({});
  const [tempFilters, setTempFilters] = useState<ProductFilter>({});
  
  const { data: products, isLoading, error } = useProducts(filters);
  const deleteMutation = useDeleteProduct();

  const handleFilterChange = (key: keyof ProductFilter, value: string) => {
    setTempFilters(prev => ({
      ...prev,
      [key]: value || undefined,
    }));
  };

  const applyFilters = () => {
    const processedFilters: ProductFilter = {};
    
    if (tempFilters.ref) processedFilters.ref = tempFilters.ref;
    if (tempFilters.sellingPrice) processedFilters.sellingPrice = Number(tempFilters.sellingPrice);
    if (tempFilters.createdAt) processedFilters.createdAt = tempFilters.createdAt;
    if (tempFilters.categoryId) processedFilters.categoryId = Number(tempFilters.categoryId);
    
    setFilters(processedFilters);
  };

  const clearFilters = () => {
    setTempFilters({});
    setFilters({});
  };

  const handleDelete = async (id: number, name: string | null) => {
    if (window.confirm(`Are you sure you want to delete "${name || 'this product'}"?`)) {
      try {
        await deleteMutation.mutateAsync(id);
      } catch (error) {
        console.error('Delete failed:', error);
      }
    }
  };

  if (isLoading) {
    return <Loader />;
  }

  if (error) {
    return <ErrorAlert message={error.message} />;
  }

  return (
    <div className="products-page">
      <div className="page-header">
        <h1>Products Management</h1>
        <Link to="/products/new" className="btn btn-primary">
          ➕ Add Product
        </Link>
      </div>

      <div className="filters-card">
        <div className="filters-grid">
          <div className="form-group">
            <label>Reference</label>
            <input
              type="text"
              placeholder="Search by reference..."
              value={tempFilters.ref || ''}
              onChange={(e) => handleFilterChange('ref', e.target.value)}
            />
          </div>

          <div className="form-group">
            <label>Selling Price</label>
            <input
              type="number"
              step="0.01"
              placeholder="Filter by price..."
              value={tempFilters.sellingPrice || ''}
              onChange={(e) => handleFilterChange('sellingPrice', e.target.value)}
            />
          </div>

          <div className="form-group">
            <label>Created Date</label>
            <input
              type="date"
              value={tempFilters.createdAt || ''}
              onChange={(e) => handleFilterChange('createdAt', e.target.value)}
            />
          </div>

          <div className="form-group">
            <label>Category ID</label>
            <input
              type="number"
              placeholder="Filter by category..."
              value={tempFilters.categoryId || ''}
              onChange={(e) => handleFilterChange('categoryId', e.target.value)}
            />
          </div>
        </div>

        <div className="filter-actions">
          <button className="btn btn-secondary btn-sm" onClick={clearFilters}>
            Clear
          </button>
          <button className="btn btn-primary btn-sm" onClick={applyFilters}>
            Apply Filters
          </button>
        </div>
      </div>

      {products && products.length > 0 ? (
        <div className="products-grid">
          {products.map((product) => (
            <div key={product.id} className="product-card">
              <div className="product-header">
                <span className="product-ref">{product.ref}</span>
                <span className="product-badge">{product.category.name}</span>
              </div>

              <h3 className="product-name">{product.name || 'Unnamed Product'}</h3>

              <div className="product-info">
                <div className="info-row">
                  <span className="info-label">Purchase Price:</span>
                  <span className="info-value">${product.purchasePrice.toFixed(2)}</span>
                </div>
                <div className="info-row">
                  <span className="info-label">Selling Price:</span>
                  <span className="info-value price-highlight">${product.sellingPrice.toFixed(2)}</span>
                </div>
                <div className="info-row">
                  <span className="info-label">Stock:</span>
                  <span className="info-value">{product.quantity ?? 'N/A'}</span>
                </div>
                <div className="info-row">
                  <span className="info-label">Created:</span>
                  <span className="info-value">{product.createdAt}</span>
                </div>
                {product.lastUpdateAt && (
                  <div className="info-row">
                    <span className="info-label">Last Update:</span>
                    <span className="info-value">{product.lastUpdateAt}</span>
                  </div>
                )}
              </div>

              <div className="product-actions">
                <Link to={`/products/${product.id}/edit`} className="btn btn-secondary btn-sm">
                  ✏️ Edit
                </Link>
                <button
                  className="btn btn-danger btn-sm"
                  onClick={() => handleDelete(product.id, product.name)}
                  disabled={deleteMutation.isPending}
                >
                  🗑️ Delete
                </button>
              </div>
            </div>
          ))}
        </div>
      ) : (
        <div className="empty-state">
          <div className="empty-state-icon">📦</div>
          <h3>No products found</h3>
          <p>Start by creating your first product</p>
          <Link to="/products/new" className="btn btn-primary">
            ➕ Add Product
          </Link>
        </div>
      )}
    </div>
  );
}
