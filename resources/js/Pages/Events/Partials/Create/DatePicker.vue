<script setup>
import {ref, computed, watch, defineEmits} from 'vue';
import {
    format,
    setHours,
    setMinutes,
    parse,
    addDays,
    isBefore,
    startOfMonth,
    endOfMonth,
    startOfWeek,
    endOfWeek,
    eachDayOfInterval,
    isSameMonth,
    isSameDay,
    isToday,
    addMonths,
    subMonths
} from 'date-fns';
import {ChevronLeftIcon, ChevronRightIcon} from '@heroicons/vue/20/solid';

const props = defineProps({
    initialEndTime: {
        type: String,
        required: false,
        default: null,
    },
    initialStartTime: {
        type: String,
        required: false,
        default: null,
    },
    selectedDates: {
        type: Array,
        required: false,
        default: () => [],
    },
});

const currentMonth = ref(new Date());
const selectedDate = ref(
    props.initialStartTime
        ? parse(props.initialStartTime, 'yyyy-MM-dd HH:mm:ss', new Date())
        : new Date()
);

const selectedHour = ref(
    props.initialStartTime
        ? format(parse(props.initialStartTime, 'yyyy-MM-dd HH:mm:ss', new Date()), 'HH')
        : format(new Date(), 'HH')
);
const selectedMinute = ref(
    props.initialStartTime
        ? format(parse(props.initialStartTime, 'yyyy-MM-dd HH:mm:ss', new Date()), 'mm')
        : format(new Date(), 'mm')
);

const enableEndTime = ref(false);
const selectedEndHour = ref(
    props.initialEndTime
        ? format(parse(props.initialEndTime, 'yyyy-MM-dd HH:mm:ss', new Date()), 'HH')
        : format(new Date(), 'HH')
);
const selectedEndMinute = ref(
    props.initialEndTime
        ? format(parse(props.initialEndTime, 'yyyy-MM-dd HH:mm:ss', new Date()), 'mm')
        : format(new Date(), 'mm')
);

if (props.initialEndTime) {
    const initialEnd = parse(props.initialEndTime, 'yyyy-MM-dd HH:mm:ss', new Date());
    if (initialEnd.getTime() !== selectedDate.value.getTime()) {
        enableEndTime.value = true;
    }
}

const daysOfWeek = ['MON', 'TUE', 'WED', 'THU', 'FRI', 'SAT', 'SUN'];
const hours = Array.from({length: 24}, (_, i) => i.toString().padStart(2, '0'));
const minutes = Array.from({length: 60}, (_, i) => i.toString().padStart(2, '0'));

const emit = defineEmits(['update']);

const emitUpdate = () => {
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
};

const onDateSelect = (day) => {
    selectedDate.value = day;
    emitUpdate();
};

const enableTime = ref(false);

const onTimeChange = () => {
    if (enableTime.value) {
        selectedDate.value = setHours(setMinutes(selectedDate.value, parseInt(selectedMinute.value)), parseInt(selectedHour.value));
        onEndTimeChange();
        emitUpdate();
    }
};

const onEndTimeChange = () => {
    if (enableEndTime.value) {
        let endDateTime = setHours(setMinutes(selectedDate.value, parseInt(selectedEndMinute.value)), parseInt(selectedEndHour.value));
        if (isBefore(endDateTime, selectedDate.value) || endDateTime.getTime() === selectedDate.value.getTime()) {
            endDateTime = addDays(endDateTime, 1);
        }
        selectedEndHour.value = format(endDateTime, 'HH');
        selectedEndMinute.value = format(endDateTime, 'mm');
    } else {
        selectedEndHour.value = null;
    }
    emitUpdate();
};

watch([selectedHour, selectedMinute], onTimeChange);
watch([selectedEndHour, selectedEndMinute, enableEndTime], onEndTimeChange);

const nextMonth = () => {
    currentMonth.value = addMonths(currentMonth.value, 1);
};

const prevMonth = () => {
    currentMonth.value = subMonths(currentMonth.value, 1);
};

const dayClass = (day) => {
    return [
        'h-8 w-8 text-center text-sm leading-8 rounded-full cursor-pointer',
        !isSameMonth(day, currentMonth.value) && 'text-gray-400',
        isSelected(day) && 'bg-blue-500 text-white',
        isToday(day) && !isSelected(day) && 'border border-blue-500',
    ];
};

const calendarRows = computed(() => {
    const monthStart = startOfMonth(currentMonth.value);
    const monthEnd = endOfMonth(monthStart);
    const startDate = startOfWeek(monthStart, {weekStartsOn: 1});
    const endDate = endOfWeek(monthEnd, {weekStartsOn: 1});
    const days = eachDayOfInterval({start: startDate, end: endDate});
    const rows = [];
    for (let i = 0; i < days.length; i += 7) {
        const row = days.slice(i, i + 7).map((date) => ({
            date,
            formatted: format(date, 'd'),
        }));
        rows.push(row);
    }
    return rows;
});

const toggleDate = (day) => {
    const index = props.selectedDates.value.findIndex(d => d.getTime() === day.getTime());
    if (index === -1) {
        props.selectedDates.value.push(day);
    } else {
        props.selectedDates.value.splice(index, 1);
    }
    emit('update', props.selectedDates.value);
};

const isSelected = (day) => {
    return props.selectedDates.some(option => isSameDay(option.selectedDate, day));
};
</script>

<template>
    <div class="flex flex-col md:flex-row md:space-y-6 md:space-x-6 p-4 bg-white shadow-lg rounded-lg">
        <div class="w-full md:w-[300px]">
            <div class="flex justify-between items-center bg-white text-black px-4 py-2">
                <button type="button" @click="prevMonth" class="bg-transparent p-2 rounded-full">
                    <ChevronLeftIcon class="h-5 w-5"/>
                </button>
                <h2 class="text-lg font-semibold">{{ format(currentMonth, 'MMMM yyyy') }}</h2>
                <button type="button" @click="nextMonth" class="bg-transparent p-2 rounded-full">
                    <ChevronRightIcon class="h-5 w-5"/>
                </button>
            </div>
            <div class="p-4">
                <div class="grid grid-cols-7 gap-1 mb-2">
                    <div v-for="day in daysOfWeek" :key="day" class="text-center text-xs font-medium text-gray-500">
                        {{ day }}
                    </div>
                </div>
                <div class="mt-4">
                    <div v-for="(row, index) in calendarRows" :key="index" class="grid grid-cols-7 gap-1 mb-1">
                        <div v-for="day in row" :key="day.date" :class="dayClass(day.date)"
                             @click="onDateSelect(day.date)">
                            <span>{{ day.formatted }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div
            class="w-full md:w-[180px] flex flex-col justify-between bg-white rounded-lg h-auto md:h-[calc(100%-32px)]">
            <div class="flex items-center space-x-2 mt-4">
                <input id="enableTime" type="checkbox" v-model="enableTime" class="rounded h-4 w-4 text-blue-600 border-gray-300 focus:outline-none focus:ring-0 focus:ring-offset-0 cursor-pointer"/>
                <label for="enableTime" class="text-sm text-gray-700 cursor-pointer">Set Time?</label>
            </div>
            <transition>
                <div v-show="enableTime" class="flex flex-col items-center space-x-4 mt-4 pt-4">
                    <label class="text-sm font-medium text-gray-700 mb-1">Begin Time</label>
                    <div class="flex items-center space-x-4">
                        <select v-model="selectedHour" @change="onTimeChange"
                                class="block w-[4rem] text-center p-2 pr-8 bg-white border border-gray-300 rounded-md focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-300 appearance-none">
                            <option v-for="hour in hours" :key="hour" :value="hour">{{ hour }}</option>
                        </select>
                        <select v-model="selectedMinute" @change="onTimeChange"
                                class="block w-[4rem] text-center p-2 pr-8 bg-white border border-gray-300 rounded-md focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-300 appearance-none">
                            <option v-for="minute in minutes" :key="minute" :value="minute">{{ minute }}</option>
                        </select>
                    </div>
                </div>
            </transition>
            <div class="flex flex-col justify-center items-center space-y-2">
                <div class="flex items-center space-x-2 mt-4">
                    <input id="enableEndTime" type="checkbox" v-model="enableEndTime" @change="onEndTimeChange"
                           class="rounded h-4 w-4 text-blue-600 border-gray-300 focus:outline-none focus:ring-0 focus:ring-offset-0 cursor-pointer"/>
                    <label for="enableEndTime" class="text-sm text-gray-700 cursor-pointer">Want to set an End
                        Time?</label>
                </div>
                <transition>
                    <div v-show="enableEndTime" class="flex flex-col items-center space-x-4 mt-4 pt-4">
                        <label class="text-sm font-medium text-gray-700 mb-1">End Time</label>
                        <div class="flex items-center space-x-4">
                            <select v-model="selectedEndHour" @change="onEndTimeChange"
                                    class="block w-[4rem] text-center p-2 pr-8 bg-white border border-gray-300 rounded-md focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-300 appearance-none">
                                <option v-for="hour in hours" :key="hour" :value="hour">{{ hour }}</option>
                            </select>
                            <select v-model="selectedEndMinute" @change="onEndTimeChange"
                                    class="block w-[4rem] text-center p-2 pr-8 bg-white border border-gray-300 rounded-md focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-300 appearance-none">
                                <option v-for="minute in minutes" :key="minute" :value="minute">{{ minute }}</option>
                            </select>
                        </div>
                    </div>
                </transition>
            </div>
        </div>
    </div>
</template>
