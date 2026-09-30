<x-guest-layout title="Login - SI Akademik">
    <div class="flex items-center justify-center min-h-[80vh]">
        <div class="w-full max-w-md">
            <div class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-xl overflow-hidden border border-white/50">
                <div class="p-8">
                    <div class="text-center mb-8">
                        <div class="w-16 h-16 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-2xl shadow-inner flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                        </div>
                        <h2 class="text-2xl font-bold text-slate-800">Sistem Akademik</h2>
                        <p class="text-sm text-slate-500 mt-1">Silakan masuk ke akun Anda</p>
                    </div>

                    @if ($errors->any())
                        <div class="mb-6 bg-red-50 text-red-600 p-4 rounded-xl text-sm border border-red-100">
                            <ul class="list-disc list-inside space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('login.attempt') }}" method="POST" class="space-y-5">
                        @csrf
                        <div>
                            <label for="email" class="block text-sm font-medium text-slate-700 mb-1">Email Address</label>
                            <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus
                                class="w-full rounded-xl border-slate-200 bg-white/50 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                        </div>

                        <div>
                            <label for="password" class="block text-sm font-medium text-slate-700 mb-1">Password</label>
                            <input type="password" id="password" name="password" required
                                class="w-full rounded-xl border-slate-200 bg-white/50 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                        </div>

                        <div class="flex items-center">
                            <input type="checkbox" id="remember" name="remember" {{ old('remember') ? 'checked' : '' }}
                                class="rounded border-slate-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                            <label for="remember" class="ml-2 block text-sm text-slate-600">Ingat saya</label>
                        </div>

                        <button type="submit" class="w-full py-3 px-4 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white rounded-xl font-medium shadow-lg shadow-indigo-200 transition-all transform hover:-translate-y-0.5">
                            Masuk Sekarang
                        </button>
                    </form>

                    <div class="mt-8 pt-6 border-t border-slate-100">
                        <p class="text-xs font-semibold text-slate-500 mb-3 text-center uppercase tracking-wider">Kredensial Demo</p>
                        <div class="space-y-2">
                            <div class="flex justify-between items-center text-sm p-2 bg-slate-50 rounded-lg border border-slate-100">
                                <span class="text-slate-600 font-medium">admin@kampus.ac.id</span>
                                <span class="text-slate-400 font-mono text-xs">password</span>
                            </div>
                            <div class="flex justify-between items-center text-sm p-2 bg-slate-50 rounded-lg border border-slate-100">
                                <span class="text-slate-600 font-medium">dosen@kampus.ac.id</span>
                                <span class="text-slate-400 font-mono text-xs">password</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
