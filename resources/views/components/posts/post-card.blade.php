@props(['post'])

@php
    $stringCut = substr(strip_tags($post->body), 0, 440);
    $endPoint = strrpos($stringCut, ' ');
    $description = $endPoint ? substr($stringCut, 0, $endPoint) : substr($stringCut, 0);
    $post_like_count = $post->total_likes ?? 0;
    $voteable_up = true;
    $voteable_down = true;
    $user_react = false;
    $liked_by_user = $post->liked_by_user ?? false;
@endphp

<div id="post-section" class="max-w-5xl px-4 pb-4 mx-auto sm:px-6 lg:px-8">
                    
    <div class="relative flex p-8 mb-6 space-x-2 overflow-hidden transition-all duration-200 bg-white rounded-sm shadow discussion-item group hover:shadow-xl"
            data-id="{{ $post->id }}"
            data-vote="{{ $user_react }}"
        >
        <div class="flex flex-col w-1/12 mt-2 space-y-2 text-center">

            @auth
                <form action="{{ route('posts.react', ['postId' => $post->id, 'action' => $liked_by_user ? 'unlike' : 'like']) }}" method="POST" class="flex items-center mr-4">
                    @csrf
                    <div class="flex flex-row justify-center text-sm cursor-pointer vote-up">
                        <button type="submit">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 10.5 12 3m0 0 7.5 7.5M12 3v18" />
                            </svg>
                        </button>
                    </div>
                </form>
            @else
                <div class="flex flex-row justify-center text-sm cursor-pointer vote-up">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 10.5 12 3m0 0 7.5 7.5M12 3v18" />
                    </svg>
                </div>
            @endauth

            <div class="@auth vote-count @endauth flex items-center mr-1 ml-2">
                {{ $post_like_count }}
            </div>
        
            @auth
                <form action="{{ route('posts.react', ['postId' => $post->id, 'action' => $liked_by_user ? 'unlike' : 'like']) }}" method="POST" class="flex items-center mr-4">
                    @csrf
                    <div class="flex flex-row justify-center text-sm
                            @if ($voteable_down) cursor-pointer vote-down @endif
                            @if ($user_react == 'downvote') text-red-500 @endif
                        ">
                        <button type="submit">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 13.5 12 21m0 0-7.5-7.5M12 21V3" />
                            </svg>
                        </button>
                    </div>
                </form>
            @else
                <div class="flex flex-row justify-center text-sm
                        @if ($voteable_down) cursor-pointer vote-down @endif
                        @if ($user_react == 'downvote') text-red-500 @endif
                    ">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 13.5 12 21m0 0-7.5-7.5M12 21V3" />
                    </svg>
                </div>
            @endauth

        </div>
        <div class="w-11/12">
            <a href="{{ route('posts.details', ['id' => $post->id]) }}" class="block mb-3 text-xl font-bold text-gray-800">
                {{ $post->title }}
            </a>
            <div class="mb-4 text-xs text-gray-600">
                {{ $description }}...
            </div>
            <div class="mb-3 border-t border-gray-300"></div>
            <div class="flex flex-col text-center lg:text-left lg:flex-row lg:space-x-4">
                <div class="flex justify-center space-x-1 lg:w-7/12 lg:justify-start">
                    @if ($post->user->profile_pic_url !== null)
                        <img style="max-height: 20px;" class="border border-blue-300 rounded-full" src="{{ Storage::url($post->user->profile_pic_url) }}">
                    @else
                        <img style="max-height: 20px;" class="border border-blue-300 rounded-full" src="https://tailwindui.com/img/ecommerce-images/home-page-02-edition-01.jpg">
                    @endif
                    <div class="self-center max-w-full text-xs truncate">
                        <span class="text-gray-500">Posted by</span>
                        <a href="#" class="text-blue-500">{{ $post->user->name }}</a>
                    </div>
                </div>
                <div class="text-xs text-gray-500 lg:w-3/12">
                    post: {{ $post->created_at->diffForHumans() }} 
                    @if (!empty($post->updated_at))
                        (edited {{ $post->updated_at->diffForHumans() }} )                                
                    @endif
                </div>
                <div class="flex justify-center space-x-2 text-xs text-gray-500 lg:w-2/12 lg:justify-end">
                    @auth
                        <div id="like-section">
                            <form action="{{ route('posts.react', ['postId' => $post->id, 'action' => $liked_by_user ? 'unlike' : 'like']) }}" method="POST" class="flex items-center mr-4">
                                @csrf
                                <button type="submit">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 fill-blue-500">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.633 10.25c.806 0 1.533-.446 2.031-1.08a9.041 9.041 0 0 1 2.861-2.4c.723-.384 1.35-.956 1.653-1.715a4.498 4.498 0 0 0 .322-1.672V2.75a.75.75 0 0 1 .75-.75 2.25 2.25 0 0 1 2.25 2.25c0 1.152-.26 2.243-.723 3.218-.266.558.107 1.282.725 1.282m0 0h3.126c1.026 0 1.945.694 2.054 1.715.045.422.068.85.068 1.285a11.95 11.95 0 0 1-2.649 7.521c-.388.482-.987.729-1.605.729H13.48c-.483 0-.964-.078-1.423-.23l-3.114-1.04a4.501 4.501 0 0 0-1.423-.23H5.904m10.598-9.75H14.25M5.904 18.5c.083.205.173.405.27.602.197.4-.078.898-.523.898h-.908c-.889 0-1.713-.518-1.972-1.368a12 12 0 0 1-.521-3.507c0-1.553.295-3.036.831-4.398C3.387 9.953 4.167 9.5 5 9.5h1.053c.472 0 .745.556.5.96a8.958 8.958 0 0 0-1.302 4.665c0 1.194.232 2.333.654 3.375Z" />
                                    </svg>
                                </button>
                                <p class="text-sm text-gray-500">{{ $post_like_count }}</p>
                            </form>
                        </div>
                        <div id="comment-section">
                            <a href="{{ route('posts.details', ['id' => $post->id]) }}" class="flex items-center mr-4 hover:text-blue-500">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12.76c0 1.6 1.123 2.994 2.707 3.227 1.087.16 2.185.283 3.293.369V21l4.076-4.076a1.526 1.526 0 0 1 1.037-.443 48.282 48.282 0 0 0 5.68-.494c1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0 0 12 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018Z" />
                                </svg>
                                <p class="text-sm text-gray-500">{{ $post->comments->count() }}</p>
                            </a>
                        </div>
                    @else
                        <div class="flex items-center mr-4">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6.633 10.25c.806 0 1.533-.446 2.031-1.08a9.041 9.041 0 0 1 2.861-2.4c.723-.384 1.35-.956 1.653-1.715a4.498 4.498 0 0 0 .322-1.672V2.75a.75.75 0 0 1 .75-.75 2.25 2.25 0 0 1 2.25 2.25c0 1.152-.26 2.243-.723 3.218-.266.558.107 1.282.725 1.282m0 0h3.126c1.026 0 1.945.694 2.054 1.715.045.422.068.85.068 1.285a11.95 11.95 0 0 1-2.649 7.521c-.388.482-.987.729-1.605.729H13.48c-.483 0-.964-.078-1.423-.23l-3.114-1.04a4.501 4.501 0 0 0-1.423-.23H5.904m10.598-9.75H14.25M5.904 18.5c.083.205.173.405.27.602.197.4-.078.898-.523.898h-.908c-.889 0-1.713-.518-1.972-1.368a12 12 0 0 1-.521-3.507c0-1.553.295-3.036.831-4.398C3.387 9.953 4.167 9.5 5 9.5h1.053c.472 0 .745.556.5.96a8.958 8.958 0 0 0-1.302 4.665c0 1.194.232 2.333.654 3.375Z" />
                            </svg>
                            <p class="text-sm text-gray-500">{{ $post_like_count }}</p>
                        </div>
                        <a x-data="" x-on:click.prevent="$dispatch('open-modal', 'signin-alert-modal')" class="flex items-center mr-4 hover:text-blue-500">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12.76c0 1.6 1.123 2.994 2.707 3.227 1.087.16 2.185.283 3.293.369V21l4.076-4.076a1.526 1.526 0 0 1 1.037-.443 48.282 48.282 0 0 0 5.68-.494c1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0 0 12 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018Z" />
                            </svg>
                            <p class="text-sm text-gray-500">{{ $post->comments->count() }}</p>
                        </a>
                    @endif
                </div>
            </div>
        </div>
        <div class="flex flex-col w-1/12 mt-2 space-y-2 text-center">
            @if ($post->user->id == Auth::id() && $post->comments->count() < 1)
                <div class="hidden sm:flex sm:items-center sm:ms-6">
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="inline-flex items-center px-3 py-2 text-sm font-medium leading-4 text-gray-500 transition duration-150 ease-in-out bg-white border border-transparent rounded-md dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none">
                                <div class="ms-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 12a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0ZM12.75 12a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0ZM18.75 12a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" />
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
                            <x-dropdown-link href="#" onclick="confirmDelete('{{ $post->id }}')">
                                {{ __('Delete') }}
                            </x-dropdown-link>
                        </x-slot>
                    </x-dropdown>
                </div>
            @endif
        </div>
    </div>
</div> 

<script>
    function confirmDelete(id) {
        if (confirm('Are you sure you want to delete?')) {
            event.preventDefault();
            document.getElementById('delete-post-form-' + id).submit();
        }
    }
</script>
