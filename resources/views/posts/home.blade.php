<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
                {{ __('Post IT') }}
            </h2>

            <!-- Search Form -->
            <x-posts.search-form :keyword="$keyword ?? ''" />

        </div>
    </x-slot>



    <div class="py-12">
        <div class="max-w-2xl pb-4 mx-auto sm:px-6 lg:px-8">
            <x-posts.new-post-button/>           
        </div>


        @if (isset($search))
            <div class="max-w-5xl px-4 pb-4 mx-auto sm:px-6 lg:px-8">
                
                <div class="w-full mx-auto mt-20">
                    <!-- Tab Buttons -->
                    <div class="p-2 bg-blue-500 rounded-t-lg">
                    <div class="flex justify-center space-x-4">
                        <button class="px-4 py-2 font-semibold text-white border-b-4 border-blue-700 hover:bg-blue-700 focus:outline-none tab-button" onclick="showTab('posts')">Posts</button>
                        <button class="px-4 py-2 font-semibold text-white border-b-4 border-blue-700 hover:bg-blue-700 focus:outline-none tab-button" onclick="showTab('comments')">Comments</button>
                    </div>
                    </div>
                
                    <!-- Tab Content -->
                    <div id="posts" class="p-4 rounded-lg shadow-md tab-content">                        
                        
                        @if ($posts)
                            @foreach ($posts as $post)
                                <x-posts.post-card :post="$post" />                         
                            @endforeach
                        @endif

                    </div>

                    <div id="comments" class="hidden p-4 bg-white rounded-lg shadow-md tab-content">
                        
                        @if ($comments)

                            @foreach ($comments as $comment)
                                <x-posts.comment-card :comment="$comment" />                                                         
                            @endforeach

                        @endif

                    </div>
                </div>
                
            </div>
        @endif


        @if (!isset($search) && $posts)
            @foreach ($posts as $post)              
                <x-posts.post-card :post="$post" />
            @endforeach
        @endif

    </div>


    <x-posts.notification-bar />
    

    <x-modal name="create-post-modal" :show="$errors->postCreation->isNotEmpty()" focusable>
        <x-posts.create-post-form />
    </x-modal>

    <x-modal name="edit-post-modal" id="edit-post-modal" :show="$errors->postUpdate->isNotEmpty()" focusable>
        <x-posts.edit-post-form />
    </x-modal>

    <x-modal name="signin-alert-modal" :show="$errors->postCreation->isNotEmpty()" focusable>
        <x-posts.signin-alert-modal />
    </x-modal>


    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>

        $(document).ready(function() {

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


            $('.post-button-email-unverified').click(function () {
                // alert("Your email isn't verified.");
                Swal.fire({
                    title: 'Unverified Email!',
                    text: 'Please verify your email in order to make a post.',
                    icon: 'warning',
                    confirmButtonText: 'OK'
                });
            });
            
        });


        function confirmDelete(postId) {
            if (confirm('Are you sure you want to delete this post?')) {
                document.getElementById('delete-post-form-' + postId).submit();
            }
        }


        function navigateToPostDetails(postId){
            // Define the URL of the route using the post ID from the data attribute
            var routeUrl = '{{ route("posts.details", ["id" => ":postId"]) }}';
            routeUrl = routeUrl.replace(':postId', postId); // Replace placeholder with actual post ID

            // // Redirect to the specified route
            window.location.href = routeUrl;
        }



        function showTab(tabId) {
            // Hide all tab content
            const tabContents = document.querySelectorAll('.tab-content');
            tabContents.forEach((content) => {
                content.classList.add('hidden');
            });
        
            // Show the selected tab content
            const selectedTab = document.getElementById(tabId);
            if (selectedTab) {
                selectedTab.classList.remove('hidden');
            }
        
            // Remove the 'active' class from all tab buttons
            const tabButtons = document.querySelectorAll('.tab-button');
            tabButtons.forEach((button) => {
                button.classList.remove('active');
            });
        
            // Add the 'active' class to the clicked tab button
            const clickedButton = document.querySelector(`[onclick="showTab('${tabId}')"]`);
            if (clickedButton) {
                clickedButton.classList.add('active');
            }
        }
            
        
        // Initialize the first tab
        showTab('posts');

    </script>



</x-app-layout>
