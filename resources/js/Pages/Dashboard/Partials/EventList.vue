<script setup>
import { defineProps } from 'vue';
import EventCard from "@/Pages/Dashboard/Partials/EventCard.vue";

const props = defineProps({
    events: {
        type: Array,
        required: true
    },
    ownedEvents: {
        type: Array,
        required: true
    }
});
</script>

<template>
    <div class="py-12">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                <ol class="col-start-1 col-end-2 row-start-1 grid grid-cols-1 sm:grid-cols-4 gap-8 sm:pr-8 p-8">
                    <EventCard v-for="event in events"  :key="event.id" :event="event"/>
                    <EventCard v-for="event in events"  :key="event.id" :event="event"/>
                    <EventCard v-for="event in events"  :key="event.id" :event="event"/>
                    <EventCard v-for="ownedEvent in ownedEvents"  :key="ownedEvent.id" :event="ownedEvent"/>
                    <EventCard v-for="ownedEvent in ownedEvents"  :key="ownedEvent.id" :event="ownedEvent"/>
                    <EventCard v-for="ownedEvent in ownedEvents"  :key="ownedEvent.id" :event="ownedEvent"/>
                    <EventCard v-for="ownedEvent in ownedEvents"  :key="ownedEvent.id" :event="ownedEvent"/>
                </ol>
            </div>
        </div>
    </div>
<!--    <ol class="col-start-1 col-end-2 row-start-1 grid grid-cols-1 sm:grid-cols-7 sm:pr-8" style="grid-template-rows: 1.75rem repeat(288, minmax(0, 1fr)) auto">-->
<!--        <EventCard v-for="event in events"  :key="event.id" :event="event"/>-->
<!--        <EventCard v-for="ownedEvent in ownedEvents"  :key="ownedEvent.id" :event="ownedEvent"/>-->
<!--    </ol>-->
</template>
