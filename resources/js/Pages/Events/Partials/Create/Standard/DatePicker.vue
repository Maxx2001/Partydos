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
const isSelected = (day) => {
    return props.selectedDates.some(selected =>
        isSameDay(selected, day)
    );
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
    </div>
</template>
