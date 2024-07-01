<x-dynamic-component :component="$getFieldWrapperView()" :field="$field">
    <div x-data="{ state: $wire.$entangle('{{ $getStatePath() }}'), states: {{ ($gates = $getRecord()?->permissions) ? json_encode($gates) : '[]' }} }">
        <div x-init="$watch('states', val => state = val)">

            @include('filament-hexa::forms.components.checkbox', ['items' => $getWidgets(), 'data' => $getRecord(), 'title' => 'Widgets'])

            @include('filament-hexa::forms.components.checkbox', ['items' => $getPages(), 'data' => $getRecord(), 'title' => 'Pages'])
            
            @include('filament-hexa::forms.components.checkbox', ['items' => $getResources(), 'data' => $getRecord(), 'title' => 'Resources'])

            @include('filament-hexa::forms.components.cluster', ['items' => $getClusters(), 'data' => $getRecord(), 'title' => 'Clusters'])

            @include('filament-hexa::forms.components.checkbox', ['items' => $getAdditioinal(), 'data' => $getRecord(), 'title' => 'Additioinals'])
                

        </div>
    </div>
</x-dynamic-component>
