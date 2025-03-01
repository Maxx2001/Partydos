<script setup>
import DefaultLayout from '@/Layouts/DefaultLayout.vue';
import EventList from "@/Pages/Events/Partials/Index/EventList.vue";
import { ref } from "vue";
import EventTypeToggle from "@/Pages/Events/Partials/Index/EventTypeToggle.vue";
import PageHeader from "@/Components/Layout/PageHeader.vue";

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
});

const activeTab = ref(0);

const setActiveTab = (tab) => activeTab.value = tab;
</script>

<template>
    <DefaultLayout>
        <PageHeader>
            Your <span class="text-yellow-500">Party</span>, Your <span class="text-green-500">Events</span>
        </PageHeader>
        <EventTypeToggle  v-model:activeTab="activeTab" :setActiveTab="setActiveTab" />

        <section id="event-list">
            <div class="max-w-6xl mx-auto px-6">
                <EventList
                    :owned-events="ownedEvents"
                    :events="events"
                    :history-events="historyEvents"
                    :active-tab="activeTab"
                />
            </div>
        </section>
    </DefaultLayout>
</template>
