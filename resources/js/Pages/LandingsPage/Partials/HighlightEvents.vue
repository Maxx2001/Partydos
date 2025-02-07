<script setup>
import "swiper/swiper-bundle.css";
import UpcomingEvents from "@/Pages/LandingsPage/Partials/UpcomingEvents.vue";
import EventInspirations from "@/Pages/LandingsPage/Partials/EventInspirations.vue";

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
    <div class="flex flex-col items-center justify-center py-12 px-6 bg-gradient-to-b from-blue-50 to-white">
        <UpcomingEvents
            v-if="showUpcomingEvents && events.length > 0"
            :events="events"
        />
        <EventInspirations v-else/>
    </div>
</template>
