<div
    x-data="{
        allChecked: false,
        toggleAll() {
            this.allChecked = !this.allChecked

            const form = this.$el.closest('form')
            if (!form) return

            const targetText = this.allChecked ? 'select all' : 'deselect all'

            // Find all elements that might be clickable toggle buttons
            // Filament renders these as various elements (span, button, a) with click handlers
            const clickableSelectors = [
                'span[x-on\\:click]',
                'span[\\@click]',
                'button[x-on\\:click]',
                'button[\\@click]',
                'a[x-on\\:click]',
                'a[\\@click]',
                '[wire\\:click]',
            ].join(', ')

            const clickables = form.querySelectorAll(clickableSelectors)

            clickables.forEach(el => {
                // Skip if this is our own toggle button
                if (this.$el.contains(el)) return

                const text = el.textContent.trim().toLowerCase()
                if (text === targetText) {
                    el.click()
                }
            })
        }
    }"
    class="inline-flex items-center gap-2 cursor-pointer select-none"
>
    <span
        @click="toggleAll()"
        class="text-sm font-medium text-primary-600 hover:text-primary-500 dark:text-primary-400 dark:hover:text-primary-300 cursor-pointer"
    >
        <span x-show="!allChecked">{{ __('Select All Permissions') }}</span>
        <span x-show="allChecked" x-cloak>{{ __('Deselect All Permissions') }}</span>
    </span>
</div>