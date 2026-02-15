<?php
$ctx = new \App\Support\RaceManagementContext(
    event: $event,
    raceClass: $raceClass ?? null,
    currentPage: 'index',
);
?>

<x-layouts::module
    title="Config"
    moduleTitle="RACE MANAGEMENT"
    :sidebarItems="$ctx->sidebarItems()"
    :seasons="$ctx->seasonsForDropdown()"
    :currentSeason="$ctx->currentSeasonArray()"
    currentItem="index"
    :showContextBar="true"
    :events="$ctx->eventsForDropdown()"
    :currentEvent="$ctx->currentEventArray()"
    :classes="$ctx->classesForDropdown()"
    :currentClass="$ctx->currentClassArray()"
>
    <livewire:race-management.setup-manager
        :eventId="$event->id"
        :raceClassId="$raceClass?->id ?? 0"
    />
</x-layouts::module>
