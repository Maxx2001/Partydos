<script setup>
import { ref, computed } from 'vue'
import { ChevronLeftIcon, ChevronRightIcon } from '@heroicons/vue/20/solid'
import {
    format,
    addMonths,
    subMonths,
    startOfMonth,
    endOfMonth,
    eachDayOfInterval,
    isSameMonth,
    isSameDay,
    isToday,
} from 'date-fns'

// State Management with ref
const currentMonth = ref(new Date())
const selectedDate = ref(new Date())

// Days of the week for the calendar header
const daysOfWeek = ['SUN', 'MON', 'TUE', 'WED', 'THU', 'FRI', 'SAT']

// Method to select a date
const onDateSelect = (day) => {
    selectedDate.value = day
}

// Computed property to determine the class for each day in the calendar
const dayClass = (day) => {
    return [
        !isSameMonth(day, currentMonth.value) && 'text-gray-400',
        isSameDay(day, selectedDate.value) && 'bg-blue-500 text-white',
        isToday(day) && !isSameDay(day, selectedDate.value) && 'border border-blue-500',
    ]
}

// Computed property to generate calendar rows
const calendarRows = computed(() => {
    const monthStart = startOfMonth(currentMonth.value)
    const monthEnd = endOfMonth(monthStart)
    const days = eachDayOfInterval({ start: monthStart, end: monthEnd })
    const rows = []

    // Generate weeks in the current month
    for (let i = 0; i < days.length; i += 7) {
        const row = days.slice(i, i + 7).map((date) => ({
            date,
            formatted: format(date, 'd'),
        }))
        rows.push(row)
    }

    return rows
})

// Method to move to the next month
const nextMonth = () => {
    currentMonth.value = addMonths(currentMonth.value, 1)
}

// Method to move to the previous month
const prevMonth = () => {
    currentMonth.value = subMonths(currentMonth.value, 1)
}
</script>

<style scoped>
.button {
    background: transparent;
    border: none;
    padding: 0;
    cursor: pointer;
}
</style>

<template>
    <div class="w-[300px] bg-white shadow-lg rounded-lg overflow-hidden">
        <div class="p-0">
            <!-- Header for Month Navigation -->
            <div class="flex justify-between items-center bg-gray-100 px-4 py-2">
                <button @click="prevMonth" class="bg-transparent p-2 rounded-full">
                    <ChevronLeftIcon class="h-4 w-4" />
                </button>
                <h2 class="text-lg font-semibold">{{ format(currentMonth, 'MMMM') }}</h2>
                <button @click="nextMonth" class="bg-transparent p-2 rounded-full">
                    <ChevronRightIcon class="h-4 w-4" />
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
                            class="h-8 w-8 text-center text-sm leading-8 rounded-full cursor-pointer"
                        >
                            <span>{{ day.formatted }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Selected Date Display -->
        <div class="flex justify-center items-center p-4 bg-gray-100">
            <div class="text-4xl font-bold">
                {{ format(selectedDate, 'dd') }}
                <span class="text-xl ml-2">{{ format(selectedDate, 'HH') }}</span>
            </div>
        </div>
    </div>
</template>


