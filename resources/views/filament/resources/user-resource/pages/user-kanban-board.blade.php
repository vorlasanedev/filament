@php
    $users = $this->gridUsers;
    $totalCount = $this->gridUsersCount;
    $activeCount = $this->activeUsersCount;
    $inactiveCount = $this->inactiveUsersCount;
    $roles = \Spatie\Permission\Models\Role::orderBy('name')->get();
    $currentView = $this->activeView ?? 'grid';
    $gridPage = $this->gridPage;
    $gridPerPage = $this->gridPerPage;
    $from = $totalCount > 0 ? (($gridPage - 1) * $gridPerPage + 1) : 0;
    $to = min($gridPage * $gridPerPage, $totalCount);
    $maxPage = max(1, (int) ceil($totalCount / $gridPerPage));
    $hasPrevious = $gridPage > 1;
    $hasNext = $gridPage < $maxPage;
@endphp

<div class="fi-user-grid-view">
    <style>
        .user-grid-toolbar {
            margin-top: 4px !important;
            background-color: #ffffff;
            border: 1px solid rgba(0, 0, 0, 0.08);
            border-radius: 0.75rem;
            padding: 0.75rem 1rem;
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
            gap: 0.75rem;
            margin-bottom: 1.25rem;
        }
        .fi-page-header-main-ctn {
            padding-top: 4px !important;
            padding-bottom: 4px !important;
            gap: 4px !important;
        }
        .fi-page-content {
            gap: 4px !important;
        }
        .fi-sc, .fi-sc-has-gap {
            gap: 4px !important;
        }
        .fi-header {
            margin-bottom: 4px !important;
            padding-bottom: 0 !important;
        }
        .dark .user-grid-toolbar {
            background-color: #18181b;
            border-color: rgba(255, 255, 255, 0.1);
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.3);
        }
        .user-grid-toolbar-left {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 0.75rem;
            flex: 1;
        }
        .user-grid-toolbar-right {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        .user-search-wrapper {
            width: 280px;
            max-width: 100%;
        }
        .user-select-wrapper {
            width: 160px;
            max-width: 100%;
        }
        .user-grid-layout {
            display: grid;
            grid-template-columns: repeat(1, minmax(0, 1fr));
            gap: 1.25rem;
        }
        @media (min-width: 640px) {
            .user-grid-layout {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }
        @media (min-width: 1024px) {
            .user-grid-layout {
                grid-template-columns: repeat(3, minmax(0, 1fr));
            }
        }
        @media (min-width: 1280px) {
            .user-grid-layout {
                grid-template-columns: repeat(4, minmax(0, 1fr));
            }
        }
        .user-card-item {
            cursor: pointer;
            background-color: #ffffff;
            border: 1px solid rgba(0, 0, 0, 0.08);
            border-radius: 1rem;
            padding: 1.25rem;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.04);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
        }
        .user-card-item:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.08), 0 8px 10px -6px rgba(0, 0, 0, 0.04);
            border-color: rgba(245, 158, 11, 0.4);
        }
        .dark .user-card-item {
            background-color: #18181b;
            border-color: rgba(255, 255, 255, 0.08);
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.4);
        }
        .dark .user-card-item:hover {
            border-color: rgba(245, 158, 11, 0.5);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.6);
        }
        .user-card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 0.75rem;
        }
        .user-avatar-container {
            position: relative;
            width: 52px;
            height: 52px;
            flex-shrink: 0;
        }
        .user-avatar-img {
            width: 52px;
            height: 52px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid rgba(0, 0, 0, 0.05);
        }
        .dark .user-avatar-img {
            border-color: rgba(255, 255, 255, 0.1);
        }
        .user-avatar-status-dot {
            position: absolute;
            bottom: 0;
            right: 0;
            width: 14px;
            height: 14px;
            border-radius: 50%;
            border: 2px solid #ffffff;
        }
        .dark .user-avatar-status-dot {
            border-color: #18181b;
        }
        .status-dot-active {
            background-color: #10b981;
        }
        .status-dot-inactive {
            background-color: #9ca3af;
        }
        .user-card-status-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
            padding: 0.25rem 0.625rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
            cursor: pointer;
            transition: opacity 0.15s ease;
            user-select: none;
            border: none;
        }
        .user-card-status-badge:hover {
            opacity: 0.8;
        }
        .badge-active {
            background-color: #ecfdf5;
            color: #047857;
        }
        .dark .badge-active {
            background-color: rgba(16, 185, 129, 0.15);
            color: #34d399;
        }
        .badge-inactive {
            background-color: #f3f4f6;
            color: #4b5563;
        }
        .dark .badge-inactive {
            background-color: rgba(255, 255, 255, 0.1);
            color: #9ca3af;
        }
        .user-card-body {
            margin-top: 0.875rem;
        }
        .user-card-name {
            font-size: 0.9375rem;
            font-weight: 700;
            color: #111827;
            margin: 0 0 0.35rem 0;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .dark .user-card-name {
            color: #f9fafb;
        }
        .user-card-info-row {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: #6b7280;
            font-size: 0.75rem;
            margin-top: 0.25rem;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        .dark .user-card-info-row {
            color: #9ca3af;
        }
        .user-card-footer {
            margin-top: 1rem;
            padding-top: 0.75rem;
            border-top: 1px solid rgba(0, 0, 0, 0.05);
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 0.375rem;
        }
        .dark .user-card-footer {
            border-top-color: rgba(255, 255, 255, 0.06);
        }
        .user-role-pill {
            display: inline-flex;
            align-items: center;
            padding: 0.15rem 0.5rem;
            border-radius: 0.375rem;
            font-size: 0.6875rem;
            font-weight: 600;
            background-color: #fef3c7;
            color: #b45309;
        }
        .dark .user-role-pill {
            background-color: rgba(245, 158, 11, 0.15);
            color: #fbbf24;
        }
        .user-no-role {
            font-size: 0.6875rem;
            color: #9ca3af;
            font-style: italic;
        }
    </style>

    {{-- Modern Toolbar Section --}}
    <div class="user-grid-toolbar">
        <div class="user-grid-toolbar-left">
            {{-- Search Bar with Filament Input Wrapper --}}
            <div class="user-search-wrapper">
                <x-filament::input.wrapper
                    inline-prefix
                    :prefix-icon="\Filament\Support\Icons\Heroicon::MagnifyingGlass"
                >
                    <x-filament::input
                        type="search"
                        placeholder="Search name, email, phone..."
                        wire:model.live.debounce.300ms="kanbanSearch"
                    />
                </x-filament::input.wrapper>
            </div>

            {{-- Spatie Role Select with Filament Input Wrapper --}}
            <div class="user-select-wrapper">
                <x-filament::input.wrapper>
                    <x-filament::input.select wire:model.live="kanbanRole">
                        <option value="">All Roles</option>
                        @foreach ($roles as $role)
                            <option value="{{ $role->name }}">{{ ucfirst(str_replace('_', ' ', $role->name)) }}</option>
                        @endforeach
                    </x-filament::input.select>
                </x-filament::input.wrapper>
            </div>

            {{-- Status Select with Filament Input Wrapper --}}
            <div class="user-select-wrapper">
                <x-filament::input.wrapper>
                    <x-filament::input.select wire:model.live="kanbanStatus">
                        <option value="">All Status</option>
                        <option value="active">Active ({{ number_format($activeCount) }})</option>
                        <option value="inactive">Inactive ({{ number_format($inactiveCount) }})</option>
                    </x-filament::input.select>
                </x-filament::input.wrapper>
            </div>

            @if ($this->kanbanSearch || $this->kanbanRole || $this->kanbanStatus)
                <button
                    type="button"
                    wire:click="$set('kanbanSearch', ''); $set('kanbanRole', null); $set('kanbanStatus', null);"
                    class="text-xs font-semibold text-danger-600 hover:text-danger-500 dark:text-danger-400 transition"
                    style="cursor: pointer; background: none; border: none; padding: 0;"
                >
                    Reset filters
                </button>
            @endif
        </div>

        {{-- Toolbar Right Side: Pagination & View Switcher Icons --}}
        <div class="user-grid-toolbar-right">
            {{-- Pagination: "1–48 of 1,000,002 < >" --}}
            <div style="display: flex; align-items: center; gap: 0.5rem;" class="text-sm text-gray-600 dark:text-gray-300">
                <span style="user-select: none; font-variant-numeric: tabular-nums;">
                    {{ number_format($from) }}–{{ number_format($to) }} of {{ number_format($totalCount) }}
                </span>

                <div style="display: flex; align-items: center; gap: 0.125rem;">
                    <button
                        type="button"
                        wire:click="previousGridPage"
                        @if (! $hasPrevious) disabled @endif
                        style="background: none; border: none; padding: 4px; display: inline-flex; align-items: center; justify-content: center; border-radius: 6px; cursor: {{ $hasPrevious ? 'pointer' : 'not-allowed' }}; color: {{ $hasPrevious ? 'inherit' : '#9ca3af' }}; opacity: {{ $hasPrevious ? '1' : '0.4' }};"
                        title="Previous page"
                    >
                        <x-filament::icon icon="heroicon-m-chevron-left" class="h-5 w-5" />
                    </button>

                    <button
                        type="button"
                        wire:click="nextGridPage"
                        @if (! $hasNext) disabled @endif
                        style="background: none; border: none; padding: 4px; display: inline-flex; align-items: center; justify-content: center; border-radius: 6px; cursor: {{ $hasNext ? 'pointer' : 'not-allowed' }}; color: {{ $hasNext ? 'inherit' : '#9ca3af' }}; opacity: {{ $hasNext ? '1' : '0.4' }};"
                        title="Next page"
                    >
                        <x-filament::icon icon="heroicon-m-chevron-right" class="h-5 w-5" />
                    </button>
                </div>
            </div>

            <div class="flex items-center gap-x-1 border-s border-gray-200 ps-3 dark:border-white/10">
                <x-filament::icon-button
                    icon="heroicon-m-list-bullet"
                    color="gray"
                    label="List view"
                    tooltip="List view"
                    wire:click="$set('activeView', 'list')"
                />

                <x-filament::icon-button
                    icon="heroicon-m-squares-2x2"
                    color="primary"
                    label="Grid view"
                    tooltip="Grid view"
                    wire:click="$set('activeView', 'grid')"
                    class="rounded-lg bg-gray-100 dark:bg-white/10"
                />
            </div>
        </div>
    </div>

    {{-- 4-Column Responsive Grid --}}
    <div class="user-grid-layout">
        @forelse ($users as $user)
            <div
                class="user-card-item"
                wire:click="mountAction('editUser', { id: {{ $user->id }} })"
                style="cursor: pointer;"
            >
                {{-- Card Header: Avatar on left, Status Badge on right --}}
                <div class="user-card-header">
                    <div class="user-avatar-container">
                        <img
                            src="{{ $user->getFilamentAvatarUrl() ?? 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=' . ($user->is_active ? '10b981' : '9ca3af') . '&color=fff&bold=true' }}"
                            alt="{{ $user->name }}"
                            class="user-avatar-img"
                        />
                        <span class="user-avatar-status-dot {{ $user->is_active ? 'status-dot-active' : 'status-dot-inactive' }}"></span>
                    </div>

                    {{-- Clickable Status Badge --}}
                    <button
                        type="button"
                        wire:click.stop="updateUserStatus({{ $user->id }}, {{ $user->is_active ? 'false' : 'true' }})"
                        class="user-card-status-badge {{ $user->is_active ? 'badge-active' : 'badge-inactive' }}"
                        title="Click to toggle status"
                    >
                        <span style="width: 6px; height: 6px; border-radius: 50%; display: inline-block; background-color: {{ $user->is_active ? '#10b981' : '#9ca3af' }};"></span>
                        {{ $user->is_active ? 'Active' : 'Inactive' }}
                    </button>
                </div>

                {{-- Card Body: User Info --}}
                <div class="user-card-body">
                    <h4 class="user-card-name" title="{{ $user->name }}">
                        {{ $user->name }}
                    </h4>

                    <div class="user-card-info-row" title="{{ $user->email }}">
                        <x-filament::icon icon="heroicon-m-envelope" class="h-3.5 w-3.5 shrink-0 text-gray-400 dark:text-gray-500" />
                        <span style="overflow: hidden; text-overflow: ellipsis;">{{ $user->email }}</span>
                    </div>

                    <div class="user-card-info-row">
                        <x-filament::icon icon="heroicon-m-phone" class="h-3.5 w-3.5 shrink-0 text-gray-400 dark:text-gray-500" />
                        <span>{{ $user->phone ?: 'No phone' }}</span>
                    </div>
                </div>

                {{-- Card Footer: Role Badges --}}
                <div class="user-card-footer">
                    @forelse ($user->roles as $role)
                        <span class="user-role-pill">
                            {{ ucfirst(str_replace('_', ' ', $role->name)) }}
                        </span>
                    @empty
                        <span class="user-no-role">No role</span>
                    @endforelse
                </div>
            </div>
        @empty
            <div style="grid-column: 1 / -1; display: flex; flex-direction: column; align-items: center; justify-content: center; border: 2px dashed #e5e7eb; border-radius: 1rem; padding: 3rem; text-align: center;">
                <x-filament::icon icon="heroicon-o-user-group" class="h-12 w-12 text-gray-300 dark:text-gray-600 mb-3" />
                <h3 style="font-size: 0.875rem; font-weight: 600; color: #111827;" class="dark:text-white">No users found</h3>
                <p style="font-size: 0.75rem; color: #6b7280; margin-top: 0.25rem;" class="dark:text-gray-400">Try adjusting your search query or filters.</p>
            </div>
        @endforelse
    </div>

    {{-- Bottom Pagination Bar --}}
    @if ($totalCount > 0)
        <div style="display: flex; align-items: center; justify-content: flex-end; padding: 1rem 0.5rem; margin-top: 0.5rem;">
            <div style="display: flex; align-items: center; gap: 0.5rem;" class="text-sm text-gray-600 dark:text-gray-300">
                <span style="user-select: none; font-variant-numeric: tabular-nums;">
                    {{ number_format($from) }}–{{ number_format($to) }} of {{ number_format($totalCount) }}
                </span>

                <div style="display: flex; align-items: center; gap: 0.125rem;">
                    <button
                        type="button"
                        wire:click="previousGridPage"
                        @if (! $hasPrevious) disabled @endif
                        style="background: none; border: none; padding: 4px; display: inline-flex; align-items: center; justify-content: center; border-radius: 6px; cursor: {{ $hasPrevious ? 'pointer' : 'not-allowed' }}; color: {{ $hasPrevious ? 'inherit' : '#9ca3af' }}; opacity: {{ $hasPrevious ? '1' : '0.4' }};"
                        title="Previous page"
                    >
                        <x-filament::icon icon="heroicon-m-chevron-left" class="h-5 w-5" />
                    </button>

                    <button
                        type="button"
                        wire:click="nextGridPage"
                        @if (! $hasNext) disabled @endif
                        style="background: none; border: none; padding: 4px; display: inline-flex; align-items: center; justify-content: center; border-radius: 6px; cursor: {{ $hasNext ? 'pointer' : 'not-allowed' }}; color: {{ $hasNext ? 'inherit' : '#9ca3af' }}; opacity: {{ $hasNext ? '1' : '0.4' }};"
                        title="Next page"
                    >
                        <x-filament::icon icon="heroicon-m-chevron-right" class="h-5 w-5" />
                    </button>
                </div>
            </div>
        </div>
    @endif

    {{-- Modals Container for Edit Action --}}
    <x-filament-actions::modals />
</div>
