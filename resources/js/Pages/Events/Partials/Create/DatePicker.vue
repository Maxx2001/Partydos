<script setup>
import { ref, computed, watch } from 'vue';
import {
    format,
    parse,
    addMonths,
    subMonths,
    startOfMonth,
    endOfMonth,
    startOfWeek,
    endOfWeek,
    eachDayOfInterval,
    isSameMonth,
    isSameDay,
    isToday
} from 'date-fns';

import { ChevronLeftIcon, ChevronRightIcon } from '@heroicons/vue/20/solid';

const props = defineProps({
    selectedDates: {
        type: Array,
        required: false,
        default: () => [],
    },
});

const emit = defineEmits(['update']);

const currentMonth = ref(new Date());
const daysOfWeek = ['MON', 'TUE', 'WED', 'THU', 'FRI', 'SAT', 'SUN'];

const calendarRows = computed(() => {
    const start = startOfWeek(startOfMonth(currentMonth.value), {weekStartsOn: 1});
    const end = endOfWeek(endOfMonth(currentMonth.value), {weekStartsOn: 1});
    const days = eachDayOfInterval({start, end});

    const weeks = [];
    for (let i = 0; i < days.length; i += 7) {
        weeks.push(days.slice(i, i + 7));
    }
    return weeks;
});

const isSelected = (day) => {
    return props.selectedDates.some(option => {
        if (!option || !option.selectedDate) return false;
        return isSameDay(option.selectedDate, day);
    });
};


const toggleDate = (day) => {
    const index = props.selectedDates.findIndex(option => isSameDay(option.selectedDate, day));
    const newDates = [...props.selectedDates];

    if (index === -1) {
        newDates.push({selectedDate: day});
    } else {
        newDates.splice(index, 1);
    }

    emit('update', newDates);
};

const dayClass = (day) => {
    return [
        'h-8 w-8 text-center text-sm leading-8 rounded-full cursor-pointer',
        !isSameMonth(day, currentMonth.value) && 'text-gray-400',
        isSelected(day) && 'bg-blue-500 text-white',
        isToday(day) && !isSelected(day) && 'border border-blue-500',
    ];
};

const nextMonth = () => {
    currentMonth.value = addMonths(currentMonth.value, 1);
};

const prevMonth = () => {
    currentMonth.value = subMonths(currentMonth.value, 1);
};
</script>

<template>
    <div class="flex flex-col p-4 bg-white shadow-lg rounded-lg w-full md:max-w-md">
        <div class="flex justify-between items-center text-black px-4 py-2">
            <button @click="prevMonth" class="p-2 rounded-full">
                <ChevronLeftIcon class="h-5 w-5"/>
            </button>
            <h2 class="text-lg font-semibold">{{ format(currentMonth, 'MMMM yyyy') }}</h2>
            <button @click="nextMonth" class="p-2 rounded-full">
                <ChevronRightIcon class="h-5 w-5"/>
            </button>
        </div>

        <div class="px-4">
            <div class="grid grid-cols-7 gap-1 mb-2">
                <div v-for="day in daysOfWeek" :key="day" class="text-center text-xs font-medium text-gray-500">
                    {{ day }}
                </div>
            </div>

            <div v-for="(row, index) in calendarRows" :key="index" class="grid grid-cols-7 gap-1 mb-1">
                <div
                    v-for="day in row"
                    :key="day"
                    :class="dayClass(day)"
                    @click="toggleDate(day)"
                >
                    {{ format(day, 'd') }}
                </div>
            </div>
        </div>
    </div>
</template>
