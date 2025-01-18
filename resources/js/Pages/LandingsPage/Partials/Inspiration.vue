<script setup>
import EventCard from "@/Pages/Events/Partials/Index/EventCard.vue";

const inspirationEvents = [
    {
        'title': 'A Party to Remember',
    },
    {
        'title': 'A family gathering',
    },
    {
        'title': 'A fun trip'
    }
];
</script>

<template>
    <div class="flex flex-col items-center justify-center py-12 px-6 bg-gradient-to-b from-blue-50 to-white">
        <div class="p-2 w-full px-6 lg:px-64">
            <h2 class="text-2xl font-bold text-blue-600 mb-4 text-center pb-4">
                No upcoming events? Plan them!
            </h2>
            <ol
                class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6 ">
                <EventCard
                    v-for="event in events"
                    :key="event.id"
                    :event="event"
                    :can-edit="event.canEdit"
                />
            </ol>
        </div>
    </div>
</template>
