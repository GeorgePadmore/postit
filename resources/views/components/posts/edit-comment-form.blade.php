<form method="post" action="{{ route('comments.update') }}" class="p-6">
    @csrf
    @method('post')

    <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
        {{ __('Update Comment') }}
    </h2>

    <!-- Hidden input field for post ID -->
    <input type="hidden" name="commentId" id="commentId">

    <div class="mt-6">
        <x-input-label for="editComment" value="{{ __('Description') }}" class="sr-only" />

        <x-text-area id="editComment" name="editComment" type="text" class="block w-full h-40 mt-1"
            placeholder="{{ __('Comment') }}" />

        <x-input-error :messages="$errors->commentUpdate->get('editComment')" class="mt-2" />
    </div>

    <div class="flex justify-end mt-6">
        <x-secondary-button x-on:click="$dispatch('close')">
            {{ __('Cancel') }}
        </x-secondary-button>

        <x-primary-button class="ms-3">
            {{ __('Update Comment') }}
        </x-primary-button>
    </div>

</form>