<script setup>
import DefaultLayout from '@/Layouts/DefaultLayout.vue';
import EventList from "@/Pages/Events/EventIndex/Partials/EventList.vue";

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
    <DefaultLayout>
        <!-- Hero Section -->
        <section class="relative bg-gradient-to-br from-blue-500 to-purple-600 text-white py-10 md:py-16 px-6">
            <div class="max-w-6xl mx-auto flex flex-col items-center justify-center text-center space-y-6">
                <h1 class="text-3xl md:text-5xl font-extrabold leading-tight drop-shadow-lg">
                    Your Party, Your Events
                </h1>
                <p class="text-base md:text-lg text-white/80 max-w-3xl">
                    Manage events you’re organizing or explore invitations you’ve received.
                </p>
            </div>

            <div class="absolute -top-10 left-1/4 w-32 h-32 bg-white/20 rounded-full blur-3xl"></div>
            <div class="absolute bottom-0 right-0 w-48 h-48 bg-white/10 rounded-full blur-3xl"></div>
        </section>

        <!-- Event List Section -->
        <section id="event-list">
            <div class="max-w-6xl mx-auto px-6">
                <EventList :owned-events="ownedEvents" :events="events" />
            </div>
        </section>
    </DefaultLayout>
</template>

<style>
/* For a smooth scrolling experience when navigating to #event-list */
html {
    scroll-behavior: smooth;
}
</style>
