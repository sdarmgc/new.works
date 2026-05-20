<x-dynamic-component :component="Auth::check() ? 'app-layout' : 'guest-layout'">
    <x-authentication-card>
        <x-slot name="logo">
            <x-authentication-card-logo />
        </x-slot>

        <x-validation-errors class="mb-4" />

        @session('status')
            <div class="mb-4 font-medium text-sm text-green-600">
                {{ $value }}
            </div>
        @endsession

        <form method="POST" action="{{ route('contact.send') }}">
            @csrf

            <div>
                <x-label for="name" value="{{ __('Name') }}" />
                <x-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus />
            </div>

            <div class="mt-4">
                <x-label for="email" value="{{ __('Email') }}" />
                <x-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required />
            </div>

            <div class="mt-4">
                <x-label for="subject" value="{{ __('Subject') }}" />
                <x-input id="subject" class="block mt-1 w-full" type="text" name="subject" :value="old('subject')" required />
            </div>

            <div class="mt-4">
                <x-label for="message" value="{{ __('Message') }}" />
                <x-textarea id="message" class="block mt-1 w-full" name="message" :value="old('message')" required placeholder="Enter your message here..." />
            </div>

             <div class="mt-4">
                {!! NoCaptcha::display() !!}
            </div>

            <div class="flex items-center justify-end mt-4">
                <x-button class="ms-4">
                    {{ __('Send Message') }}
                </x-button>
            </div>
        </form>
    </x-authentication-card>
</x-dynamic-component>
