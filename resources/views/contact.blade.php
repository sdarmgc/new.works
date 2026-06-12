<x-dynamic-component :component="Auth::check() ? 'app-layout' : 'guest-layout'">
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-4">

                <x-slot name="header">
                    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                        {{ __('Contact') }}
                    </h2>
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
                        <x-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus
                            value="{{ Auth::check() ? Auth::user()->name : '' }}"
                        />
                    </div>

                    <div class="mt-4">
                        <x-label for="email" value="{{ __('Email') }}" />
                        <x-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required
                            value="{{ Auth::check() ? Auth::user()->email : '' }}"
                        />
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
            </div>
        </div>
    </div>
</x-dynamic-component>
