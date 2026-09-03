@php
    $currentView = $this->activeView ?? 'list';
    $isGrid = in_array($currentView, ['grid', 'kanban']);
@endphp

<div class="fi-ta-view-toggle flex items-center gap-x-1 border-s border-gray-200 ps-1 ms-1 dark:border-white/10">
    <x-filament::icon-button
        icon="heroicon-m-list-bullet"
        :color="!$isGrid ? 'primary' : 'gray'"
        label="List view"
        tooltip="List view"
        wire:click="$set('activeView', 'list')"
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
        @class([
            'rounded-lg',
            'bg-gray-100 dark:bg-white/10' => $isGrid,
        ])
    />
</div>
