@php
    $info = $profileData['info'];
    $roles = $profileData['roles'];
    $modules = $profileData['modules'];
    $menuTree = $profileData['menu_tree'];
    $isSuperAdmin = $profileData['is_super_admin'];
@endphp

<div
    class="user-profile-modal"
    x-data="{
        activeTab: 'modules',
        moduleSearch: '',
        menuSearch: '',
        expandedModules: {},

        toggleModule(key) {
            this.expandedModules[key] = ! this.expandedModules[key];
        },

        expandAll() {
            @foreach ($modules as $group => $items)
                @foreach ($items as $item)
                    this.expandedModules['{{ $item['key'] }}'] = true;
                @endforeach
            @endforeach
        },

        collapseAll() {
            this.expandedModules = {};
        }
    }"
>
    <style>
        .user-profile-modal {
            font-family: inherit;
            color: #1f2937;
            display: flex;
            flex-direction: column;
            gap: 1.25rem;
            width: 100%;
        }
        .dark .user-profile-modal {
            color: #e5e7eb;
        }

        /* Bulletproof SVG safety - prevent any SVG from ever expanding */
        .user-profile-modal svg {
            max-width: 100%;
            display: inline-block;
            vertical-align: middle;
            flex-shrink: 0;
        }
        .user-profile-modal .up-icon-xs {
            width: 12px !important;
            height: 12px !important;
            min-width: 12px !important;
            min-height: 12px !important;
            max-width: 12px !important;
            max-height: 12px !important;
            flex-shrink: 0 !important;
            display: inline-block !important;
        }
        .user-profile-modal .up-icon-sm {
            width: 14px !important;
            height: 14px !important;
            min-width: 14px !important;
            min-height: 14px !important;
            max-width: 14px !important;
            max-height: 14px !important;
            flex-shrink: 0 !important;
            display: inline-block !important;
        }
        .user-profile-modal .up-icon-md {
            width: 16px !important;
            height: 16px !important;
            min-width: 16px !important;
            min-height: 16px !important;
            max-width: 16px !important;
            max-height: 16px !important;
            flex-shrink: 0 !important;
            display: inline-block !important;
        }

        /* 1. Header Card */
        .up-header-card {
            border: 1px solid rgba(0, 0, 0, 0.08);
            border-radius: 1rem;
            background: linear-gradient(135deg, #ffffff, #f9fafb);
            padding: 1.25rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }
        @media (min-width: 640px) {
            .up-header-card {
                flex-direction: row;
                align-items: center;
                justify-content: space-between;
            }
        }
        .dark .up-header-card {
            background: linear-gradient(135deg, #18181b, #111827);
            border-color: rgba(255, 255, 255, 0.08);
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.3);
        }

        .up-profile-left {
            display: flex;
            align-items: flex-start;
            gap: 1rem;
        }

        .up-avatar-wrap {
            position: relative;
            width: 56px;
            height: 56px;
            flex-shrink: 0;
        }
        .up-avatar-img {
            width: 56px;
            height: 56px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #ffffff;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.08);
            background-color: #f3f4f6;
        }
        .dark .up-avatar-img {
            border-color: #27272a;
            background-color: #27272a;
        }
        .up-status-dot {
            position: absolute;
            bottom: 0;
            right: 0;
            width: 14px;
            height: 14px;
            border-radius: 50%;
            border: 2px solid #ffffff;
        }
        .dark .up-status-dot {
            border-color: #18181b;
        }
        .up-dot-active { background-color: #10b981; }
        .up-dot-inactive { background-color: #9ca3af; }

        .up-details-col {
            display: flex;
            flex-direction: column;
            gap: 0.25rem;
        }
        .up-name-row {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 0.5rem;
        }
        .up-user-name {
            font-size: 1.125rem;
            font-weight: 700;
            color: #111827;
            margin: 0;
            line-height: 1.3;
        }
        .dark .up-user-name {
            color: #f9fafb;
        }
        .up-user-id-badge {
            background: #f3f4f6;
            color: #4b5563;
            font-size: 0.75rem;
            font-weight: 500;
            padding: 0.125rem 0.375rem;
            border-radius: 0.25rem;
        }
        .dark .up-user-id-badge {
            background: #27272a;
            color: #9ca3af;
        }
        .up-super-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.3rem;
            background: linear-gradient(135deg, #f59e0b, #d97706);
            color: #ffffff;
            font-size: 0.6875rem;
            font-weight: 700;
            padding: 0.2rem 0.6rem;
            border-radius: 9999px;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
        }

        .up-meta-text-row {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 0.5rem;
            font-size: 0.75rem;
            color: #6b7280;
        }
        .dark .up-meta-text-row {
            color: #9ca3af;
        }
        .up-username-highlight {
            font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace;
            color: #d97706;
            font-weight: 600;
        }
        .dark .up-username-highlight {
            color: #fbbf24;
        }

        .up-roles-row {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 0.375rem;
            margin-top: 0.25rem;
        }
        .up-roles-label {
            font-size: 0.75rem;
            color: #6b7280;
        }
        .dark .up-roles-label {
            color: #9ca3af;
        }
        .up-role-pill-primary {
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
            background-color: #fef3c7;
            color: #92400e;
            border: 1px solid #fde68a;
            font-size: 0.6875rem;
            font-weight: 600;
            padding: 0.15rem 0.5rem;
            border-radius: 0.375rem;
        }
        .dark .up-role-pill-primary {
            background-color: rgba(245, 158, 11, 0.2);
            color: #fcd34d;
            border-color: rgba(245, 158, 11, 0.4);
        }
        .up-role-pill-secondary {
            display: inline-flex;
            align-items: center;
            background-color: #f3f4f6;
            color: #374151;
            border: 1px solid #e5e7eb;
            font-size: 0.6875rem;
            font-weight: 500;
            padding: 0.15rem 0.5rem;
            border-radius: 0.375rem;
        }
        .dark .up-role-pill-secondary {
            background-color: #27272a;
            color: #d1d5db;
            border-color: #3f3f46;
        }

        .up-meta-right {
            display: flex;
            flex-direction: column;
            gap: 0.375rem;
            align-items: flex-start;
        }
        @media (min-width: 640px) {
            .up-meta-right {
                align-items: flex-end;
            }
        }
        .up-status-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
            padding: 0.25rem 0.625rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
        }
        .up-status-active {
            background-color: #ecfdf5;
            color: #065f46;
            border: 1px solid #a7f3d0;
        }
        .dark .up-status-active {
            background-color: rgba(16, 185, 129, 0.15);
            color: #34d399;
            border-color: rgba(16, 185, 129, 0.3);
        }
        .up-status-inactive {
            background-color: #f3f4f6;
            color: #4b5563;
            border: 1px solid #e5e7eb;
        }
        .dark .up-status-inactive {
            background-color: #27272a;
            color: #9ca3af;
            border-color: #3f3f46;
        }
        .up-time-text {
            font-size: 0.6875rem;
            color: #6b7280;
        }
        .dark .up-time-text {
            color: #9ca3af;
        }

        /* 2. Tabs Bar */
        .up-tabs-bar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid rgba(0, 0, 0, 0.08);
            padding-bottom: 0;
            margin-top: 0.5rem;
        }
        .dark .up-tabs-bar {
            border-bottom-color: rgba(255, 255, 255, 0.08);
        }
        .up-tab-buttons {
            display: flex;
            gap: 0.5rem;
        }
        .up-tab-btn {
            background: none;
            border: none;
            border-bottom: 2px solid transparent;
            padding: 0.5rem 0.75rem;
            font-size: 0.75rem;
            font-weight: 600;
            color: #6b7280;
            cursor: pointer;
            transition: all 0.15s ease;
        }
        .up-tab-btn:hover {
            color: #111827;
        }
        .dark .up-tab-btn {
            color: #9ca3af;
        }
        .dark .up-tab-btn:hover {
            color: #ffffff;
        }
        .up-tab-btn-active {
            border-bottom-color: #f59e0b !important;
            color: #d97706 !important;
            font-weight: 700;
        }
        .dark .up-tab-btn-active {
            color: #fbbf24 !important;
        }
        .up-bypass-notice {
            font-size: 0.6875rem;
            color: #d97706;
            font-weight: 500;
            display: none;
        }
        @media (min-width: 640px) {
            .up-bypass-notice {
                display: inline-block;
            }
        }
        .dark .up-bypass-notice {
            color: #fbbf24;
        }

        /* 3. Toolbar (Search & Expand/Collapse) */
        .up-toolbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 0.75rem;
            margin-bottom: 0.75rem;
        }
        .up-search-box {
            position: relative;
            width: 100%;
            max-width: 280px;
        }
        .up-search-icon {
            position: absolute;
            left: 0.625rem;
            top: 50%;
            transform: translateY(-50%);
            width: 14px !important;
            height: 14px !important;
            color: #9ca3af;
            pointer-events: none;
        }
        .up-search-input {
            width: 100%;
            height: 32px;
            padding-left: 2rem;
            padding-right: 0.75rem;
            font-size: 0.75rem;
            border-radius: 0.5rem;
            border: 1px solid #d1d5db;
            background-color: #ffffff;
            color: #111827;
            outline: none;
            box-sizing: border-box;
        }
        .up-search-input:focus {
            border-color: #f59e0b;
            box-shadow: 0 0 0 1px #f59e0b;
        }
        .dark .up-search-input {
            background-color: #18181b;
            border-color: #3f3f46;
            color: #f9fafb;
        }
        .dark .up-search-input:focus {
            border-color: #f59e0b;
        }
        .up-btn-toolbar {
            background: #ffffff;
            border: 1px solid #e5e7eb;
            border-radius: 0.375rem;
            padding: 0.25rem 0.625rem;
            font-size: 0.6875rem;
            font-weight: 500;
            color: #4b5563;
            cursor: pointer;
            transition: all 0.15s ease;
        }
        .up-btn-toolbar:hover {
            background: #f9fafb;
            border-color: #d1d5db;
        }
        .dark .up-btn-toolbar {
            background: #27272a;
            border-color: #3f3f46;
            color: #d1d5db;
        }
        .dark .up-btn-toolbar:hover {
            background: #3f3f46;
        }

        /* 4. Module Group Cards & Accordions */
        .up-groups-list {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
        }
        .up-group-panel {
            background: #ffffff;
            border: 1px solid rgba(0, 0, 0, 0.08);
            border-radius: 0.75rem;
            padding: 0.875rem;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.02);
        }
        .dark .up-group-panel {
            background: #18181b;
            border-color: rgba(255, 255, 255, 0.08);
        }
        .up-group-title {
            font-size: 0.6875rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #6b7280;
            margin: 0 0 0.625rem 0;
        }
        .dark .up-group-title {
            color: #9ca3af;
        }
        .up-modules-container {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }
        .up-module-card {
            border: 1px solid #f3f4f6;
            background: #fafafa;
            border-radius: 0.5rem;
            overflow: hidden;
            transition: border-color 0.15s ease;
        }
        .dark .up-module-card {
            border-color: #27272a;
            background: rgba(39, 39, 42, 0.4);
        }
        .up-module-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0.625rem 0.75rem;
            cursor: pointer;
            user-select: none;
        }
        .up-module-header:hover {
            background: rgba(0, 0, 0, 0.02);
        }
        .dark .up-module-header:hover {
            background: rgba(255, 255, 255, 0.02);
        }
        .up-mod-name-group {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        .up-mod-title {
            font-size: 0.75rem;
            font-weight: 700;
            color: #111827;
        }
        .dark .up-mod-title {
            color: #f9fafb;
        }
        .up-mod-key {
            font-size: 0.6875rem;
            color: #9ca3af;
        }
        .up-count-pill {
            font-size: 0.625rem;
            font-weight: 600;
            padding: 0.125rem 0.5rem;
            border-radius: 9999px;
        }
        .up-count-allowed {
            background: #ecfdf5;
            color: #065f46;
            border: 1px solid #a7f3d0;
        }
        .dark .up-count-allowed {
            background: rgba(16, 185, 129, 0.15);
            color: #34d399;
            border-color: rgba(16, 185, 129, 0.3);
        }
        .up-count-none {
            background: #f3f4f6;
            color: #6b7280;
            border: 1px solid #e5e7eb;
        }
        .dark .up-count-none {
            background: #27272a;
            color: #9ca3af;
            border-color: #3f3f46;
        }

        .up-actions-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 0.375rem;
            padding: 0.625rem 0.75rem;
            border-top: 1px solid #f3f4f6;
        }
        @media (min-width: 640px) {
            .up-actions-grid {
                grid-template-columns: repeat(3, minmax(0, 1fr));
            }
        }
        @media (min-width: 1024px) {
            .up-actions-grid {
                grid-template-columns: repeat(5, minmax(0, 1fr));
            }
        }
        .dark .up-actions-grid {
            border-top-color: #27272a;
        }
        .up-action-chip {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0.375rem 0.5rem;
            border-radius: 0.375rem;
            font-size: 0.6875rem;
            border: 1px solid;
            box-sizing: border-box;
        }
        .up-action-granted {
            background-color: #f0fdf4;
            border-color: #bbf7d0;
            color: #15803d;
        }
        .dark .up-action-granted {
            background-color: rgba(16, 185, 129, 0.1);
            border-color: rgba(16, 185, 129, 0.25);
            color: #4ade80;
        }
        .up-action-denied {
            background-color: #ffffff;
            border-color: #e5e7eb;
            color: #9ca3af;
            opacity: 0.65;
        }
        .dark .up-action-denied {
            background-color: #18181b;
            border-color: #27272a;
            color: #71717a;
        }

        /* 5. Menu Tree Grid */
        .up-tree-grid {
            display: grid;
            grid-template-columns: repeat(1, minmax(0, 1fr));
            gap: 0.5rem;
            margin-top: 0.625rem;
        }
        @media (min-width: 640px) {
            .up-tree-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }
        @media (min-width: 1024px) {
            .up-tree-grid {
                grid-template-columns: repeat(3, minmax(0, 1fr));
            }
        }
        .up-tree-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0.5rem 0.625rem;
            border-radius: 0.5rem;
            border: 1px solid;
            font-size: 0.75rem;
            box-sizing: border-box;
        }
        .up-tree-item-ok {
            background-color: #f0fdf4;
            border-color: #bbf7d0;
        }
        .dark .up-tree-item-ok {
            background-color: rgba(16, 185, 129, 0.08);
            border-color: rgba(16, 185, 129, 0.2);
        }
        .up-tree-item-blocked {
            background-color: #f9fafb;
            border-color: #e5e7eb;
            opacity: 0.65;
        }
        .dark .up-tree-item-blocked {
            background-color: rgba(39, 39, 42, 0.4);
            border-color: #27272a;
        }
        .up-badge-ok {
            background-color: #dcfce7;
            color: #15803d;
            font-size: 0.625rem;
            font-weight: 700;
            padding: 0.125rem 0.375rem;
            border-radius: 9999px;
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
        }
        .dark .up-badge-ok {
            background-color: rgba(16, 185, 129, 0.2);
            color: #4ade80;
        }
        .up-badge-blocked {
            background-color: #fef2f2;
            color: #b91c1c;
            font-size: 0.625rem;
            font-weight: 700;
            padding: 0.125rem 0.375rem;
            border-radius: 9999px;
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
        }
        .dark .up-badge-blocked {
            background-color: rgba(239, 68, 68, 0.2);
            color: #f87171;
        }

        /* 6. Roles Cards */
        .up-roles-grid {
            display: grid;
            grid-template-columns: repeat(1, minmax(0, 1fr));
            gap: 0.75rem;
        }
        @media (min-width: 640px) {
            .up-roles-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }
        .up-role-box {
            border: 1px solid #e5e7eb;
            background: #ffffff;
            border-radius: 0.75rem;
            padding: 0.875rem;
        }
        .dark .up-role-box {
            border-color: #27272a;
            background: #18181b;
        }
        .up-role-box-primary {
            border-color: #fde68a;
            background: #fffbeb;
        }
        .dark .up-role-box-primary {
            border-color: rgba(245, 158, 11, 0.4);
            background: rgba(245, 158, 11, 0.1);
        }
        .up-super-alert {
            border: 1px solid #fde68a;
            background: #fffbeb;
            border-radius: 0.75rem;
            padding: 0.875rem;
            font-size: 0.75rem;
            color: #92400e;
            margin-top: 0.75rem;
        }
        .dark .up-super-alert {
            border-color: rgba(245, 158, 11, 0.3);
            background: rgba(245, 158, 11, 0.12);
            color: #fcd34d;
        }
    </style>

    {{-- 1. USER PROFILE HEADER --}}
    <div class="up-header-card">
        <div class="up-profile-left">
            {{-- Avatar with Status Dot --}}
            <div class="up-avatar-wrap">
                <img
                    src="{{ $info['avatar_url'] }}"
                    alt="{{ $info['name'] }}"
                    class="up-avatar-img"
                />
                <span
                    class="up-status-dot {{ $info['is_active'] ? 'up-dot-active' : 'up-dot-inactive' }}"
                    title="{{ $info['is_active'] ? 'Active User' : 'Inactive User' }}"
                ></span>
            </div>

            {{-- User Core Details --}}
            <div class="up-details-col">
                <div class="up-name-row">
                    <h2 class="up-user-name">{{ $info['name'] }}</h2>
                    <span class="up-user-id-badge">ID: #{{ $info['id'] }}</span>
                    @if ($isSuperAdmin)
                        <span class="up-super-badge">
                            <svg width="14" height="14" viewBox="0 0 20 20" fill="currentColor" class="up-icon-sm" style="width: 14px; height: 14px; min-width: 14px; min-height: 14px; max-width: 14px; max-height: 14px; flex-shrink: 0;"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            Super Admin
                        </span>
                    @endif
                </div>

                <div class="up-meta-text-row">
                    <span class="up-username-highlight">{{ $info['username'] }}</span>
                    <span>•</span>
                    <span>{{ $info['email'] }}</span>
                    <span>•</span>
                    <span>{{ $info['phone'] }}</span>
                </div>

                {{-- Primary & Assigned Roles --}}
                <div class="up-roles-row">
                    <span class="up-roles-label">Roles:</span>
                    @forelse ($roles['list'] as $r)
                        @if ($r['is_primary'])
                            <span class="up-role-pill-primary">
                                <svg width="12" height="12" viewBox="0 0 20 20" fill="currentColor" class="up-icon-xs" style="width: 12px; height: 12px; min-width: 12px; min-height: 12px; max-width: 12px; max-height: 12px; flex-shrink: 0;"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                {{ $r['display_name'] }} (Primary)
                            </span>
                        @else
                            <span class="up-role-pill-secondary">
                                {{ $r['display_name'] }}
                            </span>
                        @endif
                    @empty
                        <span style="font-size: 0.75rem; font-style: italic; color: #9ca3af;">No role assigned</span>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Quick Meta Badges --}}
        <div class="up-meta-right">
            <span class="up-status-badge {{ $info['is_active'] ? 'up-status-active' : 'up-status-inactive' }}">
                <span style="width: 6px; height: 6px; border-radius: 50%; display: inline-block; background-color: {{ $info['is_active'] ? '#10b981' : '#9ca3af' }};"></span>
                {{ $info['is_active'] ? 'Active Status' : 'Inactive Status' }}
            </span>
            <span class="up-time-text">
                Last activity: <strong style="color: inherit;">{{ $info['last_login'] }}</strong>
            </span>
            <span class="up-time-text">
                Registered: <strong style="color: inherit;">{{ $info['created_at'] }}</strong> ({{ $info['created_ago'] }})
            </span>
        </div>
    </div>

    {{-- 2. NAVIGATION TABS --}}
    <div class="up-tabs-bar">
        <div class="up-tab-buttons">
            <button
                type="button"
                @click="activeTab = 'modules'"
                :class="activeTab === 'modules' ? 'up-tab-btn up-tab-btn-active' : 'up-tab-btn'"
            >
                Module Permissions
            </button>
            <button
                type="button"
                @click="activeTab = 'menus'"
                :class="activeTab === 'menus' ? 'up-tab-btn up-tab-btn-active' : 'up-tab-btn'"
            >
                Menu Access Tree
            </button>
            <button
                type="button"
                @click="activeTab = 'roles'"
                :class="activeTab === 'roles' ? 'up-tab-btn up-tab-btn-active' : 'up-tab-btn'"
            >
                Assigned Roles & Meta
            </button>
        </div>

        @if ($isSuperAdmin)
            <span class="up-bypass-notice">
                ★ Super Admin privileges bypass all permission checks
            </span>
        @endif
    </div>

    {{-- TAB 1: MODULES & PERMISSIONS --}}
    <div x-show="activeTab === 'modules'" style="display: flex; flex-direction: column; gap: 0.75rem;">
        {{-- Toolbar: Search & Expand/Collapse --}}
        <div class="up-toolbar">
            <div class="up-search-box">
                <svg class="up-search-icon up-icon-sm" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="width: 14px; height: 14px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <input
                    type="text"
                    x-model="moduleSearch"
                    placeholder="Search modules or permissions..."
                    class="up-search-input"
                />
            </div>

            <div style="display: flex; align-items: center; gap: 0.375rem;">
                <button
                    type="button"
                    @click="expandAll()"
                    class="up-btn-toolbar"
                >
                    Expand All
                </button>
                <button
                    type="button"
                    @click="collapseAll()"
                    class="up-btn-toolbar"
                >
                    Collapse All
                </button>
            </div>
        </div>

        {{-- Modules Accordions --}}
        <div class="up-groups-list">
            @foreach ($modules as $groupName => $groupModules)
                <div class="up-group-panel">
                    <h3 class="up-group-title">
                        {{ $groupName }} Modules
                    </h3>

                    <div class="up-modules-container">
                        @foreach ($groupModules as $mod)
                            <div
                                x-show="! moduleSearch || '{{ strtolower($mod['label'] . ' ' . $mod['key'] . ' ' . $groupName) }}'.includes(moduleSearch.toLowerCase())"
                                class="up-module-card"
                            >
                                {{-- Module Row Header --}}
                                <div
                                    @click="toggleModule('{{ $mod['key'] }}')"
                                    class="up-module-header"
                                >
                                    <div class="up-mod-name-group">
                                        <svg
                                            class="up-icon-sm"
                                            width="14" height="14"
                                            :style="expandedModules['{{ $mod['key'] }}'] ? 'transform: rotate(90deg); transition: transform 0.2s;' : 'transition: transform 0.2s;'"
                                            style="width: 14px; height: 14px; min-width: 14px; min-height: 14px; color: #9ca3af;"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                        >
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                        </svg>
                                        <span class="up-mod-title">{{ $mod['label'] }}</span>
                                        <span class="up-mod-key">({{ $mod['key'] }})</span>
                                    </div>

                                    <div>
                                        <span class="up-count-pill {{ $mod['granted_count'] > 0 ? 'up-count-allowed' : 'up-count-none' }}">
                                            {{ $mod['granted_count'] }}/{{ $mod['total_actions'] }} Allowed
                                        </span>
                                    </div>
                                </div>

                                {{-- Expandable Action Badges Grid --}}
                                <div
                                    x-show="expandedModules['{{ $mod['key'] }}'] || moduleSearch.length > 0"
                                    x-collapse
                                >
                                    <div class="up-actions-grid">
                                        @foreach ($mod['actions'] as $actionKey => $act)
                                            <div class="up-action-chip {{ $act['granted'] ? 'up-action-granted' : 'up-action-denied' }}">
                                                <span style="font-weight: 600;">{{ $act['label'] }}</span>
                                                @if ($act['granted'])
                                                    <svg width="14" height="14" viewBox="0 0 20 20" fill="currentColor" class="up-icon-sm" style="width: 14px; height: 14px; min-width: 14px; min-height: 14px; color: #16a34a; flex-shrink: 0;"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                                @else
                                                    <svg width="14" height="14" viewBox="0 0 20 20" fill="currentColor" class="up-icon-sm" style="width: 14px; height: 14px; min-width: 14px; min-height: 14px; color: #9ca3af; flex-shrink: 0;"><path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/></svg>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    {{-- TAB 2: MENU ACCESS TREE --}}
    <div x-show="activeTab === 'menus'" x-cloak style="display: flex; flex-direction: column; gap: 0.75rem;">
        {{-- Search Input --}}
        <div class="up-search-box">
            <svg class="up-search-icon up-icon-sm" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="width: 14px; height: 14px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            <input
                type="text"
                x-model="menuSearch"
                placeholder="Search menus or groups..."
                class="up-search-input"
            />
        </div>

        {{-- Tree Structure --}}
        <div class="up-groups-list">
            @foreach ($menuTree as $groupName => $items)
                <div class="up-group-panel">
                    {{-- Tree Branch: Group Level --}}
                    <div style="display: flex; align-items: center; justify-content: space-between; border-bottom: 1px solid rgba(0, 0, 0, 0.06); padding-bottom: 0.5rem;">
                        <div style="display: flex; align-items: center; gap: 0.5rem;">
                            <svg width="16" height="16" viewBox="0 0 20 20" fill="currentColor" class="up-icon-md" style="width: 16px; height: 16px; min-width: 16px; min-height: 16px; color: #f59e0b; flex-shrink: 0;"><path d="M2 6a2 2 0 012-2h5l2 2h5a2 2 0 012 2v6a2 2 0 01-2 2H4a2 2 0 01-2-2V6z"/></svg>
                            <h4 style="font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; margin: 0;">
                                {{ $groupName }}
                            </h4>
                        </div>
                        <span class="up-user-id-badge">
                            {{ count(array_filter($items, fn ($i) => $i['is_accessible'])) }}/{{ count($items) }} Accessible
                        </span>
                    </div>

                    {{-- Tree Children: Sub-menus --}}
                    <div class="up-tree-grid">
                        @foreach ($items as $item)
                            <div
                                x-show="! menuSearch || '{{ strtolower($item['label'] . ' ' . $item['slug'] . ' ' . $groupName) }}'.includes(menuSearch.toLowerCase())"
                                class="up-tree-item {{ $item['is_accessible'] ? 'up-tree-item-ok' : 'up-tree-item-blocked' }}"
                            >
                                <div style="display: flex; align-items: center; gap: 0.375rem; overflow: hidden;">
                                    <span style="color: #9ca3af;">└─</span>
                                    <div style="overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                        <div style="font-weight: 600; color: inherit;">{{ $item['label'] }}</div>
                                        <div style="font-size: 0.625rem; color: #9ca3af;">{{ $item['type'] }} • {{ $item['permission'] }}</div>
                                    </div>
                                </div>

                                <div>
                                    @if ($item['is_accessible'])
                                        <span class="up-badge-ok">
                                            <svg width="12" height="12" viewBox="0 0 20 20" fill="currentColor" class="up-icon-xs" style="width: 12px; height: 12px; min-width: 12px; min-height: 12px; flex-shrink: 0;"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                            Accessible
                                        </span>
                                    @else
                                        <span class="up-badge-blocked">
                                            <svg width="12" height="12" viewBox="0 0 20 20" fill="currentColor" class="up-icon-xs" style="width: 12px; height: 12px; min-width: 12px; min-height: 12px; flex-shrink: 0;"><path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/></svg>
                                            Restricted
                                        </span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    {{-- TAB 3: ASSIGNED ROLES & DETAILS --}}
    <div x-show="activeTab === 'roles'" x-cloak style="display: flex; flex-direction: column; gap: 0.75rem;">
        {{-- Roles Cards --}}
        <div class="up-roles-grid">
            @forelse ($roles['list'] as $r)
                <div class="up-role-box {{ $r['is_primary'] ? 'up-role-box-primary' : '' }}">
                    <div style="display: flex; align-items: center; justify-content: space-between;">
                        <div style="display: flex; align-items: center; gap: 0.5rem;">
                            <span style="font-size: 0.875rem; font-weight: 700; color: inherit;">{{ $r['display_name'] }}</span>
                            @if ($r['is_primary'])
                                <span style="background: #f59e0b; color: #fff; font-size: 0.625rem; font-weight: 700; padding: 0.1rem 0.375rem; border-radius: 0.25rem;">PRIMARY</span>
                            @endif
                        </div>
                        <span style="font-size: 0.6875rem; color: #9ca3af;">Guard: {{ $r['guard_name'] }}</span>
                    </div>

                    <p style="margin-top: 0.5rem; font-size: 0.75rem; color: #6b7280;">
                        {{ $r['description'] }}
                    </p>

                    <div style="margin-top: 0.75rem; display: flex; align-items: center; justify-content: space-between; border-top: 1px solid rgba(0, 0, 0, 0.06); padding-top: 0.5rem; font-size: 0.6875rem; color: #6b7280;">
                        <span>Associated Permissions:</span>
                        <strong style="color: inherit;">{{ $r['permissions_count'] }}</strong>
                    </div>
                </div>
            @empty
                <div style="grid-column: 1 / -1; border: 1px dashed #d1d5db; border-radius: 0.75rem; padding: 1.5rem; text-align: center; font-size: 0.75rem; color: #9ca3af;">
                    No roles assigned to this user.
                </div>
            @endforelse
        </div>

        {{-- Super Admin Callout --}}
        @if ($isSuperAdmin)
            <div class="up-super-alert">
                <div style="font-weight: 700; font-size: 0.8125rem; margin-bottom: 0.25rem; display: flex; align-items: center; gap: 0.375rem;">
                    <svg width="14" height="14" viewBox="0 0 20 20" fill="currentColor" class="up-icon-sm" style="width: 14px; height: 14px; min-width: 14px; min-height: 14px; color: #d97706; flex-shrink: 0;"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                    Super Administrator Bypass Active
                </div>
                This user has the Super Admin role assigned. Under enterprise access policies via <code>Gate::before</code>, all module permissions, actions, and menu endpoints are authorized automatically without requiring explicit permission checkboxes.
            </div>
        @endif
    </div>
</div>
