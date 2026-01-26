<div class="fi-logo-flex flex items-center justify-center h-full w-full gap-x-2">
    <img src="{{ asset('images/logo.png') }}" alt="Logo" class="fi-logo-light" style="display: block; height: 2rem; width: auto;" />
    <img src="{{ asset('images/logo-dark.png') }}" alt="Logo" class="fi-logo-dark" style="display: none; height: 2rem; width: auto;" />
</div>

<style>
    :root.dark .fi-logo-light, .dark .fi-logo-light { display: none !important; }
    :root.dark .fi-logo-dark, .dark .fi-logo-dark { display: block !important; }
</style>
