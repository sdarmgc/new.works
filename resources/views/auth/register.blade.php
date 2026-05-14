@php
    $languages = \App\Models\Language::orderBy('name')->get();
    $countries = \App\Models\Country::orderBy('name')->get();
    $roles = [
        'Translator' => 'Translator',
        'Proof-Reader' => 'Proof-Reader',
        'Editor' => 'Editor',
        'Designer' => 'Designer',
        'Printer' => 'Printer',
        // 'Other' => 'Other',
    ];
@endphp

<x-guest-layout>
    <x-authentication-card>
        <x-slot name="logo">
            <x-authentication-card-logo />
        </x-slot>

        <x-validation-errors class="mb-4" />

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div>
                <x-label for="name" value="{{ __('Nickname') }}" />
                <x-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            </div>

            <div class="mt-4">
                <x-label for="email" value="{{ __('Email') }}" />
                <x-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            </div>

            <div class="mt-4">
                <x-label for="password" value="{{ __('Password') }}" />
                <x-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="new-password" />
            </div>

            <div class="mt-4">
                <x-label for="password_confirmation" value="{{ __('Confirm Password') }}" />
                <x-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required autocomplete="new-password" />
            </div>

            <div class="mt-4">
                <x-label for="gender" value="{{ __('Title') }}" />
                <select id="gender" name="gender" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="1" {{ old('gender') == '1' ? 'selected' : '' }}>
                        Br.
                    </option>
                    <option value="2" {{ old('gender') == '2' ? 'selected' : '' }}>
                        Sis.
                    </option>
                    <option value="0" {{ old('gender') == '0' ? 'selected' : '' }}>
                        Other
                    </option>
                </select>
                <x-input-error for="gender" class="mt-2" />
            </div>

            <div class="mt-4">
                <x-label for="first_name" value="{{ __('First Name') }}" />
                <x-input id="first_name" class="block mt-1 w-full" required 
                        type="text"
                        name="first_name"
                        :value="old('first_name')" />
            </div>

            <div class="mt-4">
                <x-label for="last_name" value="{{ __('Last Name') }}" />
                <x-input id="last_name" class="block mt-1 w-full" required 
                        type="text"
                        name="last_name"
                        :value="old('last_name')" />
            </div>

            <div class="mt-4">
                <x-label for="phone" value="{{ __('Phone Number (Please include country code!)') }}" />
                <x-input id="phone" class="block mt-1 w-full"
                        type="text"
                        name="phone"
                        :value="old('phone')" />
            </div>
            
            <div class="mt-4">
                <x-label for="languages" value="{{ __('Translation Languages') }}" />
                <div class="text-xs">Select all languages with Control (Ctrl, Command on a Mac) key pressed.</div>
                <select id="languages"
                        name="languages[]"
                        multiple
                        class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    @foreach($languages as $language)
                        <option value="{{ $language->id }}"
                            {{ collect(old('languages'))->contains($language->id) ? 'selected' : '' }}>
                            {{ $language->name }}
                        </option>
                    @endforeach
                </select>
                <div id="selected-lang" class="" style="font-size:0.75; margin-bottom: .5em;">
                    {{--  Selected Language(s):  --}}
                </div>
                <x-input-error for="languages" class="mt-2" />
            </div>

            @push("after-scripts")
                <script>
                    $(document).ready(function(){                                        
                        function setSelectedCountry() {
                            var str = "Selected Language: ";
                            $( "#languages option:selected" ).each(function() {
                                str += $( this ).text() + " ";
                            });
                            $( "#selected-lang" ).text( str );
                        }

                        $('#languages').change(setSelectedCountry);

                        // when the previous submission faild.
                        if ($('#languages').val()) {
                            setSelectedCountry();
                        }
                    });
                </script>
            @endpush
            
            <div class="mt-4">
                <x-label for="country" value="{{ __('Country') }}" />
                <div class="text-xs">The country you are working for.</div>
                <select id="country"
                        name="country"
                        class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    @foreach($countries as $country)
                        <option value="{{ $country->id }}"
                            {{ collect(old('countries'))->contains($country->id) ? 'selected' : '' }}>
                            {{ $country->name }}
                        </option>
                    @endforeach
                </select>
                <x-input-error for="countries" class="mt-2" />
            </div>

            <div class="mt-4">
                <x-label value="{{ __('Responsibility ') }}" />
                <div class="text-xs">Select all responsiblity(ies) you are involved with in the publishing work.</div>
                <div class="mt-2 space-y-2">
                    @foreach($roles as $role)
                        <label class="flex items-center">
                            <input type="checkbox"
                                name="roles[]"
                                value="{{ $role }}"
                                class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                                {{ collect(old('roles'))->contains($role) ? 'checked' : '' }}
                            >
                            <span class="ml-2">
                                {{ ucfirst($role) }}
                            </span>
                        </label>
                    @endforeach

                    <label class="flex items-center" for="roles_other">
                        <input type="checkbox" name="roles[]" value="Other" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                        <span class="ml-2">
                            Other
                        </span>
                        <input class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm inline-block ml-1" id="roles_other" type="text" name="roles_other" placeholder="Please Specify">
                    </label>
                </div>
                <x-input-error for="roles" class="mt-2" />
            </div>
            
            <div class="mt-10">
                {!! NoCaptcha::display() !!}
                <x-input-error for="g-recaptcha-response" class="mt-2" />
                @if ($errors->has('g-recaptcha-response'))
                    <span class="help-block">
                        <strong>{{ $errors->first('g-recaptcha-response') }}</strong>
                    </span>
                @endif
            </div>

            @if (Laravel\Jetstream\Jetstream::hasTermsAndPrivacyPolicyFeature())
                <div class="mt-4">
                    <x-label for="terms">
                        <div class="flex items-center">
                            <x-checkbox name="terms" id="terms" required />

                            <div class="ms-2">
                                {!! __('I agree to the :terms_of_service and :privacy_policy', [
                                        'terms_of_service' => '<a target="_blank" href="'.route('terms.show').'" class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">'.__('Terms of Service').'</a>',
                                        'privacy_policy' => '<a target="_blank" href="'.route('policy.show').'" class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">'.__('Privacy Policy').'</a>',
                                ]) !!}
                            </div>
                        </div>
                    </x-label>
                </div>
            @endif

            <div class="flex items-center justify-end mt-4">
                <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800" href="{{ route('login') }}">
                    {{ __('Already registered?') }}
                </a>

                <x-button class="ms-4">
                    {{ __('Register') }}
                </x-button>
            </div>
        </form>
    </x-authentication-card>
</x-guest-layout>
