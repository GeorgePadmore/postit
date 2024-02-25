@props(['post'])

<div class="max-w-5xl px-4 pb-5 mx-auto sm:px-6 lg:px-8">
    <div class="w-full bg-white h-50 sm:py-2 max-w-screen">
        <div class="mt-6 space-y-12 lg:flex lg:gap-x-6 lg:space-y-0">
            <!-- First column -->
            <div class="relative group lg:w-1/7"></div>
            <div class="relative group lg:w-2/7">
                <div class="relative w-10 h-2 overflow-hidden bg-white rounded-lg group-hover:opacity-75 sm:h-10">
                    @php
                        $profilePicUrl = Auth::check() && Auth::user()->profile_pic_url ? Storage::url(Auth::user()->profile_pic_url) : 'https://tailwindui.com/img/ecommerce-images/home-page-02-edition-01.jpg';
                        $userName = Auth::check() ? Auth::user()->name : 'Guest';
                    @endphp
                    <img src="{{ $profilePicUrl }}" alt="Desk with leather desk pad, walnut desk organizer, wireless keyboard and mouse, and porcelain mug." class="object-cover object-center">
                </div>
                <h3 class="mt-6 text-sm text-gray-500">
                    <a href="#">
                        <span class="absolute inset-0"></span>
                        {{ $userName }}
                    </a>
                </h3>
            </div>

            <!-- Second column -->
            <div class="relative pb-2 group lg:w-10/12">
                @if (Auth::check() && Auth::user()->hasVerifiedEmail())
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
                            <x-primary-button class="ms-3">{{ __('Comment') }}</x-primary-button>
                        </div>
                    </form>
                @else
                    <div>
                        <p class="pb-5 text-sm text-gray-500"> Login / Signup in order to comment on this post. </p>
                        <x-input-error :messages="$errors->commentCreation->get('text')" class="mt-2" />
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>