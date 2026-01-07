import './bootstrap';
import React, { useState, useEffect } from 'react';
import { createRoot } from 'react-dom/client';
import Login from './components/Login';
import Dashboard from './components/Dashboard';
import Layout from './components/Layout';

function App() {
    const [token, setToken] = useState(localStorage.getItem('access_token'));
    // Listen for storage changes to handle login/logout across tabs or refreshes
    useEffect(() => {
        const handleStorageChange = () => {
            setToken(localStorage.getItem('access_token'));
        };
        window.addEventListener('storage', handleStorageChange);
        return () => window.removeEventListener('storage', handleStorageChange);
    }, []);

    // Custom handler to update state immediately upon action in child components
    const handleLoginSuccess = (newToken) => {
        localStorage.setItem('access_token', newToken);
        setToken(newToken);
    };

    const handleLogout = () => {
        localStorage.removeItem('access_token');
        setToken(null);
    };

    if (!token) {
        return <Login onLoginSuccess={handleLoginSuccess} />;
    }

    return (
        <Layout onLogout={handleLogout}>
            <Dashboard />
        </Layout>
    );
}

const app = document.getElementById('app');

if (app) {
    const root = createRoot(app);
    root.render(
        <React.StrictMode>
            <App />
        </React.StrictMode>
    );
}
