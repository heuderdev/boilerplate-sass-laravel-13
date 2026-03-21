<div class="flex min-h-screen items-center justify-center bg-gray-50">
    <div class="w-full max-w-md rounded-2xl bg-white p-8 shadow-md">
        <h1 class="mb-6 text-2xl font-bold text-gray-800">Criar conta</h1>

        <form wire:submit="register" class="space-y-4">

            {{-- Nome --}}
            <div>
                <label class="block text-sm font-medium text-gray-700">Nome</label>
                <input type="text" wire:model="name" autocomplete="name" class="mt-1 w-full rounded-lg border border-gray-300 px-4 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500
                           @error('name') border-red-400 @enderror" />
                @error('name')
                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            {{-- E-mail --}}
            <div>
                <label class="block text-sm font-medium text-gray-700">E-mail</label>
                <input type="email" wire:model="email" autocomplete="email" class="mt-1 w-full rounded-lg border border-gray-300 px-4 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500
                           @error('email') border-red-400 @enderror" />
                @error('email')
                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            {{-- Senha --}}
            <div>
                <label class="block text-sm font-medium text-gray-700">Senha</label>
                <input type="password" wire:model="password" autocomplete="new-password" class="mt-1 w-full rounded-lg border border-gray-300 px-4 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500
                           @error('password') border-red-400 @enderror" />
                @error('password')
                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            {{-- Confirmar senha --}}
            <div>
                <label class="block text-sm font-medium text-gray-700">Confirmar senha</label>
                <input type="password" wire:model="password_confirmation" autocomplete="new-password" class="mt-1 w-full rounded-lg border border-gray-300 px-4 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500
                           @error('password_confirmation') border-red-400 @enderror" />
                @error('password_confirmation')
                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit"
                class="w-full rounded-lg bg-indigo-600 py-2 font-semibold text-white transition hover:bg-indigo-700 disabled:opacity-50"
                wire:loading.attr="disabled">
                <span wire:loading.remove>Criar conta</span>
                <span wire:loading>Criando...</span>
            </button>

        </form>

        <p class="mt-4 text-center text-sm text-gray-500">
            Já tem conta?
            <a href="{{ route('login') }}" wire:navigate class="text-indigo-600 hover:underline">Entrar</a>
        </p>
    </div>
</div>