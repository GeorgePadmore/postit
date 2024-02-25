<form method="post" action="{{ route('posts.create') }}" class="p-6">
    @csrf
    @method('post')

    <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
        {{ __('Create a Post') }}
    </h2>

    <div class="mt-6">
        <x-input-label for="title" value="{{ __('Title') }}" class="sr-only" />

        <x-text-input id="title" name="title" type="text" class="block w-full mt-1"
            placeholder="{{ __('Title') }}" />

        <x-input-error :messages="$errors->postCreation->get('title')" class="mt-2" />
    </div>

    <div class="mt-6">
        <x-input-label for="body" value="{{ __('Description') }}" class="sr-only" />

        <x-text-area id="body" name="body" type="text" class="block w-full h-40 mt-1"
            placeholder="{{ __('Description') }}" />

        <x-input-error :messages="$errors->postCreation->get('body')" class="mt-2" />
    </div>

    <div class="flex justify-end mt-6">
        <x-secondary-button x-on:click="$dispatch('close')">
            {{ __('Cancel') }}
        </x-secondary-button>

        <x-primary-button class="ms-3">
            {{ __('Post') }}
        </x-primary-button>
    </div>

</form>