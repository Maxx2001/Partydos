<script setup>
import DefaultLayout from '@/Layouts/DefaultLayout.vue';
// import PollDatePicker from '@/Pages/DatePoll/Partials/PollDatePicker.vue';
import PollDatePicker from './PollDatePicker.vue';
import PollDateList from './PollDateList.vue';
import BaseButton from '@/Components/Base/BaseButton.vue';
import {  reactive, ref } from 'vue';
import { router } from '@inertiajs/vue3';
import { useTitle } from '@/Composables/useTitle.js';
import BaseOutlineButton from '@/Components/Base/BaseOutlineButton.vue';
import DatePicker from './DatePicker.vue';
import TimePickerModal from './TimePickerModal.vue';

useTitle('Create Date Poll | Partydos');

const form = reactive({
    title: '',
    description: '',
    options: [], // Hier slaan we de datums en optionele tijden op
});

const showDatePicker = ref(false);

const toggleDatePicker = () => {
    showDatePicker.value = !showDatePicker.value;
};

const addDateOption = (selectedDate) => {
    if (!selectedDate || typeof selectedDate !== 'object') {
        console.error('Expected a date object');
        return;
    }

    // Find the index of the existing date entry based on the date only
    const index = form.options.findIndex(option => 
        option.selectedDate.getDate() === selectedDate.selectedDate.getDate()
    );

    if (index !== -1) {
        // Update the existing entry with the new time
        console.log('Updating existing entry at index:', index);
        form.options.splice(index, 1);
        // form.options[index] = {
        //     ...form.options[index],
        //     selectedHour: selectedDate.selectedHour,
        //     selectedMinute: selectedDate.selectedMinute,
        //     selectedEndHour: selectedDate.selectedEndHour,
        //     selectedEndMinute: selectedDate.selectedEndMinute,
        // };
    } else {
        // If the date is not found, add it
        console.log('Adding new entry');    
        form.options.push({
            selectedDate: selectedDate.selectedDate,
            selectedHour: selectedDate.selectedHour,
            selectedMinute: selectedDate.selectedMinute,
            selectedEndHour: selectedDate.selectedEndHour,
            selectedEndMinute: selectedDate.selectedEndMinute,
        });
    }
};

const removeDateOption = (index) => {
    form.options.splice(index, 1);
};

const submitPoll = () => {
    router.post(route('date-polls.store'), form);
};

const emits = defineEmits(['returnToPreviousStep']);

const selectedDates = ref([]);

function customDebounce(func, delay) {
  let timeoutId;
  return function(...args) {
    clearTimeout(timeoutId);
    timeoutId = setTimeout(() => {
      func.apply(this, args);
    }, delay);
  };
}

const emitUpdate = customDebounce(() => {
  const updateData = {
    selectedDate: selectedDate.value,
  };

  if (enableTime.value) {
    updateData.selectedHour = selectedHour.value;
    updateData.selectedMinute = selectedMinute.value;
  }

  if (enableEndTime.value) {
    updateData.selectedEndHour = selectedEndHour.value;
    updateData.selectedEndMinute = selectedEndMinute.value;
  }

  emit('update', updateData);
}, 300);

// Add this method to handle time selection
const openTimeSelection = ({ option, index }) => {
    // Logic to open the time selection modal
    console.log('Setting time for option:', option);
    // You can set the selected option in a ref and show the modal
    selectedOption.value = option;
    selectedIndex.value = index;
    showTimePicker.value = true;
};

const showTimePicker = ref(false);
const selectedOption = ref(null);
const selectedIndex = ref(null);

// const setTime = (option, index) => {
//     selectedOption.value = option;
//     selectedIndex.value = index;
//     showTimePicker.value = true;
// };

const updateTime = (hour, minute) => {
    if (selectedOption.value) {
        selectedOption.value.selectedHour = hour;
        selectedOption.value.selectedMinute = minute;
        emitUpdate();
    }
};
</script>

<template>
    <!-- <DefaultLayout> -->
        <div class="w-full flex flex-col items-center justify-center bg-slate-100 rounded">
            <h1 class="text-2xl md:text-4xl font-bold mb-6">Create Your Date Poll</h1>

            <div class="w-full max-w-2xl space-y-6">
                <button @click="toggleDatePicker" class="bg-blue-500 text-white p-2 rounded">
                    Add Date Option
                </button>
                <div v-if="showDatePicker" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center" @click="toggleDatePicker">
                    <div class="bg-white p-6 rounded shadow-lg" @click.stop>
                        <DatePicker :selectedDates="form.options" @update="addDateOption" />
                        <div class="flex justify-end mt-4">
                            <BaseButton label="Close" @click="toggleDatePicker" variant="cancel" />
                            <BaseButton label="Pick Date" @click="toggleDatePicker" class="ml-4" />
                        </div>
                    </div>
                </div>
                <PollDateList :options="form.options" @removeOption="removeDateOption" @setTime="openTimeSelection" />

                <!-- Submit knop -->
                <div class="flex justify-end">
                    <BaseOutlineButton label="Back to date picker" class="mr-4" @click="emits('returnToPreviousStep')"/>
                    <BaseButton label="Create Poll" @click="submitPoll" :disabled="form.options.length === 0" />
                </div>
            </div>
        </div>
        <TimePickerModal 
            v-if="showTimePicker" 
            :option="selectedOption" 
            :index="selectedIndex" 
            @close="showTimePicker = false" 
            @confirm="updateTime" 
        />
    <!-- </DefaultLayout> -->
</template>
