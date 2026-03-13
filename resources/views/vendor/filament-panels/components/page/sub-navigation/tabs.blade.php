@props([
    'navigation',
])

<template x-teleport=".fi-topbar-start">
    <div class="overflow-x-auto self-center md:flex hidden items-center justify-start hide-scrollbar" style="margin-left: 6rem;">
        <x-filament::tabs
            :attributes="\Filament\Support\prepare_inherited_attributes($attributes)->class(['fi-page-sub-navigation-tabs'])"
            style="border: none !important; background: transparent !important; padding: 0 !important; margin: 0 !important; gap: 0.5rem;"
        >
            @foreach ($navigation as $navigationGroup)
                @php
                    $navigationGroupLabel = $navigationGroup->getLabel();
                    $isNavigationGroupActive = $navigationGroup->isActive();
                    $navigationGroupIcon = $navigationGroup->getIcon();
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
                            @foreach ($navigationGroup->getItems() as $navigationItem)
                                @php
                                    $navigationItemBadge = $navigationItem->getBadge();
                                    $navigationItemBadgeColor = $navigationItem->getBadgeColor();
                                    $navigationItemIcon = $navigationItem->isActive() ? ($navigationItem->getActiveIcon() ?? $navigationItem->getIcon()) : $navigationItem->getIcon();
                                    $navigationItemUrl = $navigationItem->getUrl();
                                    $shouldNavigationItemOpenUrlInNewTab = $navigationItem->shouldOpenUrlInNewTab();
                                @endphp

                                <x-filament::dropdown.list.item
                                    :badge="$navigationItemBadge"
                                    :badge-color="$navigationItemBadgeColor"
                                    :href="$navigationItemUrl"
                                    :icon="$navigationItemIcon"
                                    tag="a"
                                    :target="$shouldNavigationItemOpenUrlInNewTab ? '_blank' : null"
                                >
                                    {{ $navigationItem->getLabel() }}

                                    @if ($navigationItemIcon instanceof \Illuminate\Contracts\Support\Htmlable)
                                        <x-slot name="icon">
                                            {{ $navigationItemIcon }}
                                        </x-slot>
                                    @endif
                                </x-filament::dropdown.list.item>
                            @endforeach
                        </x-filament::dropdown.list>
                    </x-filament::dropdown>
                @else
                    @foreach ($navigationGroup->getItems() as $navigationItem)
                        @php
                            $isNavigationItemActive = $navigationItem->isActive();
                            $navigationItemBadge = $navigationItem->getBadge();
                            $navigationItemBadgeColor = $navigationItem->getBadgeColor();
                            $navigationItemIcon = $navigationItem->isActive() ? ($navigationItem->getActiveIcon() ?? $navigationItem->getIcon()) : $navigationItem->getIcon();
                            $navigationItemUrl = $navigationItem->getUrl();
                            $shouldNavigationItemOpenUrlInNewTab = $navigationItem->shouldOpenUrlInNewTab();
                        @endphp

                        <x-filament::tabs.item
                            :active="$isNavigationItemActive"
                            :badge="$navigationItemBadge"
                            :badge-color="$navigationItemBadgeColor"
                            :href="$navigationItemUrl"
                            :icon="$navigationItemIcon"
                            tag="a"
                            :target="$shouldNavigationItemOpenUrlInNewTab ? '_blank' : null"
                        >
                            {{ $navigationItem->getLabel() }}

                            @if ($navigationItemIcon instanceof \Illuminate\Contracts\Support\Htmlable)
                                <x-slot name="icon">
                                    {{ $navigationItemIcon }}
                                </x-slot>
                            @endif
                        </x-filament::tabs.item>
                    @endforeach
                @endif
            @endforeach
        </x-filament::tabs>
    </div>
</template>
