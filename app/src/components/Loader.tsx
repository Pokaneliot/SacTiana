// Reusable Loader Component

import './Loader.css';

interface LoaderProps {
  size?: 'small' | 'medium' | 'large';
  message?: string;
  fullScreen?: boolean;
}

export function Loader({ size = 'medium', message, fullScreen = false }: LoaderProps) {
  const content = (
    <div className={`loader-content loader-${size}`}>
      <div className="loader-spinner"></div>
      {message && <p className="loader-message">{message}</p>}
    </div>
  );

  if (fullScreen) {
    return (
      <div className="loader-fullscreen">
        {content}
      </div>
    );
  }

  return content;
}
