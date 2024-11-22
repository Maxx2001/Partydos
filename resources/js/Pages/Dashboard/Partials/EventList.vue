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
    <div class="py-8">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <div>
                <div class="p-2">
                    <h2 class="text-2xl font-bold text-blue-600 mb-4">Your Organized Events</h2>
                    <ol class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                        <EventCard v-for="ownedEvent in ownedEvents" :key="ownedEvent.id" :event="ownedEvent"/>
                    </ol>
                </div>
                <div class="p-2 mt-6 border-t border-gray-300">
                    <h2 class="text-2xl font-bold text-purple-600 mb-4">Invited Events</h2>
                    <ol class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                        <EventCard v-for="event in events" :key="event.id" :event="event"/>
                    </ol>
                </div>
            </div>
        </div>
    </div>
</template>
