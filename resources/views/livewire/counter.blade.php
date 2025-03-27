<div>
    <h1 class="text-2xl font-bold mb-4">Livewire Counter</h1>
    <div class="flex items-center space-x-4">
        <button wire:click="decrement" class="px-4 py-2 border border-black text-black rounded hover:bg-gray-100">-</button>
        <span class="text-xl">{{ $count }}</span>
        <button wire:click="increment" class="px-4 py-2 border border-black text-black rounded hover:bg-gray-100">+</button>
    </div>
</div>
