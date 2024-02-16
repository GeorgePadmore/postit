<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
            {{ __('Post IT') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl pb-5 mx-auto sm:px-6 lg:px-8">

            @if (session('status') === 'post-created')
                <div class="px-4 py-3 text-teal-900 bg-teal-100 border-t-4 border-teal-500 rounded-b shadow-md"
                    role="alert">
                    <div class="flex">
                        <div class="py-1"><svg class="w-6 h-6 mr-4 text-teal-500 fill-current"
                                xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                <path
                                    d="M2.93 17.07A10 10 0 1 1 17.07 2.93 10 10 0 0 1 2.93 17.07zm12.73-1.41A8 8 0 1 0 4.34 4.34a8 8 0 0 0 11.32 11.32zM9 11V9h2v6H9v-4zm0-6h2v2H9V5z" />
                            </svg></div>
                        <div>
                            <p class="font-bold">Post</p>
                            <p class="text-sm">Your post has been created!</p>
                        </div>
                    </div>
                </div>
            @endif


        </div>


        <x-modal name="edit-post-modal" id="edit-post-modal" :show="$errors->postUpdate->isNotEmpty()" focusable>
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
        </x-modal>


         <x-modal name="edit-comment-modal" id="edit-comment-modal" :show="$errors->commentUpdate->isNotEmpty()" focusable>
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
        </x-modal>



        @if ($post)
            {{-- Posts section --}}

            {{-- <div class="max-w-5xl px-4 pb-2 mx-auto sm:px-6 lg:px-8">
                <div class="w-full bg-white h-50 sm:py-2 max-w-screen">
                    <div class="mt-6 space-y-12 lg:flex lg:gap-x-6 lg:space-y-0 lg:justify-between"> <!-- Added lg:justify-between -->
                        <!-- First column -->
                        <div class="relative group lg:w-1/7"></div>
                        <div class="relative group lg:w-1/7">
                            <div
                                class="relative w-10 h-2 overflow-hidden bg-white rounded-lg group-hover:opacity-75 sm:h-10">
                                @if ($post->user->profile_pic_url)
                                    <img src="{{ Storage::url($post->user->profile_pic_url) }}"
                                        alt="Desk with leather desk pad, walnut desk organizer, wireless keyboard and mouse, and porcelain mug."
                                        class="object-cover object-center ">
                                @else
                                    <img src="https://tailwindui.com/img/ecommerce-images/home-page-02-edition-01.jpg"
                                        alt="Desk with leather desk pad, walnut desk organizer, wireless keyboard and mouse, and porcelain mug."
                                        class="object-cover object-center ">
                                @endif

                            </div>
                            <h3 class="mt-6 text-sm text-gray-500">
                                <a href="#">
                                    <span class="absolute inset-0"></span>
                                    {{ $post->user->name }}
                                </a>
                            </h3>
                        </div>

                        <div class="relative group lg:w-4/7">
                        
                            <p class="text-base font-semibold text-gray-900">{{ $post->title }}</p>
                            <p class="pb-5 text-base text-gray-500"> {{ $post->body }} </p>

                            <div class="flex items-center">
                                <a href="{{ route('posts.details', ['id' => $post->id]) }}"
                                    class="flex items-center mr-4 hover:text-blue-500">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M2.25 12.76c0 1.6 1.123 2.994 2.707 3.227 1.087.16 2.185.283 3.293.369V21l4.076-4.076a1.526 1.526 0 0 1 1.037-.443 48.282 48.282 0 0 0 5.68-.494c1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0 0 12 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018Z" />
                                    </svg>
                                    <p class="text-sm text-gray-500"> 
                                        @if ( $post->comments->count() <= 1 )
                                            {{$post->comments->count()}} Comment
                                        @else
                                            {{$post->comments->count()}} Comments
                                        @endif
                                    </p>
                                </a>

                                @if ($post->liked_by_user)
                                            
                                    <form action="{{ route('posts.unlikePost', ['postId' => $post->id]) }}" method="POST" class="flex items-center mr-4">
                                        @csrf
                                        <button type="submit">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 fill-blue-500">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M6.633 10.25c.806 0 1.533-.446 2.031-1.08a9.041 9.041 0 0 1 2.861-2.4c.723-.384 1.35-.956 1.653-1.715a4.498 4.498 0 0 0 .322-1.672V2.75a.75.75 0 0 1 .75-.75 2.25 2.25 0 0 1 2.25 2.25c0 1.152-.26 2.243-.723 3.218-.266.558.107 1.282.725 1.282m0 0h3.126c1.026 0 1.945.694 2.054 1.715.045.422.068.85.068 1.285a11.95 11.95 0 0 1-2.649 7.521c-.388.482-.987.729-1.605.729H13.48c-.483 0-.964-.078-1.423-.23l-3.114-1.04a4.501 4.501 0 0 0-1.423-.23H5.904m10.598-9.75H14.25M5.904 18.5c.083.205.173.405.27.602.197.4-.078.898-.523.898h-.908c-.889 0-1.713-.518-1.972-1.368a12 12 0 0 1-.521-3.507c0-1.553.295-3.036.831-4.398C3.387 9.953 4.167 9.5 5 9.5h1.053c.472 0 .745.556.5.96a8.958 8.958 0 0 0-1.302 4.665c0 1.194.232 2.333.654 3.375Z" />
                                            </svg>
                                        </button>

                                        <p class="text-sm text-gray-500">
                                            @if ($post->total_likes == null)
                                                0 Like
                                            @else
                                                {{ $post->total_likes }} Likes
                                            @endif
                                        </p>
                                    </form>

                                @else

                                    <form action="{{ route('posts.likePost', ['postId' => $post->id]) }}" method="POST" class="flex items-center mr-4">
                                        @csrf
                                        <button type="submit">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M6.633 10.25c.806 0 1.533-.446 2.031-1.08a9.041 9.041 0 0 1 2.861-2.4c.723-.384 1.35-.956 1.653-1.715a4.498 4.498 0 0 0 .322-1.672V2.75a.75.75 0 0 1 .75-.75 2.25 2.25 0 0 1 2.25 2.25c0 1.152-.26 2.243-.723 3.218-.266.558.107 1.282.725 1.282m0 0h3.126c1.026 0 1.945.694 2.054 1.715.045.422.068.85.068 1.285a11.95 11.95 0 0 1-2.649 7.521c-.388.482-.987.729-1.605.729H13.48c-.483 0-.964-.078-1.423-.23l-3.114-1.04a4.501 4.501 0 0 0-1.423-.23H5.904m10.598-9.75H14.25M5.904 18.5c.083.205.173.405.27.602.197.4-.078.898-.523.898h-.908c-.889 0-1.713-.518-1.972-1.368a12 12 0 0 1-.521-3.507c0-1.553.295-3.036.831-4.398C3.387 9.953 4.167 9.5 5 9.5h1.053c.472 0 .745.556.5.96a8.958 8.958 0 0 0-1.302 4.665c0 1.194.232 2.333.654 3.375Z" />
                                            </svg>
                                        </button>

                                        <p class="text-sm text-gray-500">
                                            @if ($post->total_likes == null)
                                                0 Like
                                            @else
                                                {{ $post->total_likes }} Likes
                                            @endif
                                        </p>
                                    </form>
                                    
                                @endif

                                <div class="flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                    </svg>

                                    <p class="text-sm text-gray-500"> {{$post->created_at->diffForHumans()}} </p>
                                </div>
                            </div>

                        </div>

                        <!-- Third column -->
                        <div class="relative group lg:w-1/7 lg:self-end"> <!-- Added lg:self-end -->
                            <!-- Edit & Delete Dropdown Only if no one has commented -->
                            @if ($post->user->id == Auth::id() && $post->comments->count() < 1)

                                <div class="hidden sm:flex sm:items-center sm:ms-6">
                                    <x-dropdown align="right" width="48">
                                        <x-slot name="trigger">
                                            <button class="inline-flex items-center px-3 py-2 text-sm font-medium leading-4 text-gray-500 transition duration-150 ease-in-out bg-white border border-transparent rounded-md dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none">
                                                <div class="ms-1">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                        stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M6.75 12a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0ZM12.75 12a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0ZM18.75 12a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" />
                                                    </svg>
                                                </div>
                                            </button>
                                        </x-slot>

                                        <x-slot name="content">
                                            
                                            <x-new-dropdown-link class="edit-post-btn" x-data="" data-post-id="{{ $post->id }}" x-on:click.prevent="$dispatch('open-modal', 'edit-post-modal')">
                                                {{ __('Edit') }}
                                            </x-new-dropdown-link>

                                            <form id="delete-post-form-{{ $post->id }}" method="POST" action="{{ route('posts.delete', ['id' => $post->id]) }}" style="display: none;">
                                                @csrf
                                            </form>

                                            <x-dropdown-link href="#" onclick="confirmDelete('delete-post-form-', {{ $post->id }}, 'Are you sure you want to delete this post?')">
                                                {{ __('Delete') }}
                                            </x-dropdown-link>

                                        </x-slot>
                                    </x-dropdown>
                                </div>
                                
                            @endif
                        </div>
                    </div>
                </div>
            </div> --}}


            <div class="max-w-5xl px-4 pb-2 mx-auto sm:px-6 lg:px-8">
                <div class="w-full bg-white h-50 sm:py-2 max-w-screen">
                    <div class="mt-6 space-y-12 lg:flex lg:gap-x-6 lg:space-y-0">
                        <!-- First column -->
                        <div class="relative group lg:w-1/7"></div>
                        <div class="relative group lg:w-1/7">
                            <div
                                class="relative w-10 h-2 overflow-hidden bg-white rounded-lg group-hover:opacity-75 sm:h-10">
                                @if ($post->user->profile_pic_url)
                                    <img src="{{ Storage::url($post->user->profile_pic_url) }}"
                                        alt="Desk with leather desk pad, walnut desk organizer, wireless keyboard and mouse, and porcelain mug."
                                        class="object-cover object-center ">
                                @else
                                    <img src="https://tailwindui.com/img/ecommerce-images/home-page-02-edition-01.jpg"
                                        alt="Desk with leather desk pad, walnut desk organizer, wireless keyboard and mouse, and porcelain mug."
                                        class="object-cover object-center ">
                                @endif

                            </div>
                            <h3 class="mt-6 text-sm text-gray-500">
                                <a href="#">
                                    <span class="absolute inset-0"></span>
                                    {{ $post->user->name }}
                                </a>
                            </h3>
                        </div>

                        <!-- Second column -->
                        <div class="relative group lg:w-4/7">
                        
                            <p class="text-base font-semibold text-gray-900">{{ $post->title }}</p>
                            <p class="pb-5 text-base text-gray-500"> {{ $post->body }} </p>

                            <div class="flex items-center">
                                <a href="{{ route('posts.details', ['id' => $post->id]) }}"
                                    class="flex items-center mr-4 hover:text-blue-500">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M2.25 12.76c0 1.6 1.123 2.994 2.707 3.227 1.087.16 2.185.283 3.293.369V21l4.076-4.076a1.526 1.526 0 0 1 1.037-.443 48.282 48.282 0 0 0 5.68-.494c1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0 0 12 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018Z" />
                                    </svg>
                                    <p class="text-sm text-gray-500"> 
                                        @if ( $post->comments->count() <= 1 )
                                            {{$post->comments->count()}} Comment
                                        @else
                                            {{$post->comments->count()}} Comments
                                        @endif
                                    </p>
                                </a>

                                @if ($post->liked_by_user)
                                            
                                    <form action="{{ route('posts.unlikePost', ['postId' => $post->id]) }}" method="POST" class="flex items-center mr-4">
                                        @csrf
                                        <button type="submit">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 fill-blue-500">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M6.633 10.25c.806 0 1.533-.446 2.031-1.08a9.041 9.041 0 0 1 2.861-2.4c.723-.384 1.35-.956 1.653-1.715a4.498 4.498 0 0 0 .322-1.672V2.75a.75.75 0 0 1 .75-.75 2.25 2.25 0 0 1 2.25 2.25c0 1.152-.26 2.243-.723 3.218-.266.558.107 1.282.725 1.282m0 0h3.126c1.026 0 1.945.694 2.054 1.715.045.422.068.85.068 1.285a11.95 11.95 0 0 1-2.649 7.521c-.388.482-.987.729-1.605.729H13.48c-.483 0-.964-.078-1.423-.23l-3.114-1.04a4.501 4.501 0 0 0-1.423-.23H5.904m10.598-9.75H14.25M5.904 18.5c.083.205.173.405.27.602.197.4-.078.898-.523.898h-.908c-.889 0-1.713-.518-1.972-1.368a12 12 0 0 1-.521-3.507c0-1.553.295-3.036.831-4.398C3.387 9.953 4.167 9.5 5 9.5h1.053c.472 0 .745.556.5.96a8.958 8.958 0 0 0-1.302 4.665c0 1.194.232 2.333.654 3.375Z" />
                                            </svg>
                                        </button>

                                        <p class="text-sm text-gray-500">
                                            @if ($post->total_likes == null)
                                                0 Like
                                            @else
                                                {{ $post->total_likes }} Likes
                                            @endif
                                        </p>
                                    </form>

                                @else

                                    <form action="{{ route('posts.likePost', ['postId' => $post->id]) }}" method="POST" class="flex items-center mr-4">
                                        @csrf
                                        <button type="submit">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M6.633 10.25c.806 0 1.533-.446 2.031-1.08a9.041 9.041 0 0 1 2.861-2.4c.723-.384 1.35-.956 1.653-1.715a4.498 4.498 0 0 0 .322-1.672V2.75a.75.75 0 0 1 .75-.75 2.25 2.25 0 0 1 2.25 2.25c0 1.152-.26 2.243-.723 3.218-.266.558.107 1.282.725 1.282m0 0h3.126c1.026 0 1.945.694 2.054 1.715.045.422.068.85.068 1.285a11.95 11.95 0 0 1-2.649 7.521c-.388.482-.987.729-1.605.729H13.48c-.483 0-.964-.078-1.423-.23l-3.114-1.04a4.501 4.501 0 0 0-1.423-.23H5.904m10.598-9.75H14.25M5.904 18.5c.083.205.173.405.27.602.197.4-.078.898-.523.898h-.908c-.889 0-1.713-.518-1.972-1.368a12 12 0 0 1-.521-3.507c0-1.553.295-3.036.831-4.398C3.387 9.953 4.167 9.5 5 9.5h1.053c.472 0 .745.556.5.96a8.958 8.958 0 0 0-1.302 4.665c0 1.194.232 2.333.654 3.375Z" />
                                            </svg>
                                        </button>

                                        <p class="text-sm text-gray-500">
                                            @if ($post->total_likes == null)
                                                0 Like
                                            @else
                                                {{ $post->total_likes }} Likes
                                            @endif
                                        </p>
                                    </form>
                                    
                                @endif

                                <div class="flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                    </svg>

                                    <p class="text-sm text-gray-500"> {{$post->created_at->diffForHumans()}} </p>
                                </div>
                            </div>

                        </div>
                        
                        <!-- Third column -->
                        <div class="relative group lg:w-1/7 lg:ml-auto">
                    
                            <!-- Edit & Delete Dropdown Only if no one has commented -->
                            @if ($post->user->id == Auth::id() && $post->comments->count() < 1)

                                <div class="hidden sm:flex sm:items-center sm:ms-6">
                                    <x-dropdown align="right" width="48">
                                        <x-slot name="trigger">
                                            <button class="inline-flex items-center px-3 py-2 text-sm font-medium leading-4 text-gray-500 transition duration-150 ease-in-out bg-white border border-transparent rounded-md dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none">
                                                <div class="ms-1">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                        stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M6.75 12a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0ZM12.75 12a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0ZM18.75 12a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" />
                                                    </svg>
                                                </div>
                                            </button>
                                        </x-slot>

                                        <x-slot name="content">
                                            
                                            <x-new-dropdown-link class="edit-post-btn" x-data="" data-post-id="{{ $post->id }}" x-on:click.prevent="$dispatch('open-modal', 'edit-post-modal')">
                                                {{ __('Edit') }}
                                            </x-new-dropdown-link>

                                            <form id="delete-post-form-{{ $post->id }}" method="POST" action="{{ route('posts.delete', ['id' => $post->id]) }}" style="display: none;">
                                                @csrf
                                            </form>

                                            <x-dropdown-link href="#" onclick="confirmDelete('delete-post-form-', {{ $post->id }}, 'Are you sure you want to delete this post?')">
                                                {{ __('Delete') }}
                                            </x-dropdown-link>

                                        </x-slot>
                                    </x-dropdown>
                                </div>
                                
                            @endif

                        </div>

                    </div>

                </div>
            </div>

            @if ($post->comments)

                @foreach ($post->comments as $comment)
                    <div class="max-w-5xl px-4 pb-5 mx-auto sm:px-6 lg:px-8">
                        <div class="w-full bg-white h-50 sm:py-2 max-w-screen">
                            <div class="mt-6 space-y-12 lg:flex lg:gap-x-6 lg:space-y-0">
                                <!-- First column -->
                                <div class="relative group lg:w-1/7"></div>
                                <div class="relative group lg:w-1/7">
                                    <div
                                        class="relative w-10 h-2 overflow-hidden bg-white rounded-lg group-hover:opacity-75 sm:h-10">
                                        @if ($comment->user->profile_pic_url)
                                            <img src="{{ Storage::url($comment->user->profile_pic_url) }}"

                                                alt="Desk with leather desk pad, walnut desk organizer, wireless keyboard and mouse, and porcelain mug."
                                                class="object-cover object-center ">
                                        @else
                                            <img src="https://tailwindui.com/img/ecommerce-images/home-page-02-edition-01.jpg"
                                                alt="Desk with leather desk pad, walnut desk organizer, wireless keyboard and mouse, and porcelain mug."
                                                class="object-cover object-center ">
                                        @endif

                                    </div>
                                    <h3 class="mt-6 text-sm text-gray-500">
                                        <a href="#">
                                            <span class="absolute inset-0"></span>
                                            {{ $comment->user->name }}
                                        </a>
                                    </h3>
                                </div>

                                <!-- Second column -->
                                <div class="relative group lg:w-4/7">
                                    <p class="text-base text-gray-500 pb-7"> {{$comment->text}} </p>

                                    <div class="flex items-center">
                                 
                                        <div class="flex items-center mr-4">
                                            
                                            @if ($comment->liked_by_user)
                                            
                                                <form action="{{ route('comments.unlikeComment', ['commentId' => $comment->id]) }}" method="POST" class="flex items-center mr-4">
                                                    @csrf
                                                    <button type="submit">
                                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 fill-blue-500">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.633 10.25c.806 0 1.533-.446 2.031-1.08a9.041 9.041 0 0 1 2.861-2.4c.723-.384 1.35-.956 1.653-1.715a4.498 4.498 0 0 0 .322-1.672V2.75a.75.75 0 0 1 .75-.75 2.25 2.25 0 0 1 2.25 2.25c0 1.152-.26 2.243-.723 3.218-.266.558.107 1.282.725 1.282m0 0h3.126c1.026 0 1.945.694 2.054 1.715.045.422.068.85.068 1.285a11.95 11.95 0 0 1-2.649 7.521c-.388.482-.987.729-1.605.729H13.48c-.483 0-.964-.078-1.423-.23l-3.114-1.04a4.501 4.501 0 0 0-1.423-.23H5.904m10.598-9.75H14.25M5.904 18.5c.083.205.173.405.27.602.197.4-.078.898-.523.898h-.908c-.889 0-1.713-.518-1.972-1.368a12 12 0 0 1-.521-3.507c0-1.553.295-3.036.831-4.398C3.387 9.953 4.167 9.5 5 9.5h1.053c.472 0 .745.556.5.96a8.958 8.958 0 0 0-1.302 4.665c0 1.194.232 2.333.654 3.375Z" />
                                                        </svg>
                                                    </button>

                                                    <p class="text-sm text-gray-500">
                                                        @if ($comment->total_likes == null)
                                                            0 Like
                                                        @else
                                                            {{ $comment->total_likes }} Likes
                                                        @endif
                                                    </p>
                                                </form>

                                            @else

                                                <form action="{{ route('comments.likeComment', ['commentId' => $comment->id]) }}" method="POST" class="flex items-center mr-4">
                                                    @csrf
                                                    <button type="submit">
                                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.633 10.25c.806 0 1.533-.446 2.031-1.08a9.041 9.041 0 0 1 2.861-2.4c.723-.384 1.35-.956 1.653-1.715a4.498 4.498 0 0 0 .322-1.672V2.75a.75.75 0 0 1 .75-.75 2.25 2.25 0 0 1 2.25 2.25c0 1.152-.26 2.243-.723 3.218-.266.558.107 1.282.725 1.282m0 0h3.126c1.026 0 1.945.694 2.054 1.715.045.422.068.85.068 1.285a11.95 11.95 0 0 1-2.649 7.521c-.388.482-.987.729-1.605.729H13.48c-.483 0-.964-.078-1.423-.23l-3.114-1.04a4.501 4.501 0 0 0-1.423-.23H5.904m10.598-9.75H14.25M5.904 18.5c.083.205.173.405.27.602.197.4-.078.898-.523.898h-.908c-.889 0-1.713-.518-1.972-1.368a12 12 0 0 1-.521-3.507c0-1.553.295-3.036.831-4.398C3.387 9.953 4.167 9.5 5 9.5h1.053c.472 0 .745.556.5.96a8.958 8.958 0 0 0-1.302 4.665c0 1.194.232 2.333.654 3.375Z" />
                                                        </svg>
                                                    </button>

                                                    <p class="text-sm text-gray-500">
                                                        @if ($comment->total_likes == null)
                                                            0 Like
                                                        @else
                                                            {{ $comment->total_likes }} Likes
                                                        @endif
                                                    </p>
                                                </form>
                                                
                                            @endif



                                        </div>

                                        <div class="flex items-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                            </svg>

                                            <p class="text-sm text-gray-500">{{ $comment->created_at->diffForHumans() }}</p>
                                        </div>
                                    </div>

                                </div>

                                <div class="relative group lg:w-1/7 lg:ml-auto">
                    
                                <!-- Edit & Delete Dropdown Only if no one has commented -->
                                @if ($comment->user->id == Auth::id())

                                    <div class="hidden sm:flex sm:items-center sm:ms-6">
                                        <x-dropdown align="right" width="48">
                                            <x-slot name="trigger">
                                                <button class="inline-flex items-center px-3 py-2 text-sm font-medium leading-4 text-gray-500 transition duration-150 ease-in-out bg-white border border-transparent rounded-md dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none">
                                                    <div class="ms-1">
                                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                            stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                d="M6.75 12a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0ZM12.75 12a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0ZM18.75 12a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" />
                                                        </svg>
                                                    </div>
                                                </button>
                                            </x-slot>

                                            <x-slot name="content">
                                                
                                                <x-dropdown-link class="edit-comment-btn" x-data="" data-comment-id="{{ $comment->id }}" x-on:click.prevent="$dispatch('open-modal', 'edit-comment-modal')">
                                                    {{ __('Edit') }}
                                                </x-new-dropdown-link>

                                                <form id="delete-comment-form-{{ $comment->id }}" method="POST" action="{{ route('comments.delete', ['id' => $comment->id]) }}" style="display: none;">
                                                    @csrf
                                                </form>

                                                <x-dropdown-link href="#" onclick="confirmDelete('delete-comment-form-', {{ $comment->id }}, 'Are you sure you want to delete this comment?')">
                                                    {{ __('Delete') }}
                                                </x-dropdown-link>


                                            </x-slot>
                                        </x-dropdown>
                                    </div>
                                    
                                @endif

                            </div>


                            </div>

                        </div>
                    </div>
                @endforeach

            @else

                {{-- Show no record found --}}
                
            @endif

            {{-- Write Comment section --}}

            @if (Auth::check())

                <div class="max-w-5xl px-4 pb-5 mx-auto sm:px-6 lg:px-8">
                    <div class="w-full bg-white h-50 sm:py-2 max-w-screen">
                        <div class="mt-6 space-y-12 lg:flex lg:gap-x-6 lg:space-y-0">
                            <!-- First column -->
                            <div class="relative group lg:w-1/7"></div>
                            <div class="relative group lg:w-2/7">
                                <div class="relative w-10 h-2 overflow-hidden bg-white rounded-lg group-hover:opacity-75 sm:h-10">
                                    @if (Auth::check() && Auth::user()->profile_pic_url != null)
                                        <img src="{{ Storage::url(Auth::user()->profile_pic_url) }}"
                                            alt="Desk with leather desk pad, walnut desk organizer, wireless keyboard and mouse, and porcelain mug."
                                            class="object-cover object-center">
                                    @else
                                        <img src="https://tailwindui.com/img/ecommerce-images/home-page-02-edition-01.jpg"
                                            alt="Desk with leather desk pad, walnut desk organizer, wireless keyboard and mouse, and porcelain mug."
                                            class="object-cover object-center">
                                    @endif
                                </div>
                                <h3 class="mt-6 text-sm text-gray-500">
                                    <a href="#">
                                        <span class="absolute inset-0"></span>
                                        
                                        {{-- (Auth::check() && Auth::user()->name != null) ? {{ Auth::user()->name }} : "Guest" --}}
                                        @if (Auth::check() && Auth::user()->name != null)
                                            {{ Auth::user()->name }}
                                        @else
                                            Guest
                                        @endif
                                    </a>
                                </h3>
                            </div>

                            <!-- Second column -->
                            <div class="relative pb-2 group lg:w-10/12">

                                <form method="post" action="{{ route('comments.add', ['postId' => $post->id]) }}">
                                    @csrf
                                    @method('post')

                                    <div>
                                        <x-text-area id="text" name="text" type="text" class="block w-full h-40 mt-1" placeholder="{{ __('Comment') }}" />

                                        <x-input-error :messages="$errors->commentCreation->get('text')" class="mt-2" />
                                    </div>
                                    <div class="flex justify-end mt-6">
                                        

                                        <a href="{{ route('posts.index') }}">
                                            <x-secondary-button x-on:click="$dispatch('close')"> {{ __('Cancel') }} </x-secondary-button> 
                                        </a>

                                        <x-primary-button class="ms-3">
                                            {{ __('Comment') }}
                                        </x-primary-button>
                                    </div>

                                </form>

                            </div>

                        </div>

                    </div>
                </div>

            @else

                <div class="max-w-5xl px-4 pb-5 mx-auto sm:px-6 lg:px-8">
                    <div class="w-full bg-white h-50 sm:py-2 max-w-screen">
                        <div class="mt-6 space-y-12 lg:flex lg:gap-x-6 lg:space-y-0">
                            <!-- First column -->
                            <div class="relative group lg:w-1/7"></div>
                            <div class="relative group lg:w-2/7">
                                <div class="relative w-10 h-2 overflow-hidden bg-white rounded-lg group-hover:opacity-75 sm:h-10">
                                    @if (Auth::check() && Auth::user()->profile_pic_url != null)
                                        <img src="{{ Storage::url(Auth::user()->profile_pic_url) }}"
                                            alt="Desk with leather desk pad, walnut desk organizer, wireless keyboard and mouse, and porcelain mug."
                                            class="object-cover object-center">
                                    @else
                                        <img src="https://tailwindui.com/img/ecommerce-images/home-page-02-edition-01.jpg"
                                            alt="Desk with leather desk pad, walnut desk organizer, wireless keyboard and mouse, and porcelain mug."
                                            class="object-cover object-center">
                                    @endif
                                </div>
                                <h3 class="mt-6 text-sm text-gray-500">
                                    <a href="#">
                                        <span class="absolute inset-0"></span>
                                        
                                        @if (Auth::check() && Auth::user()->name != null)
                                            {{ Auth::user()->name }}
                                        @else
                                            Guest
                                        @endif
                                    </a>
                                </h3>
                            </div>

                            <!-- Second column -->
                            <div class="relative pb-2 group lg:w-10/12">

                                <div>
                                    <p class="pb-5 text-sm text-gray-500"> Login / Signup in order to comment on this post. </p>
                                    <x-input-error :messages="$errors->commentCreation->get('text')" class="mt-2" />
                                </div>

                            </div>

                        </div>

                    </div>
                </div>
                
            @endif
            
        @endif

    </div>


     <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {

            $('.edit-comment-btn').click(function() {
                
                var commentId = $(this).data('comment-id');
                
                // Send Ajax request to fetch comment details
                $.ajax({
                    url: '/comments/' + commentId + '/edit',
                    type: 'GET',
                    success: function(response) {
                        // Update form fields with fetched post details
                        $('#editComment').val(response.comment.text);
                        $('#commentId').val(commentId);
                        
                    },
                    error: function(xhr) {
                        console.log(xhr.responseText);
                    }
                });
                
            });


            $('.edit-post-btn').click(function() {
                
                var postId = $(this).data('post-id');
                
                // Send Ajax request to fetch post details
                $.ajax({
                    url: '/posts/' + postId + '/edit',
                    type: 'GET',
                    success: function(response) {
                        // Update form fields with fetched post details
                        $('#editTitle').val(response.post.title);
                        $('#editBody').val(response.post.body);
                        $('#postId').val(postId);
                        
                    },
                    error: function(xhr) {
                        console.log(xhr.responseText);
                    }
                });
            });
        });


        function confirmDelete(formId, keyId, promptText) {
            if (confirm(promptText)) {
                document.getElementById(formId + keyId).submit();
            }
        }


    </script>

</x-app-layout>
