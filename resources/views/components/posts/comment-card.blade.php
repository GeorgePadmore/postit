@props(['comment'])

@php
    $comment_like_count = ($comment->total_likes == null) ? 0 : $comment->total_likes;
    $stringCut = substr(strip_tags($comment->post_title), 0, 440);//substr(strip_tags(markdown($post->body)), 0, 440);
    $endPoint = strrpos($stringCut, ' ');
    $postTitle = $endPoint? substr($stringCut, 0, $endPoint) : substr($stringCut, 0);

    $voteable_up = true;
    $voteable_down = true;
    $user_react = false;
@endphp


<div id="comment-section" class="max-w-5xl px-4 pb-1 mx-auto sm:px-6 lg:px-8">

    <div id="clickableDiv" class="relative flex p-8 mb-6 space-x-2 overflow-hidden transition-all duration-200 bg-white rounded-sm shadow discussion-item group hover:shadow-xl"
            data-id="{{ $comment->id }}"
            data-post-id="{{ $comment->post_id }}"
            data-vote="{{ $user_react }}"
            onclick="navigateToPostDetails('{{ $comment->post_id }}')"
        >
        <div class="flex flex-col w-1/12 mt-2 space-y-2 text-center">

            @auth
                
                @if ($comment->liked_by_user)
                    <form action="{{ route('comments.unlikeComment', ['commentId' => $comment->id]) }}" method="POST" class="flex items-center mr-4">
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
                    <form action="{{ route('comments.likeComment', ['commentId' => $comment->id]) }}"
                        method="POST" class="flex items-center mr-4">
                        @csrf
                        <div class="flex flex-row justify-center text-sm cursor-pointer vote-up">
                            <button type="submit">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 10.5 12 3m0 0 7.5 7.5M12 3v18" />
                                    </svg>                                                                                                    
                            </button>
                
                        </div>
                    </form>
                @endif

            @else

                <div class="flex flex-row justify-center text-sm cursor-pointer vote-up">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 10.5 12 3m0 0 7.5 7.5M12 3v18" />
                    </svg>
                </div>

            @endauth


            <div class="@auth vote-count @endauth flex items-center mr-1 ml-2">
                {{ $comment_like_count }}
            </div>
        
            @auth
                
                @if ($comment->liked_by_user)
                    <form action="{{ route('comments.unlikeComment', ['commentId' => $comment->id]) }}" method="POST" class="flex items-center mr-4">
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
                    <form action="{{ route('comments.likeComment', ['commentId' => $comment->id]) }}"
                        method="POST" class="flex items-center mr-4">
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
                @endif

            @else

                <div class="
                        flex flex-row justify-center text-sm
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
            {{-- <a href="#" class="block mb-3 text-xl font-bold text-gray-800">
                {{ $post->title }}
            </a> --}}
            <div class="mb-4 text-sm italic text-gray-600">
                {{ $postTitle }}...
            </div>
            <div class="mb-4 text-base text-gray-600">
                {{ $comment->text }}
            </div>
            <div class="mb-3 border-t border-gray-300"></div>
            <div class="flex flex-col text-center lg:text-left lg:flex-row lg:space-x-4">
                <div class="flex justify-center space-x-1 lg:w-7/12 lg:justify-start">

                    @if ($comment->user->profile_pic_url !== null)
                        <img style="max-height: 20px;" class="border border-blue-300 rounded-full" src="{{ Storage::url($comment->user->profile_pic_url) }}">
                    @else
                        <img style="max-height: 20px;" class="border border-blue-300 rounded-full" src="https://tailwindui.com/img/ecommerce-images/home-page-02-edition-01.jpg">
                    @endif

                    <div class="self-center max-w-full text-xs truncate">
                        <span class="text-gray-500">Comment by</span>
                        <a href="#" class="text-blue-500">{{ $comment->user->name }}</a>
                    </div>
                </div>
                <div class="text-xs text-gray-500 lg:w-3/12">
                    comment: {{ $comment->created_at->diffForHumans() }}
                    @if (!empty($comment->updated_at))
                        (edited {{ $comment->updated_at->diffForHumans() }} )                                
                    @endif
                </div>
                <div class="flex justify-center space-x-2 text-xs text-gray-500 lg:w-2/12 lg:justify-end">

                    @auth
                        
                        <div id="like-section">
                            @if ($comment->liked_by_user)
                                <form action="{{ route('comments.unlikeComment', ['commentId' => $comment->id]) }}" method="POST" class="flex items-center mr-4">
                                    @csrf
                                    <button type="submit">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor"
                                            class="w-6 h-6 fill-blue-500">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M6.633 10.25c.806 0 1.533-.446 2.031-1.08a9.041 9.041 0 0 1 2.861-2.4c.723-.384 1.35-.956 1.653-1.715a4.498 4.498 0 0 0 .322-1.672V2.75a.75.75 0 0 1 .75-.75 2.25 2.25 0 0 1 2.25 2.25c0 1.152-.26 2.243-.723 3.218-.266.558.107 1.282.725 1.282m0 0h3.126c1.026 0 1.945.694 2.054 1.715.045.422.068.85.068 1.285a11.95 11.95 0 0 1-2.649 7.521c-.388.482-.987.729-1.605.729H13.48c-.483 0-.964-.078-1.423-.23l-3.114-1.04a4.501 4.501 0 0 0-1.423-.23H5.904m10.598-9.75H14.25M5.904 18.5c.083.205.173.405.27.602.197.4-.078.898-.523.898h-.908c-.889 0-1.713-.518-1.972-1.368a12 12 0 0 1-.521-3.507c0-1.553.295-3.036.831-4.398C3.387 9.953 4.167 9.5 5 9.5h1.053c.472 0 .745.556.5.96a8.958 8.958 0 0 0-1.302 4.665c0 1.194.232 2.333.654 3.375Z" />
                                        </svg>
                                    </button>

                                    <p class="text-sm text-gray-500">
                                        @if ($comment->total_likes == null)
                                            0
                                        @else
                                            {{ $comment->total_likes }}
                                        @endif
                                    </p>
                                </form>
                            @else
                                <form action="{{ route('comments.likeComment', ['commentId' => $comment->id]) }}"
                                    method="POST" class="flex items-center mr-4">
                                    @csrf
                                    <button type="submit">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                            viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                            class="w-6 h-6">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M6.633 10.25c.806 0 1.533-.446 2.031-1.08a9.041 9.041 0 0 1 2.861-2.4c.723-.384 1.35-.956 1.653-1.715a4.498 4.498 0 0 0 .322-1.672V2.75a.75.75 0 0 1 .75-.75 2.25 2.25 0 0 1 2.25 2.25c0 1.152-.26 2.243-.723 3.218-.266.558.107 1.282.725 1.282m0 0h3.126c1.026 0 1.945.694 2.054 1.715.045.422.068.85.068 1.285a11.95 11.95 0 0 1-2.649 7.521c-.388.482-.987.729-1.605.729H13.48c-.483 0-.964-.078-1.423-.23l-3.114-1.04a4.501 4.501 0 0 0-1.423-.23H5.904m10.598-9.75H14.25M5.904 18.5c.083.205.173.405.27.602.197.4-.078.898-.523.898h-.908c-.889 0-1.713-.518-1.972-1.368a12 12 0 0 1-.521-3.507c0-1.553.295-3.036.831-4.398C3.387 9.953 4.167 9.5 5 9.5h1.053c.472 0 .745.556.5.96a8.958 8.958 0 0 0-1.302 4.665c0 1.194.232 2.333.654 3.375Z" />
                                        </svg>
                                    </button>

                                    <p class="text-sm text-gray-500"> {{ $comment_like_count }} </p>
                                </form>
                            @endif

                        </div>

                    @else

                        <div class="flex items-center mr-4">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M6.633 10.25c.806 0 1.533-.446 2.031-1.08a9.041 9.041 0 0 1 2.861-2.4c.723-.384 1.35-.956 1.653-1.715a4.498 4.498 0 0 0 .322-1.672V2.75a.75.75 0 0 1 .75-.75 2.25 2.25 0 0 1 2.25 2.25c0 1.152-.26 2.243-.723 3.218-.266.558.107 1.282.725 1.282m0 0h3.126c1.026 0 1.945.694 2.054 1.715.045.422.068.85.068 1.285a11.95 11.95 0 0 1-2.649 7.521c-.388.482-.987.729-1.605.729H13.48c-.483 0-.964-.078-1.423-.23l-3.114-1.04a4.501 4.501 0 0 0-1.423-.23H5.904m10.598-9.75H14.25M5.904 18.5c.083.205.173.405.27.602.197.4-.078.898-.523.898h-.908c-.889 0-1.713-.518-1.972-1.368a12 12 0 0 1-.521-3.507c0-1.553.295-3.036.831-4.398C3.387 9.953 4.167 9.5 5 9.5h1.053c.472 0 .745.556.5.96a8.958 8.958 0 0 0-1.302 4.665c0 1.194.232 2.333.654 3.375Z" />
                            </svg>

                            <p class="text-sm text-gray-500"> {{ $comment_like_count }} </p>
                        </div>

                    @endauth
                    
                </div>
            </div>
        </div>

        <div class="flex flex-col w-1/12 mt-2 space-y-2 text-center">

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
                            
                            <x-new-dropdown-link class="edit-comment-btn" x-data="" data-comment-id="{{ $comment->id }}" x-on:click.prevent="$dispatch('open-modal', 'edit-comment-modal')">
                                {{ __('Edit') }}
                            </x-new-dropdown-link>


                            <form id="delete-comment-form-{{ $comment->id }}" method="POST" action="{{ route('comments.delete', ['id' => $comment->id]) }}" style="display: none;">
                                @csrf
                            </form>

                            <x-dropdown-link href="#" onclick="confirmDelete('{{ $comment->id }}')">
                                {{ __('Delete') }}
                            </x-dropdown-link>

                        </x-slot>
                    </x-dropdown>
                </div>
                
            @endif


        </div>
    </div>

</div> 