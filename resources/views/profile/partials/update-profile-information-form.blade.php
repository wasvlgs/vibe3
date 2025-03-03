<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Profile Information') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __("Update your account's profile information, email address, and profile picture.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6" enctype="multipart/form-data">
        @csrf
        @method('patch')

        <!-- Name Field -->
        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <!-- Email Field -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />
        </div>

        <!-- City Field -->
        <div>
            <x-input-label for="city" :value="__('City')" />
            <x-text-input id="city" name="city" type="text" class="mt-1 block w-full" :value="old('city', $user->city)" required />
            <x-input-error class="mt-2" :messages="$errors->get('city')" />
        </div>

        <!-- Birthday Field -->
        <div>
            <x-input-label for="birthday" :value="__('Birthday')" />
            <x-text-input id="birthday" name="birthday" type="date" class="mt-1 block w-full" :value="old('birthday', $user->birthday->format('Y-m-d'))" required />
            <x-input-error class="mt-2" :messages="$errors->get('birthday')" />
        </div>

        <!-- Profile Picture Field - Two Columns -->
        <div class="grid grid-cols-2 gap-4">
            <!-- Current Profile Picture -->
            <div>
                <x-input-label for="current_profile_picture" :value="__('Current Profile Picture')" />
                @if ($user->cover_photo)

                    <img src="{{ asset($user->cover_photo) }}" alt="{{$user->cover_photo}}" class="w-28 h-24 object-contain rounded-xl">
                @else
                    <img src="{{ asset('images/default-avatar.png') }}" alt="Default Avatar" class="w-32 h-32 object-cover rounded-full">
                @endif
            </div>

            <!-- File Input for New Profile Picture -->
            <div>
                <x-input-label for="profile_picture" :value="__('New Profile Picture (Optional)')" />
                <x-text-input id="profile_picture" name="cover_picture" type="file" class="mt-1 block w-full" />
                <x-input-error class="mt-2" :messages="$errors->get('profile_picture')" />
            </div>
        </div>

        <!-- Bio Field -->
        <div>
            <x-input-label for="bio" :value="__('Bio')" />
            <x-text-input id="bio" name="bio" type="text" class="mt-1 block w-full" :value="old('bio', $user->bio)" />
            <x-input-error class="mt-2" :messages="$errors->get('bio')" />
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600"
                >{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>
