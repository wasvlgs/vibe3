@php use Illuminate\Support\Facades\Auth; @endphp
<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Cover Photo Section -->
        <div class="relative">
            <div class="h-64 w-full bg-gray-200 rounded-b-lg overflow-hidden">
                @if(!$user->cover_photo)
                    <img src="{{ asset( $user->cover_photo) }}" alt="Cover Photo" class="w-full h-full object-cover">
                @else
                    <div class="w-full h-full bg-gradient-to-r from-blue-400 to-indigo-500"></div>
                @endif
            </div>

            <!-- Profile Photo -->
            <div class="absolute -bottom-16 left-10">
                <div class="h-32 w-32 rounded-full border-4 border-white bg-gray-200 overflow-hidden">
                    @if($user->cover_photo)
                        <img src="{{ asset( $user->cover_photo) }}" alt="{{ $user->name }}" class="h-full w-full object-cover">
                    @else
                        <div class="h-full w-full flex items-center justify-center bg-gray-300 text-gray-600 text-2xl font-bold">
                            {{ substr($user->name, 0, 1) }}
                        </div>
                    @endif
                </div>
            </div>

            <!-- Friend Request Button (if not viewing own profile) -->

        </div>

        <!-- Profile Info -->
        <div class="mt-20 px-8">
            <div class="flex flex-col md:flex-row justify-between">
                <div>
                    <h1 class="text-3xl font-bold">{{ $user->name }}</h1>
                    @if($user->pseudo)
                        <p class="text-gray-600">{{ $user->pseudo }}</p>
                    @endif
                    @if($user->bio)
                        <p class="mt-2 text-gray-700">{{ $user->bio }}</p>
                    @endif
                </div>
                <div class="mt-4 md:mt-0">
                    <div class="flex flex-col space-y-1 text-gray-600">
                        @if($user->city)
                            <div class="flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <span>Lives in {{ $user->city }}</span>
                            </div>
                        @endif
                        @if($user->birthday)
                            <div class="flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <span>Born on {{ $user->birthday->format('F j, Y') }}</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Tabs -->
            <div class="mt-8 border-b border-gray-200">
                <nav class="-mb-px flex space-x-8">
                    <a href="#" class="border-b-2 border-blue-500 pb-3 px-1 text-blue-600 font-medium">Posts</a>
                    <a href="#" class="border-b-2 border-transparent pb-3 px-1 text-gray-500 font-medium hover:text-gray-700 hover:border-gray-300">About</a>
                    <a href="#" class="border-b-2 border-transparent pb-3 px-1 text-gray-500 font-medium hover:text-gray-700 hover:border-gray-300">
                        Friends <span class="ml-1 text-sm bg-gray-100 px-2 py-0.5 rounded-full">{{ $friends->count() }}</span>
                    </a>
                    <a href="#" class="border-b-2 border-transparent pb-3 px-1 text-gray-500 font-medium hover:text-gray-700 hover:border-gray-300">Photos</a>
                </nav>
            </div>

            <div class="mt-6 grid grid-cols-1 md:grid-cols-4 gap-6">
                <!-- Left Column - Friends -->
                <div class="md:col-span-1">
                    <div class="bg-white p-4 rounded-lg shadow">
                        <h2 class="font-bold text-gray-800 mb-3">Friends ({{ $friends->count() }})</h2>
                        @if($friends->count() > 0)
                            <div class="grid grid-cols-3 gap-2">
                                @foreach($friends->take(9) as $friend)
                                    <a href="{{ route('profile', $friend->pseudo) }}" class="block">
                                        <div class="aspect-w-1 aspect-h-1 bg-gray-200 rounded overflow-hidden">
                                            @if($friend->cover_photo)
                                                <img src="{{ asset($friend->cover_photo) }}" alt="{{ $friend->name }}" class="object-cover">
                                            @else
                                                <div class="h-full w-full flex items-center justify-center bg-gray-300 text-gray-600 font-bold">
                                                    {{ asset('uploads/cover_picture/none.png') }}
                                                </div>
                                            @endif
                                        </div>
                                        <p class="text-xs mt-1 truncate">{{ $friend->name }}</p>
                                    </a>
                                @endforeach
                            </div>
                            @if($friends->count() > 9)
                                <a href="#" class="block text-center text-sm text-blue-500 mt-3 hover:underline">See All Friends</a>
                            @endif
                        @else
                            <p class="text-gray-500 text-sm">No friends to display</p>
                        @endif
                    </div>
                </div>

                <!-- Right Column - Posts -->
                <div class="md:col-span-3">
                    <!-- Create Post (only on own profile) -->
                    @if(Auth::id() === $user->id)
                        <div class="bg-white rounded-lg shadow mb-6 p-4">
                            <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="flex items-start space-x-4">
                                    <div class="flex-shrink-0">
                                        <div class="h-10 w-10 rounded-full bg-gray-200 overflow-hidden">
                                            @if(Auth::user()->cover_photo)
                                                <img src="{{ asset(Auth::user()->cover_photo) }}" alt="{{ Auth::user()->name }}" class="h-full w-full object-cover">
                                            @else
                                                <div class="h-full w-full flex items-center justify-center bg-gray-300 text-gray-600 font-bold">
                                                    {{ substr(Auth::user()->name, 0, 1) }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <div class="border border-gray-300 rounded-lg overflow-hidden focus-within:border-blue-500">
                                            <textarea id="post-content" name="description" rows="3" class="block w-full border-0 py-3 px-4 resize-none focus:ring-0 sm:text-sm" placeholder="What's on your mind?"></textarea>

                                            <div class="border-t border-gray-200 p-3 flex justify-between items-center">
                                                <div class="flex space-x-4">
                                                    <label for="image-upload" class="cursor-pointer flex items-center text-gray-600 hover:text-gray-800">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                        </svg>
                                                        <span>Photo</span>
                                                        <input id="image-upload" name="image" type="file" class="sr-only" accept="image/*">
                                                    </label>
                                                </div>
                                                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                                    Post
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    @endif

                    <!-- Posts Feed -->
                    @if($posts->count() > 0)
                        @foreach($posts as $post)
                            <div class="bg-white rounded-lg shadow mb-6 overflow-hidden">
                                <!-- Post Header -->
                                <div class="p-4 flex items-center">
                                    <a href="{{ route('profile', $post->user->pseudo) }}" class="flex-shrink-0">
                                        <div class="h-10 w-10 rounded-full bg-gray-200 overflow-hidden">
                                            @if($post->user->cover_photo)
                                                <img src="{{ asset( $post->user->cover_photo) }}" alt="{{ $post->user->name }}" class="h-full w-full object-cover">
                                            @else
                                                <div class="h-full w-full flex items-center justify-center bg-gray-300 text-gray-600 font-bold">
                                                    {{ substr($post->user->name, 0, 1) }}
                                                </div>
                                            @endif
                                        </div>
                                    </a>
                                    <div class="ml-3">
                                        <a href="{{ route('profile', $post->user->pseudo) }}" class="text-base font-medium text-gray-800">{{ $post->user->name }}</a>
                                        <p class="text-sm text-gray-500">{{ $post->created_at->diffForHumans() }}</p>
                                    </div>

                                    <!-- Post Options Dropdown (if own post) -->
                                    @if(Auth::id() === $post->user_id)
                                        <div class="ml-auto relative">
                                            <button type="button" class="text-gray-400 hover:text-gray-600 focus:outline-none" id="post-options-{{ $post->id }}" onclick="toggleDropdown('post-options-menu-{{ $post->id }}')">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                    <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z" />
                                                </svg>
                                            </button>
                                            <div id="post-options-menu-{{ $post->id }}" class="hidden origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none" role="menu" aria-orientation="vertical" aria-labelledby="post-options-{{ $post->id }}">
                                                <div class="py-1" role="none">
                                                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">Edit Post</a>
                                                    <form action="{{ route('posts.destroy', $post) }}" method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100" role="menuitem">Delete Post</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>

                                <!-- Post Content -->
                                @if($post->titre)
                                    <h3 class="px-4 font-medium text-lg">{{ $post->titre }}</h3>
                                @endif

                                @if($post->description)
                                    <div class="px-4 py-2 text-gray-800">
                                        {{ $post->description }}
                                    </div>
                                @endif

                                @if($post->image)
                                    <div class="mt-2">
                                        <img src="{{ asset( $post->image) }}" alt="Post image" class="w-full h-auto">
                                    </div>
                                @endif

                                <!-- Post Stats (Likes and Comments) -->
                                <div class="px-4 py-2 border-t border-gray-200">
                                    <div class="flex justify-between text-gray-500 text-sm">
                                        <div>
                                            <span>{{ $post->likes->count() }} {{ Str::plural('like', $post->likes->count()) }}</span>
                                        </div>
                                        <div>
                                            <span>{{ $post->commantaires->count() }} {{ Str::plural('comment', $post->commantaires->count()) }}</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Post Actions -->
                                <div class="px-4 py-1 border-t border-gray-200 flex">
                                    @php
                                        $hasLiked = $post->likes->where('user_id', Auth::id())->count() > 0;
                                    @endphp

                                    <form action="{{ route('posts.like', $post) }}" method="POST" class="flex-1">
                                        @csrf
                                        <button type="submit" class="w-full flex items-center justify-center py-2 text-sm font-medium {{ $hasLiked ? 'text-blue-600' : 'text-gray-600 hover:text-gray-800' }}">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905a3.61 3.61 0 01-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5" />
                                            </svg>
                                            Like
                                        </button>
                                    </form>

                                    <button type="button" class="flex-1 flex items-center justify-center py-2 text-sm font-medium text-gray-600 hover:text-gray-800" onclick="toggleComments('comments-section-{{ $post->id }}')">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                        </svg>
                                        Comment
                                    </button>
                                </div>

                                <!-- Comments Section (Initially Hidden) -->
                                <div id="comments-section-{{ $post->id }}" class="hidden border-t border-gray-200">
                                    <!-- Existing Comments -->
                                    @if($post->commantaires->count() > 0)
                                        <div class="px-4 py-3 space-y-3">
                                            @foreach($post->commantaires as $comment)
                                                <div class="flex items-start space-x-3">
                                                    <a href="{{ route('profile', $comment->user->pseudo) }}" class="flex-shrink-0">
                                                        <div class="h-8 w-8 rounded-full bg-gray-200 overflow-hidden">
                                                            @if($comment->user->cover_photo)
                                                                <img src="{{ asset( $comment->user->cover_photo) }}" alt="{{ $comment->user->name }}" class="h-full w-full object-cover">
                                                            @else
                                                                <div class="h-full w-full flex items-center justify-center bg-gray-300 text-gray-600 text-xs font-bold">
                                                                    {{ substr($comment->user->name, 0, 1) }}
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </a>
                                                    <div class="flex-1 min-w-0">
                                                        <div class="bg-gray-100 rounded-lg px-3 py-2">
                                                            <a href="{{ route('profile', $comment->user->pseudo) }}" class="font-medium text-gray-900">{{ $comment->user->name }}</a>
                                                            <p class="text-gray-700">{{ $comment->commantaire }}</p>
                                                        </div>
                                                        <div class="mt-1 flex items-center space-x-2 text-xs text-gray-500">
                                                            <span>{{ $comment->created_at->diffForHumans() }}</span>
                                                            <span>·</span>
                                                            <button class="font-medium hover:underline">Like</button>
                                                            <span>·</span>
                                                            <button class="font-medium hover:underline">Reply</button>


                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif

                                    <!-- Add Comment Form -->
                                    <div class="px-4 py-3 border-t border-gray-200">
                                        <form action="{{ route('posts.comment', $post->id ) }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="post_id" value="{{ $post->id }}">
                                            <div class="flex items-start space-x-3">
                                                <div class="flex-shrink-0">
                                                    <div class="h-8 w-8 rounded-full bg-gray-200 overflow-hidden">
                                                        @if(Auth::user()->cover_photo)
                                                            <img src="{{ asset(Auth::user()->cover_photo) }}" alt="{{ Auth::user()->name }}" class="h-full w-full object-cover">
                                                        @else
                                                            <div class="h-full w-full flex items-center justify-center bg-gray-300 text-gray-600 text-xs font-bold">
                                                                {{ substr(Auth::user()->name, 0, 1) }}
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="min-w-0 flex-1 relative">
                                                    <input type="text" name="content" class="block w-full rounded-full border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 pl-4 pr-12 py-2" placeholder="Write a comment...">
                                                    <button type="submit" class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-500" viewBox="0 0 20 20" fill="currentColor">
                                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-8.707l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 001.414 1.414L9 9.414V13a1 1 0 102 0V9.414l1.293 1.293a1 1 0 001.414-1.414z" clip-rule="evenodd" />
                                                        </svg>
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="bg-white rounded-lg shadow p-6 text-center">
                            <p class="text-gray-500">No posts to display</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript for toggling dropdowns and comments -->
    <script>
        function toggleDropdown(id) {
            const dropdown = document.getElementById(id);
            if (dropdown.classList.contains('hidden')) {
                dropdown.classList.remove('hidden');
            } else {
                dropdown.classList.add('hidden');
            }
        }

        function toggleComments(id) {
            const commentsSection = document.getElementById(id);
            if (commentsSection.classList.contains('hidden')) {
                commentsSection.classList.remove('hidden');
            } else {
                commentsSection.classList.add('hidden');
            }
        }

        // Close dropdowns when clicking outside
        window.addEventListener('click', function(event) {
            const dropdowns = document.querySelectorAll('[id^="post-options-menu-"]');
            dropdowns.forEach(function(dropdown) {
                if (!event.target.closest(`#${dropdown.id.replace('menu-', '')}`)) {
                    dropdown.classList.add('hidden');
                }
            });
        });
    </script>
</x-app-layout>
