import { useState, ReactNode } from 'react';
import { NavLink, useNavigate } from 'react-router-dom';
import { useAuth } from '../features/auth/hooks';
import './Layout.css';

interface LayoutProps {
  children: ReactNode;
}

export function Layout({ children }: LayoutProps) {
  const [sidebarOpen, setSidebarOpen] = useState(false);
  const { logout, user } = useAuth();
  const navigate = useNavigate();

  const handleLogout = async () => {
    try {
      await logout();
      navigate('/login');
    } catch (error) {
      console.error('Logout failed:', error);
    }
  };

  const closeSidebar = () => {
    if (window.innerWidth <= 768) {
      setSidebarOpen(false);
    }
  };

  const getUserInitials = (name: string) => {
    return name
      .split(' ')
      .map(n => n[0])
      .join('')
      .toUpperCase()
      .substring(0, 2);
  };

  return (
    <div className="layout">
      <button
        className="mobile-menu-button"
        onClick={() => setSidebarOpen(!sidebarOpen)}
        aria-label="Toggle menu"
      >
        ☰
      </button>

      <div
        className={`overlay ${sidebarOpen ? 'show' : ''}`}
        onClick={() => setSidebarOpen(false)}
      />

      <aside className={`sidebar ${sidebarOpen ? 'open' : ''}`}>
        <div className="sidebar-header">
          <h2>SacTiana</h2>
        </div>

        <nav className="sidebar-nav">
          <NavLink
            to="/dashboard"
            className={({ isActive }) => `nav-link ${isActive ? 'active' : ''}`}
            onClick={closeSidebar}
          >
            📊 Dashboard
          </NavLink>

          <NavLink
            to="/products"
            className={({ isActive }) => `nav-link ${isActive ? 'active' : ''}`}
            onClick={closeSidebar}
          >
            📦 Products
          </NavLink>
        </nav>

        <div className="sidebar-footer">
          {user && (
            <div className="user-info">
              <div className="user-avatar">
                {getUserInitials(user.name)}
              </div>
              <div className="user-details">
                <p className="user-name">{user.name}</p>
                <p className="user-role">{user.role}</p>
              </div>
            </div>
          )}

          <button className="logout-button" onClick={handleLogout}>
            🚪 Logout
          </button>
        </div>
      </aside>

      <main className="main-content">
        <div className="content-area">
          {children}
        </div>
      </main>
    </div>
  );
}
