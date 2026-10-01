@php
    $statePath = $getStatePath();
    $discovered = $getDiscoveredData();
    $models = $discovered['models'];
    $pages = $discovered['pages'];
    $clusters = $discovered['clusters'];
    $widgets = $discovered['widgets'];
    $modelActions = $getModelActions();
@endphp

<div
    x-data="{
        state: $wire.$entangle('{{ $statePath }}'),
        activeTab: 'models',
        searchQuery: '',

        init() {
            if (! Array.isArray(this.state)) {
                this.state = [];
            }
        },

        isChecked(permission) {
            return Array.isArray(this.state) && this.state.includes(permission);
        },

        toggle(permission) {
            if (! Array.isArray(this.state)) {
                this.state = [];
            }
            if (this.isChecked(permission)) {
                this.state = this.state.filter(p => p !== permission);
            } else {
                this.state.push(permission);
            }
        },

        toggleRow(perms) {
            const allChecked = perms.every(p => this.isChecked(p));
            if (allChecked) {
                this.state = this.state.filter(p => ! perms.includes(p));
            } else {
                perms.forEach(p => {
                    if (! this.isChecked(p)) {
                        this.state.push(p);
                    }
                });
            }
        },

        isRowChecked(perms) {
            return perms.length > 0 && perms.every(p => this.isChecked(p));
        },

        toggleColumn(action, allModelPerms) {
            const columnPerms = [];
            Object.values(allModelPerms).forEach(m => {
                if (m.permissions[action]) {
                    columnPerms.push(m.permissions[action]);
                }
            });

            const allChecked = columnPerms.every(p => this.isChecked(p));
            if (allChecked) {
                this.state = this.state.filter(p => ! columnPerms.includes(p));
            } else {
                columnPerms.forEach(p => {
                    if (! this.isChecked(p)) {
                        this.state.push(p);
                    }
                });
            }
        },

        selectAll(allPerms) {
            allPerms.forEach(p => {
                if (! this.isChecked(p)) {
                    this.state.push(p);
                }
            });
        },

        deselectAll() {
            this.state = [];
        }
    }"
    class="space-y-4 rounded-xl border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-800 dark:bg-gray-900"
>
    {{-- Tabs Header & Global Actions --}}
    <div class="flex flex-wrap items-center justify-between gap-3 border-b border-gray-200 pb-3 dark:border-gray-800">
        <div class="flex items-center gap-2">
            <button
                type="button"
                @click="activeTab = 'models'"
                :class="activeTab === 'models' ? 'bg-amber-500 text-white dark:bg-amber-600' : 'bg-gray-100 text-gray-700 hover:bg-gray-200 dark:bg-gray-800 dark:text-gray-300'"
                class="rounded-lg px-3 py-1.5 text-xs font-semibold transition"
            >
                Models & Resources ({{ count($models) }})
            </button>
            <button
                type="button"
                @click="activeTab = 'pages'"
                :class="activeTab === 'pages' ? 'bg-amber-500 text-white dark:bg-amber-600' : 'bg-gray-100 text-gray-700 hover:bg-gray-200 dark:bg-gray-800 dark:text-gray-300'"
                class="rounded-lg px-3 py-1.5 text-xs font-semibold transition"
            >
                Pages & Clusters ({{ count($pages) + count($clusters) }})
            </button>
            <button
                type="button"
                @click="activeTab = 'widgets'"
                :class="activeTab === 'widgets' ? 'bg-amber-500 text-white dark:bg-amber-600' : 'bg-gray-100 text-gray-700 hover:bg-gray-200 dark:bg-gray-800 dark:text-gray-300'"
                class="rounded-lg px-3 py-1.5 text-xs font-semibold transition"
            >
                Widgets ({{ count($widgets) }})
            </button>
        </div>

        <div class="flex items-center gap-2">
            <input
                type="text"
                x-model="searchQuery"
                placeholder="Filter models..."
                class="h-8 w-44 rounded-lg border border-gray-300 px-2.5 text-xs focus:border-amber-500 focus:outline-none dark:border-gray-700 dark:bg-gray-800 dark:text-gray-200"
            />
            <button
                type="button"
                @click="selectAll({{ json_encode($discovered['all_permission_names']) }})"
                class="rounded-lg border border-gray-300 bg-white px-2.5 py-1 text-xs font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700"
            >
                Select All
            </button>
            <button
                type="button"
                @click="deselectAll()"
                class="rounded-lg border border-gray-300 bg-white px-2.5 py-1 text-xs font-medium text-red-600 hover:bg-red-50 dark:border-gray-700 dark:bg-gray-800 dark:text-red-400 dark:hover:bg-red-950/40"
            >
                Deselect All
            </button>
        </div>
    </div>

    {{-- TAB 1: Models & Resources Matrix --}}
    <div x-show="activeTab === 'models'" class="overflow-x-auto">
        <table class="w-full text-left text-xs text-gray-700 dark:text-gray-300">
            <thead class="border-b border-gray-200 bg-gray-50 text-[11px] font-semibold uppercase tracking-wider text-gray-600 dark:border-gray-800 dark:bg-gray-800/60 dark:text-gray-400">
                <tr>
                    <th scope="col" class="py-2.5 px-3">Model</th>
                    <th scope="col" class="py-2.5 px-2 text-center" title="Toggle entire row">All</th>
                    @foreach ($modelActions as $actionKey => $actionLabel)
                        <th scope="col" class="py-2.5 px-2 text-center whitespace-nowrap">
                            <button
                                type="button"
                                @click="toggleColumn('{{ $actionKey }}', {{ json_encode($models) }})"
                                class="hover:text-amber-600 underline decoration-dotted underline-offset-2 transition"
                                title="Click to toggle column for all models"
                            >
                                {{ $actionLabel }}
                            </button>
                        </th>
                    @endforeach
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                @foreach ($models as $mKey => $mData)
                    @php
                        $rowPerms = array_values($mData['permissions']);
                    @endphp
                    <tr
                        x-show="! searchQuery || '{{ strtolower($mData['label'] . ' ' . $mData['name']) }}'.includes(searchQuery.toLowerCase())"
                        class="hover:bg-gray-50/70 transition dark:hover:bg-gray-800/40"
                    >
                        {{-- Model Label --}}
                        <td class="py-2 px-3 font-medium text-gray-900 dark:text-white whitespace-nowrap">
                            <div class="flex items-center gap-1.5">
                                <span class="font-semibold">{{ $mData['label'] }}</span>
                                <span class="text-[10px] text-gray-400 dark:text-gray-500">({{ $mData['key'] }})</span>
                            </div>
                        </td>

                        {{-- Row Select All --}}
                        <td class="py-2 px-2 text-center">
                            <input
                                type="checkbox"
                                :checked="isRowChecked({{ json_encode($rowPerms) }})"
                                @click="toggleRow({{ json_encode($rowPerms) }})"
                                class="h-4 w-4 rounded border-gray-300 text-amber-600 focus:ring-amber-500 dark:border-gray-700 dark:bg-gray-800 cursor-pointer"
                                title="Toggle all permissions for {{ $mData['label'] }}"
                            />
                        </td>

                        {{-- Action Columns --}}
                        @foreach ($modelActions as $aKey => $aLabel)
                            @php
                                $perm = $mData['permissions'][$aKey] ?? null;
                            @endphp
                            <td class="py-2 px-2 text-center">
                                @if ($perm)
                                    <input
                                        type="checkbox"
                                        :checked="isChecked('{{ $perm }}')"
                                        @click="toggle('{{ $perm }}')"
                                        class="h-4 w-4 rounded border-gray-300 text-amber-600 focus:ring-amber-500 dark:border-gray-700 dark:bg-gray-800 cursor-pointer"
                                        title="{{ $perm }}"
                                    />
                                @else
                                    <span class="text-gray-300 dark:text-gray-700">-</span>
                                @endif
                            </td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- TAB 2: Pages & Clusters --}}
    <div x-show="activeTab === 'pages'" x-cloak class="space-y-4">
        {{-- Clusters --}}
        @if (count($clusters))
            <div class="rounded-lg border border-gray-200 p-3 dark:border-gray-800">
                <h4 class="mb-2 text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300">Clusters (Navigation Groups)</h4>
                <div class="grid grid-cols-1 gap-2 sm:grid-cols-2 md:grid-cols-3">
                    @foreach ($clusters as $cKey => $cData)
                        <label class="flex items-center gap-2 rounded-md border border-gray-100 p-2 text-xs hover:bg-gray-50 dark:border-gray-800 dark:hover:bg-gray-800/50 cursor-pointer">
                            <input
                                type="checkbox"
                                :checked="isChecked('{{ $cData['permissions']['menu'] }}')"
                                @click="toggle('{{ $cData['permissions']['menu'] }}')"
                                class="h-4 w-4 rounded border-gray-300 text-amber-600 focus:ring-amber-500 dark:border-gray-700 dark:bg-gray-800"
                            />
                            <div>
                                <span class="font-medium text-gray-900 dark:text-gray-200">{{ $cData['label'] }}</span>
                                <span class="block text-[10px] text-gray-400">{{ $cData['permissions']['menu'] }}</span>
                            </div>
                        </label>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- Pages --}}
        @if (count($pages))
            <div class="rounded-lg border border-gray-200 p-3 dark:border-gray-800">
                <h4 class="mb-2 text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300">Pages</h4>
                <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($pages as $pKey => $pData)
                        <div class="rounded-md border border-gray-100 p-2.5 text-xs dark:border-gray-800 dark:bg-gray-800/30">
                            <div class="mb-2 font-semibold text-gray-900 dark:text-gray-200">{{ $pData['label'] }}</div>
                            <div class="flex flex-wrap gap-3">
                                @foreach ($pData['permissions'] as $pAction => $pPerm)
                                    <label class="flex items-center gap-1.5 cursor-pointer">
                                        <input
                                            type="checkbox"
                                            :checked="isChecked('{{ $pPerm }}')"
                                            @click="toggle('{{ $pPerm }}')"
                                            class="h-3.5 w-3.5 rounded border-gray-300 text-amber-600 focus:ring-amber-500 dark:border-gray-700 dark:bg-gray-800"
                                        />
                                        <span class="text-[11px] text-gray-600 dark:text-gray-400">{{ ucfirst($pAction) }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>

    {{-- TAB 3: Widgets --}}
    <div x-show="activeTab === 'widgets'" x-cloak>
        <div class="rounded-lg border border-gray-200 p-3 dark:border-gray-800">
            <h4 class="mb-2 text-xs font-bold uppercase tracking-wider text-gray-700 dark:text-gray-300">Widgets Visibility</h4>
            <div class="grid grid-cols-1 gap-2 sm:grid-cols-2 md:grid-cols-3">
                @foreach ($widgets as $wKey => $wData)
                    <label class="flex items-center gap-2 rounded-md border border-gray-100 p-2 text-xs hover:bg-gray-50 dark:border-gray-800 dark:hover:bg-gray-800/50 cursor-pointer">
                        <input
                            type="checkbox"
                            :checked="isChecked('{{ $wData['permissions']['view'] }}')"
                            @click="toggle('{{ $wData['permissions']['view'] }}')"
                            class="h-4 w-4 rounded border-gray-300 text-amber-600 focus:ring-amber-500 dark:border-gray-700 dark:bg-gray-800"
                        />
                        <div>
                            <span class="font-medium text-gray-900 dark:text-gray-200">{{ $wData['label'] }}</span>
                            <span class="block text-[10px] text-gray-400">{{ $wData['permissions']['view'] }}</span>
                        </div>
                    </label>
                @endforeach
            </div>
        </div>
    </div>
</div>
