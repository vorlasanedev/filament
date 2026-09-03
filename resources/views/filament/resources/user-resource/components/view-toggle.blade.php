@php
    $currentView = $this->activeView ?? 'list';
    $isGrid = in_array($currentView, ['grid', 'kanban']);
@endphp

<div
    class="fi-ta-view-toggle border-s border-gray-200 ps-1 ms-1 dark:border-white/10"
    style="display: inline-flex !important; flex-direction: row !important; align-items: center !important; gap: 0.25rem !important; white-space: nowrap !important;"
>
    <x-filament::icon-button
        icon="heroicon-m-list-bullet"
        :color="!$isGrid ? 'primary' : 'gray'"
        label="List view"
        tooltip="List view"
        wire:click="$set('activeView', 'list')"
        style="display: inline-flex !important;"
        @class([
            'rounded-lg',
            'bg-gray-100 dark:bg-white/10' => !$isGrid,
        ])
    />

    <x-filament::icon-button
        icon="heroicon-m-squares-2x2"
        :color="$isGrid ? 'primary' : 'gray'"
        label="Grid view"
        tooltip="Grid view"
        wire:click="$set('activeView', 'grid')"
        style="display: inline-flex !important;"
        @class([
            'rounded-lg',
            'bg-gray-100 dark:bg-white/10' => $isGrid,
        ])
    />
</div>
