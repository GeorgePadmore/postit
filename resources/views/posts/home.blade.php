<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
            {{ __('Post IT') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-sm dark:bg-gray-800 sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    {{-- {{ __("You're logged in!") }} --}}
                    Create a post
                </div>
            </div>

            {{-- <button id="myBtn">Open Modal</button> --}}

            <x-primary-button x-data=""
                x-on:click.prevent="$dispatch('open-modal', 'create-post-modal')">{{ __('Create a Post') }}</x-primary-button>

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

                <div class="flex items-center gap-4">

                    @if (session('status') === 'post-created')
                        <p
                            x-data="{ show: true }"
                            x-show="show"
                            x-transition
                            x-init="setTimeout(() => show = false, 2000)"
                            class="text-sm text-gray-600 dark:text-gray-400"
                        >{{ __('Saved.') }}</p>
                    @endif
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
                                <p class="text-base font-semibold text-gray-900">Journals and note-taking
                                    Self-Improvement Journals</p>
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

    <script>
        // Get the modal
        var modal = document.getElementById("myModal");

        // Get the button that opens the modal
        var btn = document.getElementById("myBtn");

        // Get the <span> element that closes the modal
        var span = document.getElementsByClassName("close")[0];

        // When the user clicks on the button, open the modal
        btn.onclick = function() {
            modal.style.display = "block";
        }

        // When the user clicks on <span> (x), close the modal
        span.onclick = function() {
            modal.style.display = "none";
        }

        // When the user clicks anywhere outside of the modal, close it
        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }
    </script>
</x-app-layout>
