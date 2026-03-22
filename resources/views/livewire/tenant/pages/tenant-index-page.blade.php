<div class="flex">

    <form wire:submit='onSubmit' class="w-75 bg-white p-4 rounded">
        <div class="flex flex-col">
            <label for="Nome" class="text-gray-500">Nome da sua Empresa:</label>
            <input wire:model='name' placeholder="Qual é o nome da sua empresa" type="text" name="name"
                class="bg-cyan-100 w-full h-8 mt-2 rounded placeholder:p-0.5 placeholder:font-light placeholder:text-[12px]">
        </div>
        <div>
            <button class="mt-2 bg-purple-800 text-white px-2 py-1 rounded cursor-pointer">Cadastrar</button>
        </div>
        <div></div>
    </form>

    <div class="flex-1"></div>

</div>