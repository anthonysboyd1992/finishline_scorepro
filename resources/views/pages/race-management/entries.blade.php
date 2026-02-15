<?php
$ctx = new \App\Support\RaceManagementContext(
    event: $event,
    raceClass: $raceClass,
    currentPage: 'entries',
);

$entryCount = \App\Models\Entry::query()
    ->where('event_id', $event->id)
    ->where('race_class_id', $raceClass->id)
    ->count();
?>

<x-layouts::module
    title="Check Ins"
    moduleTitle="RACE MANAGEMENT"
    :sidebarItems="$ctx->sidebarItems()"
    :seasons="$ctx->seasonsForDropdown()"
    :currentSeason="$ctx->currentSeasonArray()"
    currentItem="entries"
    :showContextBar="true"
    :events="$ctx->eventsForDropdown()"
    :currentEvent="$ctx->currentEventArray()"
    :classes="$ctx->classesForDropdown()"
    :currentClass="$ctx->currentClassArray()"
    :entryCount="$entryCount"
>
    <livewire:race-management.entry-manager :eventId="$event->id" :raceClassId="$raceClass->id" />
</x-layouts::module>
