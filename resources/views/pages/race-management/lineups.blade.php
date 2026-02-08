<?php
$ctx = new \App\Support\RaceManagementContext(
    event: $event,
    raceClass: $raceClass,
    raceType: $raceType,
    currentPage: 'lineups',
);
?>

<x-layouts::module
    title="Lineups"
    moduleTitle="RACE MANAGEMENT"
    :sidebarItems="$ctx->sidebarItems()"
    :seasons="$ctx->seasonsForDropdown()"
    :currentSeason="$ctx->currentSeasonArray()"
    currentItem="lineups"
    :showContextBar="true"
    :events="$ctx->eventsForDropdown()"
    :currentEvent="$ctx->currentEventArray()"
    :classes="$ctx->classesForDropdown()"
    :currentClass="$ctx->currentClassArray()"
    :raceTypes="$ctx->raceTypes()"
    :currentRaceType="$ctx->currentRaceTypeArray()"
>
    <livewire:race-management.lineup-manager :raceTypeLabel="$ctx->raceTypeLabel()" :eventId="$event->id" :raceClassId="$raceClass->id" />
</x-layouts::module>
