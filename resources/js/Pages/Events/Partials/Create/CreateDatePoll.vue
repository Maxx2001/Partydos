<script setup>
import { ref, reactive } from 'vue';
import { router } from '@inertiajs/vue3';

import { useTitle } from '@/Composables/useTitle.js';
import BaseButton from '@/Components/Base/BaseButton.vue';
import BaseOutlineButton from '@/Components/Base/BaseOutlineButton.vue';
import PollDateList from './PollDateList.vue';
import DatePicker from './DatePicker.vue';
import TimePickerModal from './TimePickerModal.vue';

useTitle('Create Date Poll | Partydos');

// Emits
const emit = defineEmits(['returnToPreviousStep']);

// Form state
const form = reactive({
    title: '',
    description: '',
    options: [],
});

// Picker and modal states
const showDatePicker = ref(false);
const showTimePicker = ref(false);
const selectedOption = ref(null);
const selectedIndex = ref(null);

// Toggle date picker modal
const toggleDatePicker = () => {
    showDatePicker.value = !showDatePicker.value;
};

// Add or update a date option
const addDateOption = (newOption) => {
    const index = form.options.findIndex(option =>
        option.selectedDate.toDateString() === newOption.selectedDate.toDateString()
    );

    if (index !== -1) {
        form.options.splice(index, 1, newOption); // update existing
    } else {
        form.options.push(newOption); // add new
    }
};

// Remove date option
const removeDateOption = (index) => {
    form.options.splice(index, 1);
};

// Submit form
const submitPoll = () => {
    router.post(route('date-polls.store'), form);
};

// Open time selection modal
const openTimeSelection = ({ option, index }) => {
    selectedOption.value = option;
    selectedIndex.value = index;
    showTimePicker.value = true;
};

// Update selected time
const updateTime = ({ hour, minute, endHour, endMinute }) => {
    if (selectedIndex.value !== null && selectedIndex.value >= 0) {
        const option = form.options[selectedIndex.value];
        if (!option) return;

        option.selectedHour = hour;
        option.selectedMinute = minute;

        if (endHour !== undefined && endMinute !== undefined) {
            option.selectedEndHour = endHour;
            option.selectedEndMinute = endMinute;
        } else {
            delete option.selectedEndHour;
            delete option.selectedEndMinute;
        }
    }

    showTimePicker.value = false;
};

</script>

<template>
    <div class="w-full flex flex-col items-center justify-center bg-slate-100 rounded">
        <h1 class="text-2xl md:text-4xl font-bold mb-6">Create Your Date Poll</h1>

        <div class="w-full max-w-2xl space-y-6">
            <button @click="toggleDatePicker" class="bg-blue-500 text-white p-2 rounded">
                Add Date Option
            </button>

            <!-- Date Picker Modal -->
            <div v-if="showDatePicker" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center" @click="toggleDatePicker">
                <div class="bg-white p-6 rounded shadow-lg" @click.stop>
                    <DatePicker :selectedDates="form.options" @update="addDateOption" />
                    <div class="flex justify-end mt-4">
                        <BaseButton label="Close" @click="toggleDatePicker" variant="cancel" />
                        <BaseButton label="Pick Date" @click="toggleDatePicker" class="ml-4" />
                    </div>
                </div>
            </div>

            <!-- Selected Dates List -->
            <PollDateList
                :options="form.options"
                @removeOption="removeDateOption"
                @setTime="openTimeSelection"
            />

            <!-- Actions -->
            <div class="flex justify-end">
                <BaseOutlineButton label="Back to date picker" class="mr-4" @click="emit('returnToPreviousStep')" />
                <BaseButton label="Create Poll" @click="submitPoll" :disabled="form.options.length === 0" />
            </div>
        </div>
    </div>

    <!-- Time Picker Modal -->
    <TimePickerModal
        v-if="showTimePicker"
        :option="selectedOption"
        :index="selectedIndex"
        @close="showTimePicker = false"
        @confirm="updateTime"
    />
</template>
