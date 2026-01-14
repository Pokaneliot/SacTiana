// React Router Configuration

import { Routes, Route, Navigate } from 'react-router-dom';
import { useAuth } from '../features/auth/hooks';
import { LoginPage } from '../features/auth/LoginPage';
import { DashboardPage } from '../features/dashboard/DashboardPage';
import { Loader } from '../components/Loader';
import { Layout } from '../components/Layout';
import { ProductsPage } from '../features/products/ProductsPage';
import { CreateProductPage } from '../features/products/CreateProductPage';
import { EditProductPage } from '../features/products/EditProductPage';
import { CategoriesPage } from '../features/categories/CategoriesPage';
import { CreateCategoryPage } from '../features/categories/CreateCategoryPage';
import { EditCategoryPage } from '../features/categories/EditCategoryPage';

// Protected Route Component
function ProtectedRoute({ children }: { children: React.ReactNode }) {
  const { isAuthenticated, isLoading } = useAuth();

  if (isLoading) {
    return <Loader fullScreen message="Loading..." />;
  }

  if (!isAuthenticated) {
    return <Navigate to="/login" replace />;
  }

  return <Layout>{children}</Layout>;
}

// Public Route Component (redirect to dashboard if already authenticated)
function PublicRoute({ children }: { children: React.ReactNode }) {
  const { isAuthenticated, isLoading } = useAuth();

  if (isLoading) {
    return <Loader fullScreen message="Loading..." />;
  }

  if (isAuthenticated) {
    return <Navigate to="/dashboard" replace />;
  }

  return <>{children}</>;
}

export function AppRouter() {
  return (
    <Routes>
      {/* Public Routes */}
      <Route
        path="/login"
        element={
          <PublicRoute>
            <LoginPage />
          </PublicRoute>
        }
      />

      {/* Protected Routes */}
      <Route
        path="/dashboard"
        element={
          <ProtectedRoute>
            <DashboardPage />
          </ProtectedRoute>
        }
      />

      {/* Product Routes */}
      <Route
        path="/products"
        element={
          <ProtectedRoute>
            <ProductsPage />
          </ProtectedRoute>
        }
      />
      <Route
        path="/products/new"
        element={
          <ProtectedRoute>
            <CreateProductPage />
          </ProtectedRoute>
        }
      />
      <Route
        path="/products/:id/edit"
        element={
          <ProtectedRoute>
            <EditProductPage />
          </ProtectedRoute>
        }
      />

      {/* Category Routes */}
      <Route
        path="/categories"
        element={
          <ProtectedRoute>
            <CategoriesPage />
          </ProtectedRoute>
        }
      />
      <Route
        path="/categories/create"
        element={
          <ProtectedRoute>
            <CreateCategoryPage />
          </ProtectedRoute>
        }
      />
      <Route
        path="/categories/:id/edit"
        element={
          <ProtectedRoute>
            <EditCategoryPage />
          </ProtectedRoute>
        }
      />

      {/* Default redirect */}
      <Route path="/" element={<Navigate to="/dashboard" replace />} />
      
      {/* 404 - redirect to dashboard/login based on auth */}
      <Route path="*" element={<Navigate to="/dashboard" replace />} />
    </Routes>
  );
}
