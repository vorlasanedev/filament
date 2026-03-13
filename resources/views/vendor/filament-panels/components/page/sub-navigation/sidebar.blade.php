@props([
    'navigation',
])

<template x-teleport=".fi-topbar-start">
    <div class="overflow-x-auto self-center md:flex hidden items-center justify-start hide-scrollbar" style="margin-left: 6rem;">
        <x-filament::tabs
            style="border: none !important; background: transparent !important; padding: 0 !important; margin: 0 !important; gap: 0.5rem;"
        >
            @foreach ($navigation as $navigationGroup)
                @php
                    $isNavigationGroupActive = $navigationGroup->isActive();
                    $navigationGroupIcon = $navigationGroup->getIcon();
                    $navigationGroupItems = $navigationGroup->getItems();
                    $navigationGroupLabel = $navigationGroup->getLabel();
                @endphp

                @if ($navigationGroupLabel)
                    <x-filament::dropdown placement="bottom-start">
                        <x-slot name="trigger">
                            <x-filament::tabs.item
                                :active="$isNavigationGroupActive"
                                :icon="$navigationGroupIcon"
                            >
                                {{ $navigationGroupLabel }}
                            </x-filament::tabs.item>
                        </x-slot>

                        <x-filament::dropdown.list>
                            @foreach ($navigationGroupItems as $navigationItem)
                                @php
                                    $isNavigationItemActive = $navigationItem->isActive();
                                    $navigationItemIcon = $isNavigationItemActive ? ($navigationItem->getActiveIcon() ?? $navigationItem->getIcon()) : $navigationItem->getIcon();
                                    $navigationItemUrl = $navigationItem->getUrl();
                                    $shouldNavigationItemOpenUrlInNewTab = $navigationItem->shouldOpenUrlInNewTab();
                                @endphp

                                <x-filament::dropdown.list.item
                                    :href="$navigationItemUrl"
                                    :icon="$navigationItemIcon"
                                    tag="a"
                                    :target="$shouldNavigationItemOpenUrlInNewTab ? '_blank' : null"
                                    :color="$isNavigationItemActive ? 'primary' : 'gray'"
                                >
                                    {{ $navigationItem->getLabel() }}
                                </x-filament::dropdown.list.item>
                            @endforeach
                        </x-filament::dropdown.list>
                    </x-filament::dropdown>
                @else
                    @foreach ($navigationGroupItems as $navigationItem)
                        @php
                            $isNavigationItemActive = $navigationItem->isActive();
                            $navigationItemIcon = $isNavigationItemActive ? ($navigationItem->getActiveIcon() ?? $navigationItem->getIcon()) : $navigationItem->getIcon();
                            $navigationItemUrl = $navigationItem->getUrl();
                            $shouldNavigationItemOpenUrlInNewTab = $navigationItem->shouldOpenUrlInNewTab();
                        @endphp

                        <x-filament::tabs.item
                            :active="$isNavigationItemActive"
                            :href="$navigationItemUrl"
                            :icon="$navigationItemIcon"
                            tag="a"
                            :target="$shouldNavigationItemOpenUrlInNewTab ? '_blank' : null"
                        >
                            {{ $navigationItem->getLabel() }}
                        </x-filament::tabs.item>
                    @endforeach
                @endif
            @endforeach
        </x-filament::tabs>
    </div>
</template>

