
@auth

    <div class="sticky flex-none forum-sidebar-right" data-sticky-offset="100">
        @if (!Auth::user()->hasVerifiedEmail())
            <a href="#" class="block px-4 py-4 text-sm font-semibold text-center text-white bg-blue-500 rounded btn post-button-email-unverified">
                <i class="fas fa-plus"></i>
                New Post
            </a>
        @else

            <a href="{{ route('posts.create') }}" class="block px-4 py-4 text-sm font-semibold text-center text-white bg-blue-400 rounded btn" x-data="" x-on:click.prevent="$dispatch('open-modal', 'create-post-modal')">
                <i class="fas fa-plus"></i>
                New Post
            </a>

        @endif
        
    </div>


@endauth
