import React from 'react';

export default function Dashboard() {
    return (
        <div className="max-w-7xl mx-auto">
            <h2 className="text-2xl font-bold text-slate-800 mb-6">Overview</h2>

            <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                {[
                    { title: 'Total Users', value: '12,345', change: '+12%', color: 'blue' },
                    { title: 'Active Sessions', value: '423', change: '+5%', color: 'indigo' },
                    { title: 'New Signups', value: '89', change: '+24%', color: 'emerald' },
                ].map((stat, i) => (
                    <div key={i} className="bg-white rounded-xl shadow-sm border border-slate-100 p-6 transition hover:shadow-md">
                        <div className="flex items-center justify-between mb-4">
                            <h3 className="text-slate-500 text-sm font-medium">{stat.title}</h3>
                            <span className={`bg-${stat.color}-50 text-${stat.color}-600 text-xs px-2 py-1 rounded-full font-bold`}>
                                {stat.change}
                            </span>
                        </div>
                        <div className="text-3xl font-bold text-slate-800">{stat.value}</div>
                    </div>
                ))}
            </div>

            <div className="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden">
                <div className="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                    <h3 className="font-bold text-slate-800">Recent Activity</h3>
                    <button className="text-indigo-600 text-sm hover:underline">View All</button>
                </div>
                <div className="p-6">
                    <div className="space-y-4">
                        {[1, 2, 3].map((_, i) => (
                            <div key={i} className="flex items-center p-4 bg-slate-50 rounded-lg border border-slate-100">
                                <div className="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-bold mr-4">
                                    JS
                                </div>
                                <div>
                                    <p className="text-sm font-medium text-slate-800">John Smith created a new account</p>
                                    <p className="text-xs text-slate-500">2 minutes ago</p>
                                </div>
                            </div>
                        ))}
                    </div>
                </div>
            </div>
        </div>
    );
}
