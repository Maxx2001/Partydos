<script setup>
import { defineProps, computed } from 'vue';
import BaseButton from "@/Components/Base/BaseButton.vue";

// Define props for the event object
const props = defineProps({
    event: {
        type: Object,
        required: true,
    },
});

const formatDate = (dateString) => {
    const options = {
        day: 'numeric',
        month: 'long',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
    };
    return new Intl.DateTimeFormat('en-GB', options).format(new Date(dateString));
};

// Computed properties to handle the formatting
const formattedStartDate = computed(() => {
    return props.event.startDateTime ? formatDate(props.event.startDateTime) : 'No start date set.';
});

const formattedEndDate = computed(() => {
    return props.event.endDateTime ? formatDate(props.event.endDateTime) : 'No end date set.';
});

const emits = defineEmits(['acceptEventInvite']);

</script>

<template>
    <div class="w-full lg:w-1/2 bg-white shadow-lg rounded-lg p-6 mx-auto my-4">
        <div class="w-full flex justify-center text-2xl font-semibold">
            <h1 class="text-3xl">
                You are invited to:
            </h1>
        </div>
        <div class="flex justify-between items-center mb-4 pt-4">
            <h2 class="text-xl font-bold text-gray-800">{{ event.title || 'No Title' }}</h2>
        </div>
        <div class="text-gray-700">
            <p class="mt-2">
                <span>{{ event.description || 'No event description set.' }}</span>
            </p>
            <p class="mt-2">
                <strong>Location: </strong>
                <span>{{ event.location || 'No event location set.' }}</span>
            </p>

            <p class="mt-2">
                <strong class="pr-2">Event starts at:</strong>
                <span>{{ formattedStartDate }}</span>
            </p>
            <p class="mt-2" v-if="event.endDateTime && event.endDateTime !== event.startDateTime">
                <strong class="pr-2">Event ends at:</strong>
                <span>{{ formattedEndDate }}</span>
            </p>
        </div>
        <div class="flex justify-end mt-6 space-x-4">
            <BaseButton label="Accept Invitation" @click="emits('acceptEventInvite')"/>
        </div>
    </div>
</template>

<style scoped>
/* You can add any additional styles here */
</style>
