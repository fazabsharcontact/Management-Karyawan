<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center bg-white">
        <div class="bg-gray-100 shadow-lg rounded-none lg:rounded-[2rem] flex flex-col lg:flex-row w-full max-w-6xl h-auto lg:h-[700px] overflow-hidden">

            <!-- Left Panel (hanya muncul di desktop) -->
            <div 
                class="lg:w-[35%] bg-cover bg-center text-white p-10 hidden lg:flex flex-col justify-between"
                style="background-image: url('{{ asset('images/login-bg.jpg') }}')">
                
                <div class="font-bold text-4xl flex-1 flex items-center justify-center">
                    <p>Move Fast. <br>Break Nothing.</p>
                </div>
                <div class="mb-10">
                    <h2 class="text-3xl font-semibold mb-4">Worktify.</h2>
                </div>
            </div>

            <!-- Right Panel -->
            <div class="w-full lg:w-[65%] px-8 md:px-16 lg:px-24 pt-0 lg:pt-10 pb-10 flex flex-col justify-center">

                <!-- Mobile Header -->
                <div class="block lg:hidden text-center mb-8">
                    <div 
                        class="rounded-b-3xl p-10 text-white"
                        style="background-image: url('{{ asset('images/login-bg.jpg') }}')">
                        <p class="text-2xl font-bold">Move Fast.<br>Break Nothing.</p>
                        <h2 class="text-xl font-semibold mt-4">Worktify.</h2>
                    </div>
                </div>

                <!-- Welcome Text -->
                <h2 class="text-3xl lg:text-4xl font-bold mb-2 text-center lg:text-center">Welcome back</h2>
                <p class="text-md lg:text-lg mb-6 text-gray-700 text-center lg:text-center">Please enter your details</p>

                <!-- Session Status -->
                <x-auth-session-status class="mb-4" :status="session('status')" />

                <form method="POST" action="{{ route('login') }}" class="w-full max-w-sm mx-auto">
                    @csrf

                    <!-- Username -->
                    <div class="mb-4">
                        <x-input-label for="username" :value="__('Username')" class="text-gray-700 font-semibold" />
                        <x-text-input id="username" 
                                      class="mt-1 block w-full border-gray-300 rounded px-4 py-2 shadow-sm focus:outline-none focus:ring-2 focus:ring-sky-900" 
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
                                      class="mt-1 block w-full border-gray-300 rounded px-4 py-2 shadow-sm focus:outline-none focus:ring-2 focus:ring-sky-900" 
                                      type="password"
                                      name="password"
                                      required autocomplete="current-password" />
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <!-- Remember Me + Forgot -->
                    <div class="flex items-center justify-between mb-4">
                        <label for="remember_me" class="flex items-center">
                            <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-sky-900 shadow-sm focus:ring-cyan-400" name="remember">
                            <span class="ml-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
                        </label>
                        @if (Route::has('password.request'))
                            <a class="text-sm text-gray-700 hover:underline" href="{{ route('password.request') }}">
                                {{ __('Forgot your password?') }}
                            </a>
                        @endif
                    </div>

                    <!-- Submit -->
                    <div>
                        <x-primary-button class="w-full bg-black text-white px-4 py-3 rounded hover:bg-gray-800 transition mt-4 text-center justify-center">
                            {{ __('Log in') }}
                        </x-primary-button>
                        <p class="text-sm py-2 mb-6 text-center text-gray-700">Be Enjoy!!!</p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-guest-layout>