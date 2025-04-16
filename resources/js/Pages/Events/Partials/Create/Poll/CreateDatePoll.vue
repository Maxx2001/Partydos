<script setup>
import { useTitle } from '@/Composables/useTitle.js';
import BaseButton from '@/Components/Base/BaseButton.vue';
import BaseOutlineButton from '@/Components/Base/BaseOutlineButton.vue';
import PollDateList from './PollDateList.vue';

useTitle('Create Date Poll | Partydos');

// Props received from the Flow component
const props = defineProps({
    options: {
        type: Array,
        required: true,
    }
});

// Emits to communicate user actions back to the Flow component
const emit = defineEmits([
    'returnToPreviousStep',
    'addDateOptionTrigger', // Event to signal opening the date picker modal
    'removeDateOption',     // Event to remove an option
    'setTimeForOption',     // Event to signal opening the time picker modal
    'submitPoll'            // Event to trigger the poll submission
]);

// Methods that just emit events
const triggerAddDateOption = () => {
    emit('addDateOptionTrigger');
};

const removeOption = (index) => {
    emit('removeDateOption', index);
};

const setTime = (payload) => {
    emit('setTimeForOption', payload);
};

const submit = () => {
    emit('submitPoll');
};

const goBack = () => {
    emit('returnToPreviousStep');
};

</script>

<template>
    <div class="w-full flex flex-col items-center justify-center bg-slate-100 rounded">
        <h1 class="text-2xl md:text-4xl font-bold mb-6">Create Your Date Poll</h1>

        <div class="w-full max-w-2xl space-y-6">
            <!-- Button to trigger adding a date option -->
            <div class="flex justify-center">
                <BaseButton @click="triggerAddDateOption" label="Add Date Option"/>
            </div>

            <!-- Selected Dates List (Receives options as props, emits actions) -->
            <PollDateList
                :options="props.options"
                @removeOption="removeOption"
                @setTime="setTime"
            />

            <!-- Actions -->
            <div class="flex justify-between mt-8"> <!-- Changed justify-end to justify-between -->
                <BaseOutlineButton label="Back" class="mr-4" @click="goBack" />
                <BaseButton label="Create Poll" @click="submit" :disabled="props.options.length === 0" />
            </div>
        </div>
    </div>
    <!-- Modals are now handled by the parent (CreateDatePollFlow) -->
</template>
