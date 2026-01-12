// Reusable Error Alert Component

import './ErrorAlert.css';

interface ErrorAlertProps {
  message: string;
  errors?: Array<{ field: string; message: string }>;
  onClose?: () => void;
}

export function ErrorAlert({ message, errors, onClose }: ErrorAlertProps) {
  return (
    <div className="error-alert" role="alert">
      <div className="error-alert-header">
        <span className="error-icon">⚠️</span>
        <span className="error-message">{message}</span>
        {onClose && (
          <button className="error-close" onClick={onClose} aria-label="Close">
            ×
          </button>
        )}
      </div>
      {errors && errors.length > 0 && (
        <ul className="error-list">
          {errors.map((error, index) => (
            <li key={index}>
              <strong>{error.field}:</strong> {error.message}
            </li>
          ))}
        </ul>
      )}
    </div>
  );
}
