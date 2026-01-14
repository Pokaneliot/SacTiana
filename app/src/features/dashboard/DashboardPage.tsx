import { Link } from 'react-router-dom';
import { useAuth } from '../auth/hooks';
import './DashboardPage.css';

export function DashboardPage() {
  const { user } = useAuth();

  return (
    <div className="dashboard">
      <h1>Dashboard</h1>
      
      <div className="welcome-card">
        <h2>Welcome back, {user?.name}! 👋</h2>
        <p>Role: {user?.role}</p>
      </div>

      <div className="quick-links">
        <Link to="/products" className="quick-link-card">
          <div className="quick-link-icon">📦</div>
          <h3>Products</h3>
          <p>Manage your product catalog</p>
        </Link>

        <Link to="/products/new" className="quick-link-card">
          <div className="quick-link-icon">➕</div>
          <h3>Add Product</h3>
          <p>Create a new product</p>
        </Link>

        <div className="quick-link-card" style={{ opacity: 0.5, cursor: 'not-allowed' }}>
          <div className="quick-link-icon">📊</div>
          <h3>Reports</h3>
          <p>Coming soon...</p>
        </div>

        <div className="quick-link-card" style={{ opacity: 0.5, cursor: 'not-allowed' }}>
          <div className="quick-link-icon">⚙️</div>
          <h3>Settings</h3>
          <p>Coming soon...</p>
        </div>
      </div>
    </div>
  );
}
