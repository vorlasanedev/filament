import React, { useState, useEffect } from 'react';

export default function Navbar({ onLogout }) {
    const [user, setUser] = useState(null);
    const [loading, setLoading] = useState(true);

    useEffect(() => {
        const fetchUser = async () => {
            try {
                const token = localStorage.getItem('access_token');
                const response = await window.axios.post('/api/auth/me', {}, {
                    headers: { Authorization: `Bearer ${token}` }
                });
                setUser(response.data);
            } catch (error) {
                console.error('Failed to fetch user', error);
                if (error.response?.status === 401) {
                    onLogout();
                }
            } finally {
                setLoading(false);
            }
        };

        fetchUser();
    }, [onLogout]);

    return (
        <header className="flex items-center justify-end px-6 py-4 bg-white border-b border-gray-200">

            <div className="flex items-center space-x-4">
                {loading ? (
                    <div className="w-32 h-8 bg-gray-200 rounded animate-pulse"></div>
                ) : user ? (
                    <div className="flex items-center gap-3">
                        <div className="text-right hidden sm:block">
                            <p className="text-sm font-semibold text-gray-900">{user.name}</p>
                            <p className="text-xs text-gray-500">{user.email}</p>
                        </div>
                        <div className="relative group cursor-pointer">
                            <div className="w-10 h-10 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 font-bold border-2 border-white ring-2 ring-indigo-50 shadow-sm">
                                {user.name ? user.name.charAt(0).toUpperCase() : 'U'}
                            </div>
                            {/* Dropdown Menu */}
                            <div className="absolute right-0 top-full pt-2 w-48 hidden group-hover:block z-20">
                                <div className="bg-white rounded-md shadow-lg py-1 border border-gray-100">
                                    <a href="#" className="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">Your Profile</a>
                                    <a href="#" className="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">Settings</a>
                                    <button
                                        onClick={onLogout}
                                        className="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50"
                                    >
                                        Sign out
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                ) : (
                    <span className="text-gray-500">Guest</span>
                )}
            </div>
        </header>
    );
}
