<x-filament::page>
    <!-- Header Section with Pipeline Selector -->
    <div class="mb-6">
        <div class="flex items-center justify-between gap-4">
            <div class="flex-1 max-w-md">
                <x-filament::input.wrapper>
                    <x-filament::input.select wire:model.live="pipelineId">
                        @foreach(\App\Models\Pipeline::all() as $pipeline)
                            <option value="{{ $pipeline->id }}">
                                {{ $pipeline->name }}
                            </option>
                        @endforeach
                    </x-filament::input.select>
                </x-filament::input.wrapper>
            </div>
            
            <div class="flex items-center gap-3">
                <div class="text-sm text-gray-600 dark:text-gray-400">
                    <span class="font-semibold">{{ $this->stages->sum(fn($stage) => count($this->dealsByStage[$stage->id] ?? [])) }}</span> 
                    Total Deals
                </div>
                <div class="text-sm text-gray-600 dark:text-gray-400">
                    <span class="font-semibold">₹{{ number_format($this->stages->sum(fn($stage) => collect($this->dealsByStage[$stage->id] ?? [])->sum('value'))) }}</span> 
                    Total Value
                </div>
            </div>
        </div>
    </div>

    <!-- Pipeline Stages -->
    <div class="overflow-x-auto pb-2 -mx-4 px-4">
        <div
            class="min-w-max grid gap-2"
            style="grid-template-columns: repeat({{ $this->stages->count() }}, minmax(14rem, 1fr));"
        >
            @foreach($this->stages as $stage)
                <div
                    class="flex flex-col"
                    x-data="{ 
                        isDraggingOver: false,
                        stageValue: {{ collect($this->dealsByStage[$stage->id] ?? [])->sum('value') }}
                    }"
                    x-on:dragover.prevent="isDraggingOver = true"
                    x-on:dragleave="isDraggingOver = false"
                    x-on:drop="
                        isDraggingOver = false;
                        $wire.moveDeal(
                            event.dataTransfer.getData('deal'),
                            {{ $stage->id }}
                        )
                    "
                >
                    <!-- Stage Header -->
                    <div 
                        class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border-2 border-gray-200 dark:border-gray-700 p-4 mb-3 transition-all duration-200"
                        :class="isDraggingOver ? 'border-primary-500 ring-2 ring-primary-500/20' : ''"
                    >
                        <div class="flex items-center justify-between mb-2">
                            <h3 class="font-bold text-lg text-gray-900 dark:text-white">
                                {{ $stage->name }}
                            </h3>
                            <span class="inline-flex items-center justify-center w-7 h-7 text-xs font-bold text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 rounded-full">
                                {{ count($this->dealsByStage[$stage->id] ?? []) }}
                            </span>
                        </div>
                        
                        @if(count($this->dealsByStage[$stage->id] ?? []) > 0)
                            <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                                <svg class="w-4 h-4 mr-1 text-green-600 dark:text-green-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z"/>
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z" clip-rule="evenodd"/>
                                </svg>
                                <span class="font-semibold">₹{{ number_format(collect($this->dealsByStage[$stage->id] ?? [])->sum('value')) }}</span>
                            </div>
                        @endif
                    </div>

                    <!-- Deals Container -->
                    <div 
                        class="flex-1 space-y-3 min-h-50 rounded-lg p-2 transition-all duration-200"
                        :class="isDraggingOver ? 'bg-primary-50 dark:bg-primary-900/10' : 'bg-transparent'"
                    >
                        @forelse($this->dealsByStage[$stage->id] ?? [] as $deal)
                            <div
                                class="group bg-white dark:bg-gray-800 rounded-lg shadow-sm hover:shadow-md border border-gray-200 dark:border-gray-700 p-4 cursor-move transition-all duration-200 hover:border-primary-400 dark:hover:border-primary-500"
                                draggable="true"
                                x-data="{ isDragging: false }"
                                x-on:dragstart="
                                    isDragging = true;
                                    event.dataTransfer.setData('deal', {{ $deal->id }});
                                    event.dataTransfer.effectAllowed = 'move';
                                "
                                x-on:dragend="isDragging = false"
                                :class="isDragging ? 'opacity-50 scale-95' : ''"
                            >
                                <!-- Deal Header -->
                                @php
                                    $displayName = 
                                    $deal->client?->name
                                        ?? $deal->company?->name  
                                        ?? $deal->lead?->name
                                        ?? 'Unknown';
                                        
                                    $companyName =
                                        $deal->company?->name 
                                        ?? $deal->client?->company
                                        ?? null;

                                @endphp
                                <div class="flex items-start justify-between mb-3">
                                    <div class="flex-1 min-w-0">
                                        <h4 class="font-semibold text-gray-900 dark:text-white text-sm leading-tight mb-1 group-hover:text-primary-600 dark:group-hover:text-primary-400 transition-colors">
                                            {{ $displayName }}
                                        </h4>
                                        @if($companyName)
                                            <p class="text-xs text-gray-500 dark:text-gray-400 flex items-center">
                                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a1 1 0 110 2h-3a1 1 0 01-1-1v-2a1 1 0 00-1-1H9a1 1 0 00-1 1v2a1 1 0 01-1 1H4a1 1 0 110-2V4zm3 1h2v2H7V5zm2 4H7v2h2V9zm2-4h2v2h-2V5zm2 4h-2v2h2V9z" clip-rule="evenodd"/>
                                                </svg>
                                                {{ $companyName }}
                                            </p>
                                        @endif
                                    </div>
                                    
                                    @if($deal->lead?->probability)
                                        @php
                                            $probabilityColors = [
                                                'early' => 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300',
                                                'warm' => 'bg-orange-100 text-orange-700 dark:bg-orange-900/30 dark:text-orange-400',
                                                'hot' => 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400',
                                            ];
                                        @endphp
                                        <span class="inline-flex items-center px-2 py-1 text-xs font-medium rounded-full {{ $probabilityColors[$deal->lead->probability] ?? 'bg-gray-100 text-gray-700' }}">
                                            {{ ucfirst($deal->lead->probability) }}
                                        </span>
                                    @endif
                                </div>

                                <!-- Deal Value -->
                                <div class="flex items-center justify-between pt-3 border-t border-gray-100 dark:border-gray-700">
                                    <div class="flex items-center text-sm">
                                        <svg class="w-4 h-4 mr-1 text-green-600 dark:text-green-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z"/>
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z" clip-rule="evenodd"/>
                                        </svg>
                                        <span class="font-bold text-gray-900 dark:text-white">
                                            ₹{{ number_format($deal->value ?? 0) }}
                                        </span>
                                    </div>

                                    @if($deal->lead?->urgency)
                                        @php
                                            $urgencyColors = [
                                                'low' => 'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400',
                                                'medium' => 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400',
                                                'high' => 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400',
                                            ];
                                        @endphp
                                        <span class="inline-flex items-center px-2 py-1 text-xs font-medium rounded {{ $urgencyColors[$deal->lead->urgency] ?? 'bg-gray-100 text-gray-700' }}">
                                            {{ ucfirst($deal->lead?->urgency) }}
                                        </span>
                                    @endif
                                </div>

                                <!-- Additional Info -->
                                @if($deal->lead?->category || $deal->lead?->timeline)
                                    <div class="flex flex-wrap gap-1 mt-3">
                                        @if($deal->lead?->category)
                                            <span class="inline-flex items-center px-2 py-1 text-xs rounded-full bg-blue-50 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400">
                                                {{ $deal->lead->category }}
                                            </span>
                                        @endif
                                        @if($deal->lead->timeline)
                                            <span class="inline-flex items-center px-2 py-1 text-xs rounded-full bg-purple-50 text-purple-700 dark:bg-purple-900/30 dark:text-purple-400">
                                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                                </svg>
                                                {{ $deal->lead->timeline }}
                                            </span>
                                        @endif
                                    </div>
                                @endif

                                <!-- Drag Handle Indicator -->
                                <div class="absolute top-2 right-2 opacity-0 group-hover:opacity-50 transition-opacity">
                                    <svg class="w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"/>
                                    </svg>
                                </div>
                            </div>
                        @empty
                            <div class="flex items-center justify-center h-32 text-gray-400 dark:text-gray-600">
                                <div class="text-center">
                                    <svg class="w-12 h-12 mx-auto mb-2 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                                    </svg>
                                    <p class="text-sm">No deals</p>
                                </div>
                            </div>
                        @endforelse
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <style>
        /* Custom scrollbar styling */
        .overflow-x-auto::-webkit-scrollbar {
            height: 8px;
        }
        
        .overflow-x-auto::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 4px;
        }
        
        .overflow-x-auto::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }
        
        .overflow-x-auto::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
        
        .dark .overflow-x-auto::-webkit-scrollbar-track {
            background: #374151;
        }
        
        .dark .overflow-x-auto::-webkit-scrollbar-thumb {
            background: #4b5563;
        }
        
        .dark .overflow-x-auto::-webkit-scrollbar-thumb:hover {
            background: #6b7280;
        }
    </style>
</x-filament::page>