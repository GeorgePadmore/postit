@props(['keyword'])

<div class="w-3/5 mx-auto">
    <div class="w-full overflow-hidden bg-white shadow-sm dark:bg-gray-800 sm:rounded-lg">
        <form method="post" action="{{ route('posts.search') }}">
            @csrf
            @method('post')
            <div class="flex items-center p-2">
                <input type="text" id="keyword" name="keyword" placeholder="Search post(s) or comment(s)" class="w-full px-4 py-2 border-2 border-gray-300 rounded-l-md focus:outline-none focus:ring focus:border-blue-300 dark:bg-gray-700 dark:text-gray-100" value="{{ $keyword }}">
                <button type="submit" class="px-4 py-2 text-white bg-blue-500 rounded-r-md focus:outline-none focus:ring focus:border-blue-300">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                    </svg>
                </button>
            </div>
        </form>
    </div>
</div>
