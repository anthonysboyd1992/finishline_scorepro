<?php

use Livewire\Attributes\On;
use Livewire\Component;

new class extends Component
{
    public int $eventId = 0;

    public string $operatorToken = '';

    // State driven by broadcast events
    public string $mode = 'idle'; // idle, dancing, stopped
    public string $driverName = '';
    public string $carNumber = '';
    public ?int $assignedPill = null;
    public array $drawnPills = [];
    public int $pillMin = 1;
    public int $pillMax = 999;

    /**
     * @return array<string, string>
     */
    public function getListeners(): array
    {
        return [
            "echo-private:event.{$this->eventId}.operator.{$this->operatorToken},PillDrawUpdated" => 'handlePillDrawUpdate',
        ];
    }

    /**
     * Handle incoming broadcast event.
     *
     * @param  array<string, mixed>  $event
     */
    public function handlePillDrawUpdate(array $event): void
    {
        $this->mode = $event['state'] ?? 'idle';
        $this->driverName = $event['driverName'] ?? '';
        $this->carNumber = $event['carNumber'] ?? '';
        $this->assignedPill = $event['assignedPill'] ?? null;
        $this->drawnPills = $event['drawnPills'] ?? [];
        $this->pillMin = $event['pillMin'] ?? 1;
        $this->pillMax = $event['pillMax'] ?? 999;

        $this->dispatch('pill-draw-update', [
            'mode' => $this->mode,
            'driverName' => $this->driverName,
            'carNumber' => $this->carNumber,
            'assignedPill' => $this->assignedPill,
            'drawnPills' => $this->drawnPills,
            'pillMin' => $this->pillMin,
            'pillMax' => $this->pillMax,
        ]);
    }
}; ?>

<div
    class="h-dvh flex flex-col"
    x-data="eventPillDraw"
    @pill-draw-update.window="handleUpdate($event.detail)"
>
    {{-- Idle / Waiting screen --}}
    <div x-show="mode === 'idle' && !driverName" class="flex-1 flex flex-col items-center justify-center text-center p-8">
        <div class="mb-8">
            <svg class="w-24 h-24 text-zinc-600 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2" />
            </svg>
        </div>
        <h1 class="text-4xl font-bold text-zinc-400 mb-4">Pill Draw Screen</h1>
        <p class="text-xl text-zinc-500">Waiting for activity...</p>
        <p class="text-sm text-zinc-600 mt-2">This screen will update automatically when a pill draw begins.</p>
    </div>

    {{-- Active pill draw display --}}
    <template x-if="driverName || mode !== 'idle'">
        <div class="flex-1 flex flex-col min-h-0">
            {{-- Top bar: competitor info --}}
            <div class="shrink-0 px-6 py-5 bg-zinc-800 border-b border-zinc-700 flex items-center justify-center gap-6">
                <div class="flex items-center justify-center w-16 h-16 bg-blue-600 text-white font-bold text-2xl rounded-lg" x-text="carNumber"></div>
                <div class="text-center">
                    <p class="text-white font-bold text-2xl" x-text="driverName"></p>
                    <p class="text-zinc-400 text-lg" x-text="mode === 'stopped' ? 'Pill Drawn!' : mode === 'dancing' ? 'Drawing...' : 'Ready'"></p>
                </div>
                <template x-if="mode === 'stopped' && assignedPill">
                    <div class="flex items-center justify-center w-20 h-20 bg-red-600 text-white font-bold text-4xl rounded-xl ring-4 ring-red-500 shadow-lg shadow-red-500/30" x-text="assignedPill"></div>
                </template>
            </div>

            {{-- Pill number grid --}}
            <div class="flex-1 overflow-y-auto p-6">
                <div class="grid gap-2 max-w-6xl mx-auto" :class="gridColsClass">
                    <template x-for="num in allPills" :key="num">
                        <div
                            class="relative flex items-center justify-center rounded-lg font-bold transition-all duration-100 select-none"
                            :class="[cellSizeClass, pillClass(num)]"
                            :id="'pill-cell-' + num"
                            x-text="num"
                        ></div>
                    </template>
                </div>
            </div>

            {{-- Bottom status bar --}}
            <div class="shrink-0 flex items-center justify-center px-6 py-4 bg-zinc-800 border-t border-zinc-700">
                <template x-if="mode === 'idle' && driverName">
                    <div class="text-zinc-300 font-bold text-xl">Press Enter to start draw</div>
                </template>
                <template x-if="mode === 'dancing'">
                    <div class="text-yellow-400 font-bold text-xl animate-pulse">Press Enter to end draw</div>
                </template>
                <template x-if="mode === 'stopped'">
                    <div class="text-green-400 font-bold text-xl">
                        Pill <span x-text="assignedPill"></span> assigned!
                    </div>
                </template>
                <template x-if="mode === 'idle' && !driverName">
                    <div class="text-zinc-500 text-lg">Waiting for next draw...</div>
                </template>
            </div>
        </div>
    </template>
</div>
