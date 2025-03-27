@if($user)
    <x-filament::dropdown.list.item>
        {{ $user->getName() }}
    </x-filament::dropdown.list.item>
@endif
