<x-filament-widgets::widget>
    <x-filament::section>
        <div class="text-sm text-gray-600 dark:text-gray-300 ml-4">
            Workspace: <strong>{{ auth()->user()->workspace->name ?? 'No Workspace' }}</strong>
        </div>        
    </x-filament::section>
</x-filament-widgets::widget>
