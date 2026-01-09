<div
    x-data="{
        allChecked: false,
        init() {
            this.updateState()
        },
        toggleAll() {
            this.allChecked = !this.allChecked

            // Find all checkbox inputs within the form
            const form = this.$el.closest('form')
            if (!form) return

            // Get all checkboxes that are part of the gates (permission checkboxes)
            const checkboxes = form.querySelectorAll('input[type=checkbox][id*=gates]')

            checkboxes.forEach(checkbox => {
                if (checkbox.checked !== this.allChecked) {
                    checkbox.checked = this.allChecked
                    checkbox.dispatchEvent(new Event('change', { bubbles: true }))
                }
            })
        },
        updateState() {
            const form = this.$el.closest('form')
            if (!form) return

            const checkboxes = form.querySelectorAll('input[type=checkbox][id*=gates]')
            if (checkboxes.length === 0) {
                this.allChecked = false
                return
            }

            const checkedCount = Array.from(checkboxes).filter(cb => cb.checked).length
            this.allChecked = checkedCount === checkboxes.length && checkboxes.length > 0
        }
    }"
    x-init="init()"
    @change.window="updateState()"
    class="flex items-center gap-2 cursor-pointer select-none"
    @click="toggleAll()"
>
    <x-filament::input.checkbox
        x-bind:checked="allChecked"
        x-on:click.stop="toggleAll()"
    />

    <span class="font-medium text-sm text-gray-700 dark:text-gray-300">
        <span x-show="!allChecked">{{ __('Select All') }}</span>
        <span x-show="allChecked" x-cloak>{{ __('Deselect All') }}</span>
    </span>
</div>