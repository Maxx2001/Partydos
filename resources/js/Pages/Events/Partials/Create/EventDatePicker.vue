<script setup>
import BaseButton from "@/Components/Base/BaseButton.vue";
import BaseOutlineButton from "@/Components/Base/BaseOutlineButton.vue";
import { computed } from 'vue';
import { DatePicker as VDatePicker } from 'v-calendar'; // Import v-calendar's DatePicker
// 'v-calendar/dist/style.css'; // Styles are imported globally in app.js

const props = defineProps({
    allowMultipleSelections: Boolean,
    dateOptions: Array, // Define prop for dateOptions
    errors: Object, // Define prop for errors
});

const emits = defineEmits(['submitEventDetails', 'returnToPreviousStep', 'update-allow-multiple']);

const localAllowMultipleSelections = computed({
    get: () => props.allowMultipleSelections,
    set: (value) => {
        emits('update-allow-multiple', value);
    }
});

// Function for removing a date option
const removeDateOption = (index) => {
    if (props.dateOptions.length > 1) {
        props.dateOptions.splice(index, 1);
    }
};

const getDateOptionError = (index, field) => {
    if (props.errors && props.errors[`dateOptions.${index}.${field}`]) {
        return props.errors[`dateOptions.${index}.${field}`];
    }
    return null;
};

// Function for adding a new date option
const addDateOption = () => {
    props.dateOptions.push({ date: null, time: null });
};
</script>
<template>
    <div class="w-full flex justify-center text-2xl font-semibold">
        <h1 class="text-2xl md:text-4xl text-center">
            When will this event take place?
        </h1>
    </div>

    <!-- Added Checkbox -->
    <div class="w-full flex justify-center my-6 md:w-2/3 xl:w-1/3">
        <label class="flex items-center space-x-3 p-3 bg-white rounded-lg shadow">
            <input
                type="checkbox"
                v-model="localAllowMultipleSelections"
                class="form-checkbox h-5 w-5 rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
            />
            <span class="text-sm text-gray-700">Allow attendees to pick multiple dates?</span>
        </label>
    </div>

    <!-- Date Options List -->
    <div class="w-full md:w-2/3 xl:w-1/3 mt-6 space-y-4">
        <div v-for="(option, index) in props.dateOptions" :key="index" class="p-4 bg-white rounded-lg shadow flex items-center space-x-3">
            <div class="flex-grow">
                <label :for="'date-' + index" class="block text-sm font-medium text-gray-700 mb-1">Date</label>
                <v-date-picker
                    v-model="option.date"
                    mode="date"
                    :masks="{ modelValue: 'YYYY-MM-DD' }"
                    :input-debounce="500"
                    :popover="{ visibility: 'focus' }"
                    class="w-full"
                >
                    <template #default="{ inputValue, inputEvents }">
                        <input
                            :id="'date-' + index"
                            class="form-input block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 sm:text-sm"
                            :class="{ 'border-red-500': getDateOptionError(index, 'date') }"
                            :value="inputValue"
                            v-on="inputEvents"
                        />
                    </template>
                </v-date-picker>
                <p v-if="getDateOptionError(index, 'date')" class="mt-1 text-xs text-red-600">
                    {{ getDateOptionError(index, 'date') }}
                </p>
            </div>
            <div class="flex-shrink-0">
                <label :for="'time-' + index" class="block text-sm font-medium text-gray-700 mb-1">Time (Optional)</label>
                <input
                    :id="'time-' + index"
                    type="time"
                    v-model="option.time"
                    class="form-input block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 sm:text-sm"
                    style="min-width: 100px;"
                />
            </div>
            <div class="flex-shrink-0 self-end">
                <button
                    @click="removeDateOption(index)"
                    v-if="props.dateOptions.length > 1"
                    type="button"
                    class="px-3 py-2 text-sm font-medium text-red-600 hover:text-red-800 bg-red-100 hover:bg-red-200 rounded-md shadow-sm"
                >
                    Remove
                </button>
            </div>
        </div>
    </div>

    <!-- Add Another Date Option Button -->
    <div class="w-full md:w-2/3 xl:w-1/3 mt-4 flex justify-start">
        <button
            @click="addDateOption"
            type="button"
            class="px-4 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
        >
            Add Another Date Option
        </button>
    </div>

    <div class="w-full flex justify-end mt-6 md:w-2/3 xl:w-1/3">
        <BaseOutlineButton label="Back to event details" class="mr-4" @click="emits('returnToPreviousStep')"/> <!-- Changed label for clarity -->
        <BaseButton label="Next: Customize" @click="emits('submitEventDetails')"/> <!-- Changed label for clarity -->
    </div>
</template>
