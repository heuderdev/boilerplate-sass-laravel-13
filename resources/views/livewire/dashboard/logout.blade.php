<div>
    <form wire:submit="logout" class="space-y-4">
        <button type="submit"
            class="w-2xs rounded-lg bg-[#4b36de] py-2 font-semibold text-white transition hover:bg-indigo-700 cursor-pointer">
            <span wire:loading.remove>Logout</span>
            <span wire:loading>Aguarde...</span>
        </button>
    </form>
</div>