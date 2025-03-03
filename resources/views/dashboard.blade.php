<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 flex">
            <!-- Left Section: Posts -->
            <div class="w-2/3 bg-white shadow-sm sm:rounded-lg p-6">
                <h3 class="font-semibold text-xl mb-4">Recent Posts</h3>

                <!-- Add Post Button -->
                <button class="mb-4 bg-blue-500 text-white px-4 py-2 rounded" id="addPostButton">Add New Post</button>

                <!-- New Post Form (Hidden by default) -->
                <div id="newPostForm" class="bg-white p-6 rounded-lg shadow-md hidden">
                    <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-4">
                            <label for="titre" class="block text-gray-700">Title</label>
                            <input type="text" name="titre" id="titre" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md" required>
                        </div>

                        <div class="mb-4">
                            <label for="description" class="block text-gray-700">Description</label>
                            <textarea name="description" id="description" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md" required></textarea>
                        </div>

                        <div class="mb-4">
                            <label for="content" class="block text-gray-700">Content</label>
                            <textarea name="content" id="content" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md" required></textarea>
                        </div>

                        <div class="mb-4">
                            <label for="image" class="block text-gray-700">Image</label>
                            <input type="file" name="image" id="image" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md">
                        </div>

                        <div class="mb-4">
                            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Post</button>
                        </div>
                    </form>
                </div>

                <!-- Existing Posts -->
                @foreach ($posts as $post)
                    <div class="bg-gray-100 p-4 rounded-lg mb-4">
                        <div class="flex items-center mb-2">
                            <img src="{{ asset($post->user->cover_photo ?? 'uploads/cover_picture/none.png') }}" class="w-10 h-10 rounded-full" alt="User">
                            <span class="ml-2 font-semibold">{{ $post->user->name }}</span>
                        </div>
                        <h4 class="font-semibold">{{ $post->titre }}</h4>
                        <img class="p-2 rounded-xl w-full" src="{{ asset($post->image) }}" alt="{{ $post->titre }}">
                        <p>{{ $post->description }}</p>
                        <div class="text-sm text-gray-500 mt-2">{{ $post->created_at->diffForHumans() }}</div>

                        <!-- Edit Button (visible only for the post owner) -->
                        @if ($post->user_id === Auth::id())
                            <div class="flex gap-2">
                            <button class="text-blue-500 mt-2" id="editButton{{ $post->id }}">Edit</button>
                                <form action="{{ route('posts.destroy', $post->id) }}" method="POST" class="mt-2">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500">Delete</button>
                                </form>
                            </div>
                        @endif

                        <!-- Edit Post Form (Initially Hidden) -->
                        @if(Auth::id() === $post->user_id)
                            <div id="editForm{{ $post->id }}" class="hidden mt-4">
                                <form action="{{ route('posts.edit', $post->id) }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')

                                    <div class="mb-4">
                                        <label for="titre" class="block text-gray-700">Title</label>
                                        <input type="text" name="titre" value="{{ $post->titre }}" id="titre" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md" required>
                                    </div>

                                    <div class="mb-4">
                                        <label for="description" class="block text-gray-700">Description</label>
                                        <textarea name="description" id="description" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md" required>{{ $post->description }}</textarea>
                                    </div>

                                    <div class="mb-4">
                                        <label for="content" class="block text-gray-700">Content</label>
                                        <textarea name="content" id="content" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md" required>{{ $post->content }}</textarea>
                                    </div>

                                    <div class="mb-4">
                                        <label for="image" class="block text-gray-700">Image</label>
                                        <input type="file" name="image" id="image" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md">
                                    </div>

                                    <div class="mb-4">
                                        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Update Post</button>
                                    </div>
                                </form>
                            </div>
                        @endif

                        <!-- Like Button -->
                        <form action="{{ route('posts.like', $post->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="text-gray-500 hover:text-red-500">
                                @if ($post->likes()->where('user_id', Auth::id())->exists())
                                    <span class="text-red-500">❤️ Liked</span>
                                @else
                                    <span>🤍 Like</span>
                                @endif
                            </button>
                        </form>

                        <!-- Comment Section -->
                        <div class="mt-4">
                            <h5 class="font-semibold">Comments</h5>
                            <form action="{{ route('posts.comment', $post->id) }}" method="POST">
                                @csrf
                                <textarea name="content" class="w-full p-2 mt-2 border border-gray-300 rounded" required></textarea>
                                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded mt-2">Add Comment</button>
                            </form>

                            <!-- Display Comments -->
                            <div class="mt-4">
                                @if ($post->commantaires->isEmpty())
                                    <p class="text-gray-500">No comments yet.</p>
                                @else
                                    @foreach ($post->commantaires as $comment)
                                        <div class="border-b pb-2 mb-2">
                                            <span class="font-semibold">{{ $comment->user->name }}</span>: {{ $comment->commantaire }}
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach

            </div>

            <!-- Right Section: Friends List -->
            <div class="w-1/3 ml-6 bg-white shadow-sm sm:rounded-lg p-6">
                <h3 class="font-semibold text-xl mb-4">Friends List</h3>

                @if ($friends->isEmpty())
                    <p class="text-gray-500">No friends yet.</p>
                @else
                    <ul>
                        @foreach ($friends as $friend)
                            <li class="flex items-center mb-4">
                                <a href="{{ route('profile', $friend->pseudo) }}" class="flex items-center">
                                    <img src="{{ asset($friend->cover_photo ?? 'uploads/cover_picture/none.png') }}" class="w-10 h-10 rounded-full" alt="Profile">
                                    <span class="ml-2">{{ $friend->name }}</span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>
    </div>

    <script>
        // Toggle the visibility of the New Post Form
        document.getElementById('addPostButton').addEventListener('click', function() {
            const form = document.getElementById('newPostForm');
            form.classList.toggle('hidden');
        });
        @foreach ($posts as $post)
        const editButton{{ $post->id }} = document.getElementById('editButton{{ $post->id }}');
        const editForm{{ $post->id }} = document.getElementById('editForm{{ $post->id }}');

        if (editButton{{ $post->id }}) {
            editButton{{ $post->id }}.addEventListener('click', function() {
                editForm{{ $post->id }}.classList.toggle('hidden');
            });
        }
        @endforeach
    </script>
</x-app-layout>
