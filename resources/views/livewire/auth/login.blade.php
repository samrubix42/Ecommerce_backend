<div class="min-h-screen flex items-center justify-center bg-slate-100 px-4">

    <div class="w-full max-w-md">

        <!-- Card -->
        <div class="bg-white rounded-2xl shadow-xl border border-slate-200 p-8">

            <!-- Logo / Header -->
            <div class="text-center mb-8">
                <div class="flex justify-center">
                    <div class="h-12 w-12 rounded-xl bg-blue-600 flex items-center justify-center shadow-sm">
                        <i class="ri-shield-user-line text-white text-xl"></i>
                    </div>
                </div>

                <h2 class="mt-5 text-2xl font-semibold text-slate-900">
                    Admin Login
                </h2>

                <p class="mt-1 text-sm text-slate-500">
                    Access your admin dashboard securely
                </p>
            </div>

            <!-- Form -->
            <form wire:submit.prevent="login" class="space-y-5">

                <!-- Email -->
                <div>
                    <label class="block text-sm font-medium text-slate-700">
                        Email address
                    </label>

                    <div class="mt-1 relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                            <i class="ri-mail-line"></i>
                        </span>

                        <input
                            type="email"
                            wire:model.defer="email"
                            placeholder="admin@example.com"
                            class="w-full rounded-lg border border-slate-300 pl-10 pr-4 py-2.5 text-sm
                                   focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500 outline-none transition
                                   @error('email') border-red-500 @enderror">
                    </div>

                    @error('email')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password -->
                <div x-data="{ show: false }">
                    <label class="block text-sm font-medium text-slate-700">
                        Password
                    </label>

                    <div class="mt-1 relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                            <i class="ri-lock-line"></i>
                        </span>

                        <input
                            :type="show ? 'text' : 'password'"
                            wire:model.defer="password"
                            placeholder="Enter your password"
                            class="w-full rounded-lg border border-slate-300 pl-10 pr-10 py-2.5 text-sm
                                   focus:ring-2 focus:ring-blue-500/30 focus:border-blue-500 outline-none transition
                                   @error('password') border-red-500 @enderror">

                        <!-- Toggle -->
                        <button type="button"
                                @click="show = !show"
                                class="absolute inset-y-0 right-0 flex items-center pr-3 text-slate-400 hover:text-blue-600 transition">
                            <i :class="show ? 'ri-eye-off-line' : 'ri-eye-line'"></i>
                        </button>
                    </div>

                    @error('password')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Remember + Forgot -->
                <div class="flex items-center justify-between text-sm">
                    <label class="flex items-center gap-2 text-slate-600">
                        <input type="checkbox"
                               wire:model="remember"
                               class="rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                        Remember me
                    </label>

                    <a href="#"
                       class="text-blue-600 hover:text-blue-500 font-medium">
                        Forgot password?
                    </a>
                </div>

                <!-- Submit -->
                <div>
                    <button type="submit"
                            class="w-full rounded-lg bg-blue-600 py-2.5 text-sm font-medium text-white
                                   hover:bg-blue-700 transition shadow-sm
                                   focus:ring-2 focus:ring-blue-500/40">
                        Sign in
                    </button>
                </div>

            </form>

        </div>

        <!-- Footer -->
        <p class="mt-6 text-center text-xs text-slate-500">
            © {{ date('Y') }} Admin Panel. All rights reserved.
        </p>

    </div>

</div>