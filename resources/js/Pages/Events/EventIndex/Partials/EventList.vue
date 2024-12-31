<script setup>
import { defineProps, computed } from 'vue';
import EventCard from "@/Pages/Events/EventIndex/Partials/EventCard.vue";

const props = defineProps({
    events: {
        type: Array,
        required: true
    },
    ownedEvents: {
        type: Array,
        required: true
    },
    historyEvents: {
        type: Array,
        required: true
    },
    activeTab: {
        type: Number,
        required: true
    }
});

const activeTabData = computed(() => {
    switch (props.activeTab) {
        case 0:
            return {
                events: props.ownedEvents,
                title: 'Your Organized Events',
                canEdit: true
            };
        case 1:
            return {
                events: props.events,
                title: 'Your Invited Events',
                canEdit: false
            };
        case 2:
            return {
                events: props.historyEvents,
                title: 'Past invited and organized',
                canEdit: false
            };
        default:
            return {
                events: [],
                title: 'No Events',
                canEdit: false
            };
    }
});
</script>

<template>
    <div class="pb-8">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <div>
                <div class="p-2">
                    <h2 class="text-2xl font-bold text-blue-600 mb-4 text-center">
                        {{ activeTabData.title }}
                    </h2>
                    <ol v-if="activeTabData.events.length > 0"
                        class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                        <EventCard
                            v-for="event in activeTabData.events"
                            :key="event.id"
                            :event="event"
                            :can-edit="activeTabData.canEdit"
                        />
                    </ol>
                    <p v-else class="italic underline text-lg text-gray-500 text-center">
                        No events to display.
                    </p>
                </div>
            </div>
        </div>
    </div>
</template>
