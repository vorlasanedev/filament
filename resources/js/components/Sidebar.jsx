import React from 'react';

export default function Sidebar({ isCollapsed, toggleSidebar }) {
    const navItems = [
        { href: "#", label: "Dashboard", icon: <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>, active: true },
        { href: "#", label: "Users", icon: <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>, active: false },
        { href: "#", label: "Analytics", icon: <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>, active: false },
        { href: "#", label: "Settings", icon: <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>, additionalIcon: <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>, active: false },
    ];

    return (
        <aside
            className={`fixed inset-y-0 left-0 z-50 px-4 py-8 overflow-y-auto bg-slate-900 shadow-xl border-r border-slate-800 transition-all duration-300 ease-in-out ${isCollapsed ? 'w-20' : 'w-64'
                }`}
        >
            <div className={`flex items-center ${isCollapsed ? 'justify-center' : 'justify-between'} mb-10 pr-2`}>
                {!isCollapsed ? (
                    <h1 className="text-2xl font-bold bg-gradient-to-r from-blue-400 to-indigo-500 bg-clip-text text-transparent truncate">
                        Nexus<span className="text-white">Admin</span>
                    </h1>
                ) : (
                    <h1 className="text-2xl font-bold bg-gradient-to-r from-blue-400 to-indigo-500 bg-clip-text text-transparent">
                        N
                    </h1>
                )}

                <button
                    onClick={toggleSidebar}
                    className={`p-1.5 rounded-lg text-slate-400 hover:bg-slate-800 hover:text-white transition-colors ${isCollapsed ? 'hidden' : 'block'}`}
                >
                    <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7" />
                    </svg>
                </button>
            </div>

            {isCollapsed && (
                <div className="flex justify-center mb-6">
                    <button
                        onClick={toggleSidebar}
                        className="p-1.5 rounded-lg text-slate-400 hover:bg-slate-800 hover:text-white transition-colors"
                    >
                        <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M13 5l7 7-7 7M5 5l7 7-7 7" />
                        </svg>
                    </button>
                </div>
            )}

            <nav className="space-y-2">
                {navItems.map((item, index) => (
                    <a
                        key={index}
                        href={item.href}
                        className={`flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-all ${item.active
                            ? 'text-white bg-indigo-600 shadow-lg shadow-indigo-500/20 hover:bg-indigo-700'
                            : 'text-slate-400 hover:text-white hover:bg-slate-800'
                            } ${isCollapsed ? 'justify-center px-2' : ''}`}
                        title={isCollapsed ? item.label : ''}
                    >
                        <svg className={`w-5 h-5 ${isCollapsed ? '' : 'mr-3'}`} fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            {item.icon}
                            {item.additionalIcon}
                        </svg>
                        {!isCollapsed && item.label}
                    </a>
                ))}
            </nav>
        </aside>
    );
}
