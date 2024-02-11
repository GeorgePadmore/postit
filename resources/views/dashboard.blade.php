<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
            {{ __('Dashboard') }}
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
        </div><br><br>
        

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
                        <p class="text-base font-semibold text-gray-900">Journals and note-taking  Self-Improvement Journals</p>
                        <p class="text-base text-gray-500">
                            Journals and note-taking  Self-Improvement Journals and note-taking Journals and note-taking Journals and
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
        </div>


    </div>
</x-app-layout>
