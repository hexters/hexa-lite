@if (count($items) > 0)
    <h3 class="mb-4 font-bold text-xl text-primary-600">{{ __($title) }}</h3>
    <ul class="mb-4">
        @foreach ($items as $access)
            <li>
                <div class="flex items-center gap-4">
                    <input @disabled($data?->id == 1 && in_array($access['id'], config('hexa-core.permissions.default'))) x-model="states" type="checkbox" class="fi-checkbox-input rounded border-none bg-white shadow-sm ring-1 transition duration-75 checked:ring-0 focus:ring-2 focus:ring-offset-0 disabled:pointer-events-none disabled:bg-gray-50 disabled:text-gray-50 disabled:checked:bg-current disabled:checked:text-gray-400 dark:bg-white/5 dark:disabled:bg-transparent dark:disabled:checked:bg-gray-600 text-primary-600 ring-gray-950/10 focus:ring-primary-600 checked:focus:ring-primary-500/50 dark:text-primary-500 dark:ring-white/20 dark:checked:bg-primary-500 dark:focus:ring-primary-500 dark:checked:focus:ring-primary-400/50 dark:disabled:ring-white/10" id="{{ $access['id'] }}"
                        value="{{ $access['id'] }}">
                    <label for="{{ $access['id'] }}" class="grow cursor-pointer border-b dark:border-gray-700 py-4">
                        <p class="font-medium">{{ __($access['name']) }}</p>
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            {{ __($access['description']) }}
                        </p>
                    </label>
                </div>
                {{-- SUB PERMISSIONS --}}
                @if (count($access['subs']) > 0)
                    <ul style="margin-left: 2rem;">
                        @foreach ($access['subs'] as $key => $title)
                            <li class="flex items-center gap-4">
                                <input @disabled($data?->id == 1 && in_array($key, config('hexa-core.permissions.default'))) x-model="states" type="checkbox" class="fi-checkbox-input rounded border-none bg-white shadow-sm ring-1 transition duration-75 checked:ring-0 focus:ring-2 focus:ring-offset-0 disabled:pointer-events-none disabled:bg-gray-50 disabled:text-gray-50 disabled:checked:bg-current disabled:checked:text-gray-400 dark:bg-white/5 dark:disabled:bg-transparent dark:disabled:checked:bg-gray-600 text-primary-600 ring-gray-950/10 focus:ring-primary-600 checked:focus:ring-primary-500/50 dark:text-primary-500 dark:ring-white/20 dark:checked:bg-primary-500 dark:focus:ring-primary-500 dark:checked:focus:ring-primary-400/50 dark:disabled:ring-white/10"
                                    id="{{ $key }}" value="{{ $key }}">
                                <label for="{{ $key }}"
                                    class="grow cursor-pointerpy-4">
                                    <p class="font-medium">{{ __($title) }}</p>
                                </label>
                            </li>
                        @endforeach
                    </ul>
                @endif

                @if (count($access['pages']) > 0)
                    <div class="mb-4"></div>
                    <div style="margin-left: 3rem;">
                        @include('filament-hexa::forms.components.checkbox', [
                            'items' => $access['pages'],
                            'data' => $data,
                            'title' => __($access['name']) . ' Pages',
                        ])
                    </div>
                @endif

                @if (count($access['resources']) > 0)
                    <div class="mb-4"></div>
                    <div style="margin-left: 3rem;">
                        @include('filament-hexa::forms.components.checkbox', [
                            'items' => $access['resources'],
                            'data' => $data,
                            'title' => __($access['name']) . ' Resources',
                        ])
                    </div>
                @endif
            </li>
        @endforeach
    </ul>
@endif
