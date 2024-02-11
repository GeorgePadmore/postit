<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
            {{ __('Post IT') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">

            @if (session('status') === 'post-created')
                <div class="px-4 py-3 text-teal-900 bg-teal-100 border-t-4 border-teal-500 rounded-b shadow-md" role="alert">
                    <div class="flex">
                        <div class="py-1"><svg class="w-6 h-6 mr-4 text-teal-500 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M2.93 17.07A10 10 0 1 1 17.07 2.93 10 10 0 0 1 2.93 17.07zm12.73-1.41A8 8 0 1 0 4.34 4.34a8 8 0 0 0 11.32 11.32zM9 11V9h2v6H9v-4zm0-6h2v2H9V5z"/></svg></div>
                        <div>
                        <p class="font-bold">Post</p>
                        <p class="text-sm">Your post has been created!</p>
                        </div>
                    </div>
                </div>
            @endif


            @if (Route::has('login'))
                @auth
                    <div class="overflow-hidden bg-white shadow-sm dark:bg-gray-800 sm:rounded-lg" x-data=""
                        x-on:click.prevent="$dispatch('open-modal', 'create-post-modal')">
                        <div class="p-6 text-gray-900 dark:text-gray-100">
                            Create a post
                        </div>
                    </div>
                @endauth
            @endif

            {{-- <x-primary-button x-data=""
                x-on:click.prevent="$dispatch('open-modal', 'create-post-modal')">{{ __('Create a Post') }}</x-primary-button> --}}

        </div><br><br>


        <x-modal name="create-post-modal" :show="$errors->postCreation->isNotEmpty()" focusable>
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
        </x-modal>


        

        @if ($posts)
            @foreach ($posts as $post)
                {{-- Posts section --}}                
                <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
                    <div class="bg-white h-50 sm:py-2 ">
                        <div class="mt-6 space-y-12 lg:flex lg:gap-x-6 lg:space-y-0">
                            <!-- First column -->
                            <div class="relative group lg:w-1/7"></div>
                            <div class="relative group lg:w-1/7">
                                <div
                                    class="relative w-10 h-2 overflow-hidden bg-white rounded-lg group-hover:opacity-75 sm:h-10">
                                    <img src="https://tailwindui.com/img/ecommerce-images/home-page-02-edition-01.jpg"
                                        alt="Desk with leather desk pad, walnut desk organizer, wireless keyboard and mouse, and porcelain mug."
                                        class="object-cover object-center ">
                                </div>
                                <h3 class="mt-6 text-sm text-gray-500">
                                    <a href="#">
                                        <span class="absolute inset-0"></span>
                                        Desk and Office
                                    </a>
                                </h3>
                            </div>

                            <!-- Second column -->
                            <div class="relative group lg:w-3/7">
                                <p class="text-base font-semibold text-gray-900">{{ $post->title}}</p>
                                <p class="text-base text-gray-500">
                                    Journals and note-taking Self-Improvement Journals and note-taking Journals and
                                    note-taking Journals and
                                    note-taking Journals
                                </p>
                            </div>

                            <!-- Third column -->
                            <div class="relative flex items-center justify-end space-x-4 group lg:w-1/7">
                                <div class="flex flex-col items-center">
                                    <div class="h-full border-r border-gray-400"></div>
                                    <p class="text-sm text-gray-500">Likes - 1,568</p>
                                    <div class="h-full border-r border-gray-400"></div>
                                    <p class="text-sm text-gray-500">Eye - 1,568</p>
                                    <div class="h-full border-r border-gray-400"></div>
                                    <p class="text-sm text-gray-500">time - 24 mins</p>
                                </div>
                            </div>
                            <div class="relative group lg:w-1/7"></div>

                        </div>
                    </div>
                </div><br>
            @endforeach
        @endif

    </div>

</x-app-layout>
