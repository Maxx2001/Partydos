<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import EventList from "@/Pages/Dashboard/Partials/EventList.vue";

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
    <AppLayout title="Events">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Events
            </h2>
        </template>
        <EventList :owned-events="ownedEvents" :events="events"/>
    </AppLayout>
</template>
