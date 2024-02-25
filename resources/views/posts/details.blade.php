<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
            {{ __('Post IT') }}
        </h2>
    </x-slot>

    <div class="py-12">
        @if ($post)
            {{-- Display post card --}}
            <x-posts.post-card :post="$post" />  
    
            {{-- Display comments or show "No comments found" message --}}
            @forelse ($post->comments as $comment)
                <x-posts.comment-card :comment="$comment" />
            @empty
                <h3>No comments found</h3>
            @endforelse
    
            {{-- Write Comment section --}}
            <x-posts.write-comment :post="$post" />
        @else
            <h3>No post found</h3>
        @endif
    </div>

    <x-posts.notification-bar />

    <x-modal name="edit-post-modal" id="edit-post-modal" :show="$errors->postUpdate->isNotEmpty()" focusable>
        <x-posts.edit-post-form />
    </x-modal>

    <x-modal name="edit-comment-modal" id="edit-comment-modal" :show="$errors->commentUpdate->isNotEmpty()" focusable>
        <x-posts.edit-comment-form />
    </x-modal>

    {{-- Scripts --}}
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            // Edit Comment Button Click Event
            $('.edit-comment-btn').click(function() {
                var commentId = $(this).data('comment-id');
                // Send Ajax request to fetch comment details
                $.ajax({
                    url: '/comments/' + commentId + '/edit',
                    type: 'GET',
                    success: function(response) {
                        // Update form fields with fetched comment details
                        $('#editComment').val(response.comment.text);
                        $('#commentId').val(commentId);
                    },
                    error: function(xhr) {
                        console.log(xhr.responseText);
                    }
                });
            });

            // Edit Post Button Click Event
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

        // Confirm Delete Function
        function confirmDelete(commentId) {
            if (confirm('Are you sure you want to delete this comment?')) {
                document.getElementById('delete-comment-form-' + commentId).submit();
            }
        }
    </script>
</x-app-layout>
