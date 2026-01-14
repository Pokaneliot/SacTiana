import { useState } from 'react';
import { useNavigate } from 'react-router-dom';
import { useCategoriesQuery, useDeleteCategoryMutation } from './hooks';
import './CategoriesPage.css';

export function CategoriesPage() {
  const navigate = useNavigate();
  const { data: categories, isLoading, error } = useCategoriesQuery();
  const deleteMutation = useDeleteCategoryMutation();
  const [deleteError, setDeleteError] = useState<string | null>(null);

  const handleDelete = async (id: number, name: string) => {
    if (!confirm(`Are you sure you want to delete category "${name}"?`)) {
      return;
    }

    try {
      setDeleteError(null);
      await deleteMutation.mutateAsync(id);
    } catch (err) {
      setDeleteError(err instanceof Error ? err.message : 'Failed to delete category');
    }
  };

  if (isLoading) {
    return (
      <div className="categories-page">
        <div className="loading">Loading categories...</div>
      </div>
    );
  }

  if (error) {
    return (
      <div className="categories-page">
        <div className="error">Error loading categories: {error.message}</div>
      </div>
    );
  }

  return (
    <div className="categories-page">
      <div className="page-header">
        <h1>Product Categories</h1>
        <button
          className="btn btn-primary"
          onClick={() => navigate('/categories/create')}
        >
          + Create Category
        </button>
      </div>

      {deleteError && (
        <div className="error-message">
          {deleteError}
        </div>
      )}

      <div className="categories-grid">
        {categories && categories.length > 0 ? (
          categories.map((category) => (
            <div key={category.id} className="category-card">
              <div className="category-info">
                <h3>{category.name}</h3>
                <span className="category-id">ID: {category.id}</span>
              </div>
              <div className="category-actions">
                <button
                  className="btn btn-secondary"
                  onClick={() => navigate(`/categories/${category.id}/edit`)}
                >
                  Edit
                </button>
                <button
                  className="btn btn-danger"
                  onClick={() => handleDelete(category.id, category.name)}
                  disabled={deleteMutation.isPending}
                >
                  Delete
                </button>
              </div>
            </div>
          ))
        ) : (
          <div className="empty-state">
            <p>No categories found</p>
            <button
              className="btn btn-primary"
              onClick={() => navigate('/categories/create')}
            >
              Create your first category
            </button>
          </div>
        )}
      </div>
    </div>
  );
}
