<div class="flex min-h-screen items-center justify-center bg-gray-50">
    <div class="w-full max-w-md rounded-2xl bg-white p-8 shadow-md">
        <h1 class="mb-6 text-2xl font-bold text-gray-800">Entrar</h1>

        <form wire:submit="login" class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">E-mail</label>
                <input type="email" wire:model="email"
                    class="mt-1 w-full rounded-lg border border-gray-300 px-4 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500" />
                @error('email') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Senha</label>
                <input type="password" wire:model="password"
                    class="mt-1 w-full rounded-lg border border-gray-300 px-4 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500" />
                @error('password') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
            </div>

            <button type="submit"
                class="w-full rounded-lg bg-[#4b36de] py-2 font-semibold text-white transition hover:bg-indigo-700 cursor-pointer">
                <span wire:loading.remove>Entrar</span>
                <span wire:loading>Aguarde...</span>
            </button>
        </form>

        <p class="mt-4 text-center text-sm text-gray-500">
            Não tem conta?
            <a href="{{ route('register') }}" wire:navigate class="text-[#4b36de] hover:underline">Cadastre-se</a>
        </p>
    </div>
</div>