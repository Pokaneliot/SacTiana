// Login Page Component

import { useState, FormEvent } from 'react';
import { useNavigate } from 'react-router-dom';
import { useAuth } from './hooks';
import type { LoginCredentials } from './types';
import { ApiError } from '../../services/apiClient';
import './LoginPage.css';

interface FieldErrors {
  login?: string;
  password?: string;
}

export function LoginPage() {
  const [credentials, setCredentials] = useState<LoginCredentials>({
    login: '',
    password: '',
  });
  const [error, setError] = useState<string>('');
  const [fieldErrors, setFieldErrors] = useState<FieldErrors>({});
  const [isSubmitting, setIsSubmitting] = useState(false);
  
  const { login } = useAuth();
  const navigate = useNavigate();

  const handleSubmit = async (e: FormEvent) => {
    e.preventDefault();
    setError('');
    setFieldErrors({});
    setIsSubmitting(true);

    try {
      await login(credentials);
      navigate('/dashboard');
    } catch (err: any) {
      if (err instanceof ApiError && err.validationErrors) {
        // Handle validation errors
        const errors: FieldErrors = {};
        err.validationErrors.forEach((validationError) => {
          errors[validationError.field as keyof FieldErrors] = validationError.message;
        });
        setFieldErrors(errors);
        setError('Please fix the errors below');
      } else {
        setError(err.message || 'Invalid credentials. Please try again.');
      }
    } finally {
      setIsSubmitting(false);
    }
  };

  return (
    <div className="login-container">
      <div className="login-card">
        <h1>Sign In</h1>
        <p className="login-subtitle">Welcome back! Please sign in to continue.</p>

        <form onSubmit={handleSubmit} className="login-form">
          {error && (
            <div className="error-message" role="alert">
              {error}
            </div>
          )}

          <div className="form-group">
            <label htmlFor="login">Login</label>
            <input
              id="login"
              type="text"
              value={credentials.login}
              onChange={(e) =>
                setCredentials({ ...credentials, login: e.target.value })
              }
              required
              disabled={isSubmitting}
              autoComplete="username"
              autoFocus
              className={fieldErrors.login ? 'input-error' : ''}
            />
            {fieldErrors.login && (
              <span className="field-error">{fieldErrors.login}</span>
            )}
          </div>

          <div className="form-group">
            <label htmlFor="password">Password</label>
            <input
              id="password"
              type="password"
              value={credentials.password}
              onChange={(e) =>
                setCredentials({ ...credentials, password: e.target.value })
              }
              required
              disabled={isSubmitting}
              autoComplete="current-password"
              className={fieldErrors.password ? 'input-error' : ''}
            />
            {fieldErrors.password && (
              <span className="field-error">{fieldErrors.password}</span>
            )}
          </div>

          <button
            type="submit"
            className="login-button"
            disabled={isSubmitting}
          >
            {isSubmitting ? 'Signing in...' : 'Sign In'}
          </button>
        </form>
      </div>
    </div>
  );
}
