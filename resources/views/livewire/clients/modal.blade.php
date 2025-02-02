<div>
    <form wire:submit.prevent="save" class="p-6">
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">
            {{ $isEditing ? __('Edit Client') : __('Add New Client') }}
        </h2>

        <div class="mb-4">
            <x-text-float-input
                label="{{ __('ID') }}"
                name="id"
                wire:model="form.id"
                :readonly="$isEditing"
                :tabIndex="$isEditing ? -1 : 0"
            />
            <x-input-error :messages="$errors->get('form.id')" class="mt-2" />
        </div>

        <div class="mb-4">
            <x-text-float-input
                label="{{ __('Name') }}"
                name="name"
                wire:model="form.name"
            />
            <x-input-error :messages="$errors->get('form.name')" class="mt-2" />
        </div>
        <div class="mb-4">
            <x-text-float-input
                label="{{ __('Last Name') }}"
                name="last_name"
                wire:model="form.last_name"
            />
            <x-input-error :messages="$errors->get('form.last_name')" class="mt-2" />
        </div>
        <div class="mb-4">
            <x-text-float-input
                label="{{ __('Email') }}"
                name="email"
                wire:model="form.email"
                type="email"
            />
            <x-input-error :messages="$errors->get('form.email')" class="mt-2" />
        </div>
        <div class="mb-4">
            <x-text-float-input
                label="{{ __('Phone') }}"
                name="phone"
                wire:model="form.phone"
            />
            <x-input-error :messages="$errors->get('form.phone')" class="mt-2" />
        </div>
        <div class="mb-4">
            <x-select-input
                label="{{ __('Client Type') }}"
                name="client_type"
                wire:model.live="form.client_type"
            >
                <option value="" disabled selected>{{ __('Select a type...') }}</option>
                <option value="Cash">Cash</option>
                <option value="Credit">Credit</option>
            </x-select-input>
            <x-input-error :messages="$errors->get('form.client_type')" class="mt-2" />
        </div>
        <div class="mb-4">
            <x-text-float-input
                label="{{ __('Address') }}"
                name="address"
                wire:model="form.address"
            />
            <x-input-error :messages="$errors->get('form.address')" class="mt-2" />
        </div>
        <div class="mb-4">
            <x-date-input
                label="{{ __('Birthdate') }}"
                name="birth_date"
                wire:model="form.birth_date"
            />
            <x-input-error :messages="$errors->get('form.birth_date')" class="mt-2" />
        </div>
        <div class="mt-6 flex justify-end">
            <x-secondary-button wire:click.prevent="closeModal">
                {{ __('Cancel') }}
            </x-secondary-button>

            <x-primary-button class="ms-3">
                {{ $isEditing ? __('Update') : __('Create') }}
            </x-primary-button>
        </div>
    </form>
</div>
