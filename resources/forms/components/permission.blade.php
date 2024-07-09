<x-dynamic-component :component="$getFieldWrapperView()">
    <div x-data="{ state: $wire.$entangle('{{ $getStatePath() }}'), states: {{ ($gates = $getRecord()?->permissions) ? json_encode($gates) : '[]' }} }">
        <div x-init="$watch('states', val => state = val)">

            <div class="flex justify-between items-center mb-4">
                <h3 class="font-bold">{{ __('List of Permissions') }}</h3>
                <x-filament::button tag="a" href="https://github.com/hexters/hexa-docs" target="_blank" icon="heroicon-o-check-circle" size="sm" outlined
                    x-on:click="selectAll('hexa-permission-list')">
                    {{ __('Select All / Deselect All') }}
                </x-filament::button>
            </div>

            @include('filament-hexa::forms.components.checkbox', [
                'items' => $getWidgets(),
                'data' => $getRecord(),
                'title' => 'Widgets',
            ])

            @include('filament-hexa::forms.components.checkbox', [
                'items' => $getPages(),
                'data' => $getRecord(),
                'title' => 'Pages',
            ])

            @include('filament-hexa::forms.components.checkbox', [
                'items' => $getResources(),
                'data' => $getRecord(),
                'title' => 'Resources',
            ])

            @include('filament-hexa::forms.components.cluster', [
                'items' => $getClusters(),
                'data' => $getRecord(),
                'title' => 'Clusters',
            ])

            @include('filament-hexa::forms.components.checkbox', [
                'items' => $getAdditioinal(),
                'data' => $getRecord(),
                'title' => 'Additioinals',
            ])


        </div>
    </div>
</x-dynamic-component>
