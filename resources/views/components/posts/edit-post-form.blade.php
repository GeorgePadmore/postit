<form method="post" action="{{ route('posts.update') }}" class="p-6">
    @csrf
    @method('post')

    <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
        {{ __('Update Post') }}
    </h2>

    <!-- Hidden input field for post ID -->
    <input type="hidden" name="postId" id="postId">

    <div class="mt-6">
        <x-input-label for="editTitle" value="{{ __('Title') }}" class="sr-only" />

        <x-text-input id="editTitle" name="editTitle" type="text" class="block w-full mt-1"
            placeholder="{{ __('Title') }}" />

        <x-input-error :messages="$errors->postUpdate->get('editTitle')" class="mt-2" />
    </div>

    <div class="mt-6">
        <x-input-label for="editBody" value="{{ __('Description') }}" class="sr-only" />

        <x-text-area id="editBody" name="editBody" type="text" class="block w-full h-40 mt-1"
            placeholder="{{ __('Description') }}" />

        <x-input-error :messages="$errors->postUpdate->get('editBody')" class="mt-2" />
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