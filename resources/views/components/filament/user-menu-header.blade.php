<div>
    <!-- It is quality rather than quantity that matters. - Lucius Annaeus Seneca -->
</div>

@if(auth()->check())
    <div class="px-4 py-3 text-sm">
        <p class="font-medium text-foreground">{{ auth()->user()->getName() }}</p>
        <p class="text-xs text-gray-500">{{ auth()->user()->email }}</p>
    </div>
@endif
