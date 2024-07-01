<x-filament-panels::page>
    <form wire:submit="updateOptions">
        @if (count($this->form->getComponents()) > 0)
            <div class="mb-4">

                {{ $this->form }}
            </div>
            <x-filament::button type="submit">
                Update Options
            </x-filament::button>
        @else
            <p>Currently there is no option that can be setup</p>
            
            @if (config('app.debug'))
                <div class="mb-4"></div>
                <p>See the full documentation at <a class="underline font-bold"
                        href="https://github.com/hexters/hexa-docs?tab=readme-ov-file#options-setting"
                        target="_blank">Option Setting Documentation &rarr;</a></p>
            @endif
        @endif

    </form>
</x-filament-panels::page>
