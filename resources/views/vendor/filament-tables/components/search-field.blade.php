@php
    use Illuminate\View\ComponentAttributeBag;
@endphp

@props([
    'debounce' => '500ms',
    'onBlur' => false,
    'placeholder' => __('filament-tables::table.fields.search.placeholder'),
    'wireModel' => 'tableSearch',
])

@php
    $wireModelAttribute = $onBlur ? 'wire:model.blur' : "wire:model.live.debounce.{$debounce}";
@endphp

<div
    x-data="{
        hasHeaderTarget: false,
        init() {
            this.hasHeaderTarget = !!document.getElementById('page-header-search-target');
        }
    }"
    class="fi-ta-search-field-ctn"
>
    <template x-if="hasHeaderTarget">
        <template x-teleport="#page-header-search-target">
            <div
                x-id="['input']"
                {{ $attributes->class(['fi-ta-search-field', 'fi-header-search-field']) }}
                style="width: 100%;"
            >
                <label x-bind:for="$id('input')" class="fi-sr-only">
                    {{ __('filament-tables::table.fields.search.label') }}
                </label>

                <x-filament::input.wrapper
                    inline-prefix
                    :prefix-icon="\Filament\Support\Icons\Heroicon::MagnifyingGlass"
                    :prefix-icon-alias="\Filament\Tables\View\TablesIconAlias::SEARCH_FIELD"
                    :wire:target="$wireModel"
                >
                    <x-filament::input
                        :attributes="
                            (new ComponentAttributeBag)->merge([
                                'autocomplete' => 'off',
                                'inlinePrefix' => true,
                                'maxlength' => 1000,
                                'placeholder' => $placeholder,
                                'type' => 'search',
                                'wire:key' => $this->getId() . '.table.' . $wireModel . '.field.input',
                                $wireModelAttribute => $wireModel,
                                'x-bind:id' => '$id(\'input\')',
                                'x-on:keyup' => 'if ($event.key === \'Enter\') { $wire.$refresh() }',
                            ], escape: false)
                        "
                    />
                </x-filament::input.wrapper>
            </div>
        </template>
    </template>

    <template x-if="!hasHeaderTarget">
        <div
            x-id="['input']"
            {{ $attributes->class(['fi-ta-search-field']) }}
        >
            <label x-bind:for="$id('input')" class="fi-sr-only">
                {{ __('filament-tables::table.fields.search.label') }}
            </label>

            <x-filament::input.wrapper
                inline-prefix
                :prefix-icon="\Filament\Support\Icons\Heroicon::MagnifyingGlass"
                :prefix-icon-alias="\Filament\Tables\View\TablesIconAlias::SEARCH_FIELD"
                :wire:target="$wireModel"
            >
                <x-filament::input
                    :attributes="
                        (new ComponentAttributeBag)->merge([
                            'autocomplete' => 'off',
                            'inlinePrefix' => true,
                            'maxlength' => 1000,
                            'placeholder' => $placeholder,
                            'type' => 'search',
                            'wire:key' => $this->getId() . '.table.' . $wireModel . '.field.input',
                            $wireModelAttribute => $wireModel,
                            'x-bind:id' => '$id(\'input\')',
                            'x-on:keyup' => 'if ($event.key === \'Enter\') { $wire.$refresh() }',
                        ], escape: false)
                    "
                />
            </x-filament::input.wrapper>
        </div>
    </template>
</div>
