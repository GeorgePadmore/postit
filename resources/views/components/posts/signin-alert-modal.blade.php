<div class="p-6">
    @csrf
    @method('post')

    <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
        {{ __('Sign In') }}
    </h2>

    <div class="flex flex-col items-center pb-10 mt-6">
        <x-input-label for="title" value="{{ __('Title') }}" class="sr-only" />

        <h3 class="pb-6 text-sm font-medium text-gray-900 dark:text-gray-100">
            {{ __('You need to Sign In or create an account in order to comment on this post.') }}
        </h3>


        <div class="w-1/2">
            <a href="{{ route('register') }}" class="block w-full mt-1">
                <x-primary-button class="block w-full mt-1">{{ __('Sign Up') }}</x-primary-button>
            </a>
        </div>

        <div class="flex items-center pt-2 pb-2">
            <hr class="flex-grow border-gray-200 dark:border-gray-700">
            <h3 class="mx-4 text-sm font-medium text-gray-900 dark:text-gray-100">{{ __('OR') }}</h3>
            <hr class="flex-grow border-gray-200 dark:border-gray-700">
        </div>

        <div class="w-1/2">
            <a href="{{ route('login') }}" class="block w-full mt-1">
                <x-primary-button
                    class="block w-full mt-1">{{ __('Already a user? Sign In') }}</x-primary-button>
            </a>
        </div>

    </div>

</div>