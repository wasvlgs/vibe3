<x-guest-layout>
    <!-- Navbar Section -->
    <div class="bg-white py-4 shadow-md">
        <div class="max-w-7xl mx-auto flex items-center justify-between px-6">
            <img class="h-10" src="uploads/logo.jpeg" alt="Logo">
            <div class="flex flex-col">
                <form method="POST" action="{{ route('login') }}" class="flex flex-col space-y-2">
                    @csrf
                    <input type="email" name="email" id="email" class="px-4 py-2 rounded-md border border-gray-300" placeholder="Email" required autofocus autocomplete="username">
                    <input type="password" name="password" id="password" class="px-4 py-2 rounded-md border border-gray-300" placeholder="Password" required autocomplete="current-password">
                    <button type="submit" class="bg-indigo-600 text-white py-2 px-4 rounded-md">Login</button>
                </form>
                <div class="flex items-center justify-center space-x-2 mt-2">
                    <a href="{{ route('login.google') }}" class="bg-red-600 text-white py-2 px-4 rounded-md">
                        Login with Google
                    </a>
                    <a href="{{ route('login.facebook') }}" class="bg-blue-600 text-white py-2 px-4 rounded-md">
                        Login with Facebook
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Section -->
    <div class="flex min-h-screen justify-center items-center px-6 py-12 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 max-w-7xl w-full">
            <!-- Left: Welcome Section -->
            <div class="space-y-6 bg-white p-8 rounded-lg">
                <div class="flex justify-center">
                    <img src="uploads/logo.jpeg" alt="Logo" class="w-28 h-28 object-contain">
                </div>
                <h2 class="text-center text-2xl font-bold text-gray-900">Avec Vibe</h2>
                <p class="text-center text-gray-600">Partagez et restez en contact avec votre entourage.</p>
            </div>

            <!-- Right: Signup Form -->
            <div class="space-y-6 bg-white p-8 rounded-lg shadow-md">
                <h2 class="text-center text-2xl font-bold text-gray-900">Create your account</h2>
                <form class="space-y-6" method="POST" action="{{ route('register.post') }}" enctype="multipart/form-data">
                    @csrf

                    <div>
                        <x-input-label for="name" :value="__('Full Name')" />
                        <x-text-input id="name" class="block w-full" type="text" name="name" :value="old('name')" required autocomplete="name" />
                    </div>
                    <div>
                        <x-input-label for="pseudo" :value="__('Pseudo')" />
                        <x-text-input id="pseudo" class="block w-full" type="text" name="pseudo" :value="old('pseudo')" required autocomplete="pseudo" />
                    </div>

                    <div>
                        <x-input-label for="email" :value="__('Email')" />
                        <x-text-input id="email" class="block w-full" type="email" name="email" :value="old('email')" required autocomplete="email" />
                    </div>
                    <div>
                        <label for="birthday" class="block text-sm font-medium text-gray-900">Birthday</label>
                        <div class="mt-2">
                            <input type="date" name="birthday" id="birthday" required class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 outline-gray-300 focus:outline-2 focus:outline-indigo-600">
                        </div>
                    </div>

                    <div>
                        <label for="city" class="block text-sm font-medium text-gray-900">City</label>
                        <div class="mt-2">
                            <input type="text" name="city" id="city" required class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 outline-gray-300 focus:outline-2 focus:outline-indigo-600">
                        </div>
                    </div>
                    <div>
                        <x-input-label for="password" :value="__('Password')" />
                        <x-text-input id="password" class="block w-full" type="password" name="password" required autocomplete="new-password" />
                    </div>

                    <div>
                        <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
                        <x-text-input id="password_confirmation" class="block w-full" type="password" name="password_confirmation" required autocomplete="new-password" />
                    </div>

                    <div>
                        <x-input-label for="file_input" :value="__('Upload Cover Picture')" />
                        <x-text-input type="file" name="cover_picture" id="file_input" accept="image/*" class="block w-full" />
                    </div>

                    <div>
                        <button type="submit" class="flex w-full justify-center rounded-md bg-indigo-600 px-3 py-1.5 text-sm font-semibold text-white shadow hover:bg-indigo-500">Sign Up</button>
                    </div>
                </form>

                <p class="mt-10 text-center text-sm text-gray-500">
                    Already have an account? <a href="#" class="font-semibold text-indigo-600 hover:text-indigo-500">Sign in</a>
                </p>
            </div>
        </div>
    </div>
</x-guest-layout>
