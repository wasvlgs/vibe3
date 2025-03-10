<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="font-semibold text-xl mb-4">Users List</h3>

                    <!-- Displaying success or error messages -->
                    @if(session('message'))
                        <div class="alert alert-info mb-4">{{ session('message') }}</div>
                    @endif

                    <!-- Search Form -->
                    <form method="GET" action="{{ route('friends') }}" class="mb-6">
                        <div class="flex space-x-4">
                            <input
                                type="text"
                                name="search"
                                placeholder="Search by name or city"
                                class="px-4 py-2 border border-gray-300 rounded-md"
                                value="{{ request()->query('search') }}"
                            >
                            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md">Search</button>
                        </div>
                    </form>

                    <div class="overflow-x-auto">
                        <table class="w-full my-0 align-middle text-dark border-neutral-200">
                            <thead class="align-bottom">
                            <tr class="font-semibold text-[0.95rem] text-secondary-dark">
                                <th class="pb-3 text-center min-w-[175px] "> NAME</th>
                                <th class="pb-3 text-start min-w-[100px]">DATE JOIN</th>
                                <th class="pb-3 text-start min-w-[100px]">CITY</th>
                                <th class="pb-3 text-start min-w-[100px]">BIRTHDAY</th>
                                <th class="pb-3 text-start min-w-[100px]">ACTION</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($users as $user)
                                <tr>
                                    <!-- Cover photo and name -->
                                    <td class="px-4 py-2 border-b gap-2 flex items-center">
                                        @if($user->cover_photo)
                                            <img src="{{ asset( $user->cover_photo) }}" alt="Cover Photo" class="h-16 w-16 object-cover rounded-full">
                                        @else
                                            <img src="{{ asset( 'uploads/cover_picture/none.png') }}" alt="Cover Photo" class="h-16 w-16 object-cover rounded-full">
                                        @endif
                                        <span class="ml-4">{{ $user->name }}</span>
                                    </td>

                                    <!-- Date Join -->
                                    <td class="px-4 py-2 border-b">{{ $user->created_at->format('d/m/Y') }}</td>

                                    <!-- City -->
                                    <td class="px-4 py-2 border-b">{{ $user->city }}</td>

                                    <!-- Birthday -->
                                    <td class="px-4 py-2 border-b">{{ \Carbon\Carbon::parse($user->birthday)->format('d/m/Y') }}</td>

                                    <!-- Action: Add Friend -->
                                    <td class="px-4 py-2 border-b">
                                        @if(auth()->user()->id != $user->id)
                                            @php
                                                // Check if a request exists (sent or received)
                                                $existingRequest = $sentRequests->where('id_receiver', $user->id)->first()
                                                                ?? $receivedRequests->where('id_sender', $user->id)->first();
                                            @endphp

                                            @if ($existingRequest)
                                                @if ($existingRequest->status == 'pending')
                                                    @if ($existingRequest->id_sender == $user->id)
                                                        <!-- Auth user received request -> Show Accept/Cancel buttons -->
                                                        <form action="{{ route('ami.accepter', ['id' => $existingRequest->id]) }}" method="POST" class="inline">
                                                            @csrf
                                                            @method('PUT')
                                                            <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded-md">
                                                                Accept
                                                            </button>
                                                        </form>
                                                        <form action="{{ route('ami.annuler', ['id' => $existingRequest->id]) }}" method="POST" class="inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded-md">
                                                                Cancel
                                                            </button>
                                                        </form>
                                                    @else
                                                        <!-- Request sent by auth user -> Show "Request Pending" -->
                                                        <span class="text-yellow-500">Request Pending</span>
                                                    @endif
                                                @elseif ($existingRequest->status == 'accepted')
                                                    <span class="text-green-500">Friends</span>
                                                @elseif ($existingRequest->status == 'rejected')
                                                    <span class="text-red-500">Request Rejected</span>
                                                @endif
                                            @else
                                                <!-- No request exists -> Show "Add Friend" button -->
                                                <form action="{{ route('ami.envoyer') }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="id_sender" value="{{ auth()->user()->id }}">
                                                    <input type="hidden" name="id_receiver" value="{{ $user->id }}">
                                                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md">
                                                        Add Friend
                                                    </button>
                                                </form>
                                            @endif
                                        @else
                                            <span>Can't add yourself as friend</span>
                                        @endif
                                    </td>


                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pusher/7.0.3/pusher.min.js"></script>
        <script>
            document.addEventListener("DOMContentLoaded", function () {
                if (typeof Echo !== 'undefined') {
                    Echo.private('user.{{ auth()->id() }}')
                        .listen('FriendRequestAccepted', (event) => {
                            alert(event.sender_name + ' has accepted your friend request!');
                        });
                } else {
                    console.error("Echo is not defined. Make sure Laravel Echo is set up correctly.");
                }
            });
        </script>

    </div>
</x-app-layout>
