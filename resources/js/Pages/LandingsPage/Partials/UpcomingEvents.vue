<script setup>
import EventCard from "@/Pages/Events/Partials/Index/EventCard.vue";

defineProps({
    events: {
        type: Array,
        required: true
    }
});
</script>

<template>
    <div class="flex flex-col items-center justify-center py-12 px-6 bg-gradient-to-b from-blue-50 to-white">
        <div class="p-2 w-full px-6 lg:px-64">
            <h2 class="text-2xl font-bold text-blue-600 mb-4 text-center pb-4">
                Upcoming Events
            </h2>
            <ol v-if="events.length > 0"
                class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6 ">
                <EventCard
                    v-for="event in events"
                    :key="event.id"
                    :event="event"
                    :can-edit="event.canEdit"
                />
            </ol>
            <p v-else class="italic underline text-lg text-gray-500 text-center">
                No events to display.
            </p>
        </div>
    </div>
</template>
