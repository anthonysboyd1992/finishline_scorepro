<?php

use Livewire\Attributes\On;
use Livewire\Component;

new class extends Component
{
    public int $eventId = 0;

    public string $operatorToken = '';

    // State driven by broadcast events
    public string $mode = 'idle'; // idle, dancing, stopped
    public string $className = '';
    public string $driverName = '';
    public string $carNumber = '';
    public ?int $assignedPill = null;
    public array $drawnPills = [];
    public int $pillMin = 1;
    public int $pillMax = 500;

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
        $this->className = $event['className'] ?? '';
        $this->driverName = $event['driverName'] ?? '';
        $this->carNumber = $event['carNumber'] ?? '';
        $this->assignedPill = $event['assignedPill'] ?? null;
        $this->drawnPills = $event['drawnPills'] ?? [];
        $this->pillMin = $event['pillMin'] ?? 1;
        $this->pillMax = $event['pillMax'] ?? 500;

        $this->dispatch('pill-draw-update', [
            'mode' => $this->mode,
            'className' => $this->className,
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
            {{-- Top bar: competitor info + draw status --}}
            <div class="shrink-0 px-6 py-3 bg-zinc-800 border-b border-zinc-700 flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <div class="flex items-center justify-center w-14 h-14 bg-blue-600 text-white font-bold text-2xl rounded-lg" x-text="carNumber"></div>
                    <div>
                        <p class="text-white font-bold text-xl" x-text="driverName"></p>
                        <p class="text-zinc-400 text-sm">
                            <span x-show="className" x-text="className" class="text-zinc-300 font-medium"></span>
                            <span x-show="className"> &mdash; </span>
                            <span x-text="mode === 'stopped' ? 'Pill Drawn!' : mode === 'dancing' ? 'Drawing...' : 'Ready'"></span>
                        </p>
                    </div>
                </div>
                <div class="flex items-center gap-4">
                    <template x-if="mode === 'idle' && driverName">
                        <div class="text-zinc-300 font-semibold text-lg">Press Enter to start draw</div>
                    </template>
                    <template x-if="mode === 'dancing'">
                        <div class="text-yellow-400 font-semibold text-lg animate-pulse">Press Enter to end draw</div>
                    </template>
                    <template x-if="mode === 'stopped' && assignedPill">
                        <div class="flex items-center justify-center w-14 h-14 bg-red-600 text-white font-bold text-3xl rounded-xl ring-4 ring-red-500 shadow-lg shadow-red-500/30" x-text="assignedPill"></div>
                    </template>
                </div>
            </div>

            {{-- Pill number grid --}}
            <div class="flex-1 min-h-0 overflow-hidden p-2">
                <div class="grid mx-auto" :class="[gridColsClass, gridGapClass]">
                    <template x-for="num in allPills" :key="num">
                        <div
                            class="relative flex items-center justify-center rounded font-bold transition-all duration-100 select-none"
                            :class="[cellSizeClass, pillClass(num)]"
                            :id="'pill-cell-' + num"
                            x-text="num"
                        ></div>
                    </template>
                </div>
            </div>
        </div>
    </template>
</div>
