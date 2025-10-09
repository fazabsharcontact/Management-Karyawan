<x-guest-layout>
    <div class="min-h-screen w-full flex flex-col lg:flex-row">

        <!-- Left Panel (Desktop only) -->
        <div 
            class="hidden lg:flex lg:w-[35%] bg-cover bg-center text-white p-10 flex-col justify-between"
            style="background-image: url('{{ asset('images/login.jpeg') }}')">
            
            <div class="font-bold text-4xl flex-1 flex items-center justify-center leading-snug">
                <p>Move Fast. <br>Break Nothing.</p>
            </div>
            <div class="mt-auto flex justify-end">
                <h2 class="text-2xl font-semibold">Worktify.</h2>
            </div>
        </div>

        <!-- Right Panel -->
        <div class="lg:w-[65%] flex-1 flex flex-col justify-center px-6 sm:px-12 lg:px-20 py-10 bg-gray-100">

            <!-- Mobile Header -->
            <div class="block lg:hidden text-center mb-8">
                <div 
                    class="p-10 text-white bg-cover bg-center"
                    style="background-image: url('{{ asset('images/login.jpeg') }}')">
                    <p class="text-2xl font-bold leading-snug">Move Fast.<br>Break Nothing.</p>
                    <h2 class="text-lg font-semibold mt-3">Worktify.</h2>
                </div>
            </div>

            <!-- Welcome Text -->
            <div class="text-center mb-6">
                <h2 class="text-3xl lg:text-4xl font-bold mb-2">Welcome back</h2>
                <p class="text-sm lg:text-base text-gray-600">Please enter your details</p>
            </div>

            <!-- Session Status -->
            <x-auth-session-status class="mb-4" :status="session('status')" />

            <form method="POST" action="{{ route('login') }}" class="w-full max-w-sm mx-auto">
                @csrf

                <!-- Username -->
                <div class="mb-4">
                    <x-input-label for="username" :value="__('Username')" class="text-gray-700 font-semibold" />
                    <x-text-input id="username" 
                                  class="mt-1 block w-full border-gray-300 rounded-lg px-4 py-2 shadow-sm focus:outline-none focus:ring-2 focus:ring-gray-500" 
                                  type="text" 
                                  name="username" 
                                  :value="old('username')" 
                                  required autofocus autocomplete="username" />
                    <x-input-error :messages="$errors->get('username')" class="mt-2" />
                </div>

                <!-- Password -->
                <div class="mb-4">
                    <x-input-label for="password" :value="__('Password')" class="text-gray-700 font-semibold" />
                    <x-text-input id="password" 
                                  class="mt-1 block w-full border-gray-300 rounded-lg px-4 py-2 shadow-sm focus:outline-none focus:ring-2 focus:ring-gray-500" 
                                  type="password"
                                  name="password"
                                  required autocomplete="current-password" />
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <!-- Remember Me + Forgot -->
                <div class="flex items-center justify-between mb-4 text-sm">
                    <label for="remember_me" class="flex items-center">
                        <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-gray-900 shadow-sm focus:ring-gray-400" name="remember">
                        <span class="ml-2 text-gray-600">{{ __('Remember me') }}</span>
                    </label>
                    @if (Route::has('password.request'))
                        <a class="text-gray-700 hover:underline" href="{{ route('password.request') }}">
                            {{ __('Forgot your password?') }}
                        </a>
                    @endif
                </div>

                <!-- Submit -->
                <div>
                    <x-primary-button class="w-full bg-black text-white px-4 py-3 rounded-lg hover:bg-gray-800 transition text-center justify-center">
                        {{ __('Log in') }}
                    </x-primary-button>
                    <p class="text-sm py-3 text-center text-gray-600">Be Enjoy!!!</p>
                </div>
            </form>
        </div>
    </div>
</x-guest-layout>