<script setup>
import { ref, computed, watch, defineEmits } from 'vue';
import { ChevronLeftIcon, ChevronRightIcon } from '@heroicons/vue/20/solid';
import {
    format,
    addMonths,
    subMonths,
    startOfMonth,
    endOfMonth,
    startOfWeek,
    endOfWeek,
    eachDayOfInterval,
    isSameMonth,
    isSameDay,
    isToday,
    setHours,
    setMinutes,
    isBefore,
} from 'date-fns';

// State Management with ref
const currentMonth = ref(new Date());
const selectedDate = ref(new Date()); // Initialize with today's date

// Time Picker State
const selectedHour = ref(format(new Date(), 'HH')); // Initialize with the current hour
const selectedMinute = ref(format(new Date(), 'mm')); // Initialize with the current minute

// End Time Picker State
const enableEndTime = ref(false);
const selectedEndHour = ref(format(new Date(), 'HH')); // Initialize with the current hour
const selectedEndMinute = ref(format(new Date(), 'mm')); // Initialize with the current minute

// Days of the week for the calendar header (starting from Monday)
const daysOfWeek = ['MON', 'TUE', 'WED', 'THU', 'FRI', 'SAT', 'SUN'];

// Hours and Minutes Options for Time Picker
const hours = Array.from({ length: 24 }, (_, i) => i.toString().padStart(2, '0')); // ['00', '01', ..., '23']
const minutes = Array.from({ length: 60 }, (_, i) => i.toString().padStart(2, '0')); // ['00', '01', ..., '59']

// Emit event to parent component
const emit = defineEmits(['update']);

// Method to emit updates to parent component
const emitUpdate = () => {
    emit('update', {
        selectedDate: selectedDate.value,
        selectedHour: selectedHour.value,
        selectedMinute: selectedMinute.value,
        selectedEndHour: selectedEndHour.value,
        selectedEndMinute: selectedEndMinute.value,
    });
};

// Method to select a date
const onDateSelect = (day) => {
    selectedDate.value = setHours(setMinutes(day, parseInt(selectedMinute.value)), parseInt(selectedHour.value));
    emitUpdate(); // Emit event on date change
};

// Method to update the selected time
const onTimeChange = () => {
    selectedDate.value = setHours(setMinutes(selectedDate.value, parseInt(selectedMinute.value)), parseInt(selectedHour.value));
    emitUpdate(); // Emit event on time change
};

// Method to update the end time
const onEndTimeChange = () => {
    if (enableEndTime.value) {
        const endDateTime = setHours(setMinutes(selectedDate.value, parseInt(selectedEndMinute.value)), parseInt(selectedEndHour.value));
        if (isBefore(selectedDate.value, endDateTime)) {
            // Valid end time
            selectedEndHour.value = format(endDateTime, 'HH');
            selectedEndMinute.value = format(endDateTime, 'mm');
        } else {
            // Reset end time if invalid
            selectedEndHour.value = selectedHour.value;
            selectedEndMinute.value = selectedMinute.value;
        }
    }
    emitUpdate(); // Emit event on end time change
};

// Emit initial state to parent component
emitUpdate(); // Emit the initial date and time values on load

// Watchers to emit changes when time changes
watch([selectedHour, selectedMinute], onTimeChange);
watch([selectedEndHour, selectedEndMinute, enableEndTime], onEndTimeChange);

// Method to move to the next month
const nextMonth = () => {
    currentMonth.value = addMonths(currentMonth.value, 1);
};

// Method to move to the previous month
const prevMonth = () => {
    currentMonth.value = subMonths(currentMonth.value, 1);
};

// Computed property to determine the class for each day in the calendar
const dayClass = (day) => {
    return [
        'h-8 w-8 text-center text-sm leading-8 rounded-full cursor-pointer', // Base styles
        !isSameMonth(day, currentMonth.value) && 'text-gray-400', // Days not in the current month
        isSameDay(day, selectedDate.value) && 'bg-blue-500 text-white', // Selected day
        isToday(day) && !isSameDay(day, selectedDate.value) && 'border border-blue-500', // Today's date
    ];
};

// Computed property to generate calendar rows
const calendarRows = computed(() => {
    const monthStart = startOfMonth(currentMonth.value);
    const monthEnd = endOfMonth(monthStart);

    // Calculate the first and last day of the week for the current month's grid, starting from Monday
    const startDate = startOfWeek(monthStart, { weekStartsOn: 1 });
    const endDate = endOfWeek(monthEnd, { weekStartsOn: 1 });

    const days = eachDayOfInterval({ start: startDate, end: endDate });
    const rows = [];

    // Generate weeks for the calendar grid
    for (let i = 0; i < days.length; i += 7) {
        const row = days.slice(i, i + 7).map((date) => ({
            date,
            formatted: format(date, 'd'),
        }));
        rows.push(row);
    }

    return rows;
});
</script>


<template>
    <div class="flex flex-col md:flex-row md:space-y-6 md:space-x-6 p-4 bg-white shadow-lg rounded-lg">
        <!-- Calendar Section -->
        <div class="w-full md:w-[300px]">
            <!-- Header for Month Navigation -->
            <div class="flex justify-between items-center bg-white text-black px-4 py-2">
                <button type="button" @click="prevMonth" class="bg-transparent p-2 rounded-full">
                    <ChevronLeftIcon class="h-5 w-5" />
                </button>
                <h2 class="text-lg font-semibold">{{ format(currentMonth, 'MMMM yyyy') }}</h2>
                <button type="button" @click="nextMonth" class="bg-transparent p-2 rounded-full">
                    <ChevronRightIcon class="h-5 w-5" />
                </button>
            </div>
            <!-- Calendar Grid -->
            <div class="p-4">
                <div class="grid grid-cols-7 gap-1 mb-2">
                    <div
                        v-for="day in daysOfWeek"
                        :key="day"
                        class="text-center text-xs font-medium text-gray-500"
                    >
                        {{ day }}
                    </div>
                </div>
                <div class="mt-4">
                    <div v-for="(row, index) in calendarRows" :key="index" class="grid grid-cols-7 gap-1 mb-1">
                        <div
                            v-for="day in row"
                            :key="day.date"
                            :class="dayClass(day.date)"
                            @click="onDateSelect(day.date)"
                        >
                            <span>{{ day.formatted }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Time Picker Section -->
        <div class="w-full md:w-[180px] flex flex-col justify-between bg-white rounded-lg h-auto md:h-[calc(100%-32px)]">
            <!-- Begin Time Section -->
            <div class="flex flex-col justify-center items-center space-y-2 mb-4">
                <label class="text-sm font-medium text-gray-700 mb-1">Begin Time</label>
                <div class="flex items-center space-x-4">
                    <!-- Hours Input -->
                    <select
                        v-model="selectedHour"
                        @change="onTimeChange"
                        class="block w-[4rem] text-center p-2 pr-8 bg-white border border-gray-300 rounded-md focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-300 appearance-none"
                        :style="{ backgroundImage: 'url(data:image/svg+xml,%3Csvg fill=%22none%22 stroke=%22%23999%22 stroke-width=%222%22 viewBox=%220 0 24 24%22 xmlns=%22http://www.w3.org/2000/svg%22%3E%3Cpath stroke-linecap=%22round%22 stroke-linejoin=%22round%22 d=%22M19 9l-7 7-7-7%22/%3E%3C/svg%3E)', backgroundRepeat: 'no-repeat', backgroundPosition: 'right 0.75rem center', backgroundSize: '1rem' }"
                    >
                        <option v-for="hour in hours" :key="hour" :value="hour">{{ hour }}</option>
                    </select>
                    <!-- Minutes Input -->
                    <select
                        v-model="selectedMinute"
                        @change="onTimeChange"
                        class="block w-[4rem] text-center p-2 pr-8 bg-white border border-gray-300 rounded-md focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-300 appearance-none"
                        :style="{ backgroundImage: 'url(data:image/svg+xml,%3Csvg fill=%22none%22 stroke=%22%23999%22 stroke-width=%222%22 viewBox=%220 0 24 24%22 xmlns=%22http://www.w3.org/2000/svg%22%3E%3Cpath stroke-linecap=%22round%22 stroke-linejoin=%22round%22 d=%22M19 9l-7 7-7-7%22/%3E%3C/svg%3E)', backgroundRepeat: 'no-repeat', backgroundPosition: 'right 0.75rem center', backgroundSize: '1rem' }"
                    >
                        <option v-for="minute in minutes" :key="minute" :value="minute">{{ minute }}</option>
                    </select>
                </div>
            </div>

            <!-- End Time Section -->
            <div class="flex flex-col justify-center items-center space-y-2">
                <!-- Checkbox for End Time -->
                <div class="flex items-center space-x-2 mt-4">
                    <input
                        id="enableEndTime"
                        type="checkbox"
                        v-model="enableEndTime"
                        @change="onEndTimeChange"
                        class="rounded h-4 w-4 text-blue-600 border-gray-300 focus:outline-none focus:ring-0 focus:ring-offset-0 cursor-pointer"
                    />
                    <label
                        for="enableEndTime"
                        class="text-sm text-gray-700 cursor-pointer"
                    >
                        Want to set an End Time?
                    </label>
                </div>

                <!-- End Time Selection (Conditional Rendering with Transition) -->
                <transition
                    enter-active-class="transition ease-out duration-300 transform"
                    enter-from-class="opacity-0 translate-y-4"
                    enter-to-class="opacity-100 translate-y-0"
                    leave-active-class="transition ease-in duration-200 transform"
                    leave-from-class="opacity-100 translate-y-0"
                    leave-to-class="opacity-0 translate-y-4"
                >
                    <div v-show="enableEndTime" class="flex flex-col items-center space-x-4 mt-4 pt-4">
                        <label class="text-sm font-medium text-gray-700 mb-1">End Time</label>
                        <!-- End Hours Dropdown -->
                        <div class="flex items-center space-x-4">
                            <select
                                v-model="selectedEndHour"
                                @change="onEndTimeChange"
                                class="block w-[4rem] text-center p-2 pr-8 bg-white border border-gray-300 rounded-md focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-300 appearance-none"
                                :style="{ backgroundImage: 'url(data:image/svg+xml,%3Csvg fill=%22none%22 stroke=%22%23999%22 stroke-width=%222%22 viewBox=%220 0 24 24%22 xmlns=%22http://www.w3.org/2000/svg%22%3E%3Cpath stroke-linecap=%22round%22 stroke-linejoin=%22round%22 d=%22M19 9l-7 7-7-7%22/%3E%3C/svg%3E)', backgroundRepeat: 'no-repeat', backgroundPosition: 'right 0.75rem center', backgroundSize: '1rem' }"
                            >
                                <option v-for="hour in hours" :key="hour" :value="hour">{{ hour }}</option>
                            </select>
                            <!-- End Minutes Dropdown -->
                            <select
                                v-model="selectedEndMinute"
                                @change="onEndTimeChange"
                                class="block w-[4rem] text-center p-2 pr-8 bg-white border border-gray-300 rounded-md focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-300 appearance-none"
                                :style="{ backgroundImage: 'url(data:image/svg+xml,%3Csvg fill=%22none%22 stroke=%22%23999%22 stroke-width=%222%22 viewBox=%220 0 24 24%22 xmlns=%22http://www.w3.org/2000/svg%22%3E%3Cpath stroke-linecap=%22round%22 stroke-linejoin=%22round%22 d=%22M19 9l-7 7-7-7%22/%3E%3C/svg%3E)', backgroundRepeat: 'no-repeat', backgroundPosition: 'right 0.75rem center', backgroundSize: '1rem' }"
                            >
                                <option v-for="minute in minutes" :key="minute" :value="minute">{{ minute }}</option>
                            </select>
                        </div>
                    </div>
                </transition>
            </div>
        </div>
    </div>
</template>

