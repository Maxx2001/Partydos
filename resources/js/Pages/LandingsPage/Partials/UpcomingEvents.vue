<script setup>
import EventCard from "@/Pages/Events/Partials/Index/EventCard.vue";
defineProps({
    events: {
        type: Array,
        required: true,
    },
    showUpcomingEvents: {
        type: Boolean,
        required: false,
        default: true,
    },
});
</script>

<template>
    <div class="w-full max-w-6xl">
        <div v-if="showUpcomingEvents && events.length > 0">
            <h2 class="text-2xl font-bold text-blue-600 mb-4 text-center pb-4">
                You're next Events
            </h2>
            <ol class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
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
