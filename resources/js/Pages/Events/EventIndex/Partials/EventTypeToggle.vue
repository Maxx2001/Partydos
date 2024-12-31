<script setup>
import { defineProps } from "vue";
import EventTypeButton from "@/Pages/Events/EventIndex/Partials/EventTypeButton.vue";

const props = defineProps({
    activeTab: {
        type: Number,
        required: true
    },
    setActiveTab: {
        type: Function,
        required: true
    }
});
</script>

<template>
    <section>
        <div class="flex flex-col items-center justify-center py-6 px-6 bg-gradient-to-b from-blue-50 to-white">
            <div
                class="w-full max-w-2xl bg-white rounded-lg shadow-lg overflow-hidden"
            >
                <div class="flex divide-x divide-gray-200">
                    <EventTypeButton
                        :activeTab="activeTab"
                        :setActiveTab="setActiveTab"
                        title="Organised"
                        :tabNumber="0"
                    />
                    <EventTypeButton
                        :activeTab="activeTab"
                        :setActiveTab="setActiveTab"
                        title="Invited"
                        :tabNumber="1"
                    />
                    <EventTypeButton
                        :activeTab="activeTab"
                        :setActiveTab="setActiveTab"
                        title="History"
                        :tabNumber="2"
                    />
                </div>
            </div>
        </div>
    </section>
</template>
