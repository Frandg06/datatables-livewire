<div class="flex gap-4">
    <a href="{{route('dashboard', ['id'=>$id])}}" class="bg-emerald-500 hover:bg-emerald-700 text-white font-bold py-2 px-4 rounded" wire:navigate>Ver</a>
    <a href='/dasboard?id={{$id}}' class='bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded' wire:navigate>Editar</a>
    <form action="">
        @csrf
        <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
            Eliminar
        </button>
    </form>

</div>