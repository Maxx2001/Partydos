<script setup>
import { ref } from 'vue';
import { format } from 'date-fns';
import BaseButton from '@/Components/Base/BaseButton.vue';

const emits = defineEmits(['addDateOption']);

const selectedDate = ref('');
const enableTime = ref(false);
const selectedHour = ref('12');
const selectedMinute = ref('00');

const hours = Array.from({ length: 24 }, (_, i) => i.toString().padStart(2, '0'));
const minutes = Array.from({ length: 60 }, (_, i) => i.toString().padStart(2, '0'));

const resetFields = () => {
    selectedDate.value = '';
    enableTime.value = false;
    selectedHour.value = '12';
    selectedMinute.value = '00';
};

const addOption = () => {
    if (!selectedDate.value) return;

    const option = {
        date: selectedDate.value,
        time: enableTime.value ? `${selectedHour.value}:${selectedMinute.value}` : null,
    };

    emits('addDateOption', option);
    resetFields();
};
</script>

<template>
    <div class="flex flex-col space-y-4 p-4 bg-white rounded-lg shadow">
        <h2 class="text-lg font-semibold">Add a Date Option</h2>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Select a date</label>
            <input type="date" v-model="selectedDate"
                   class="w-full p-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-300" />
        </div>

        <div class="flex items-center space-x-2">
            <input id="enableTime" type="checkbox" v-model="enableTime"
                   class="rounded h-4 w-4 text-blue-600 border-gray-300 focus:outline-none cursor-pointer" />
            <label for="enableTime" class="text-sm text-gray-700 cursor-pointer">Add a time?</label>
        </div>

        <div v-if="enableTime" class="flex space-x-4">
            <select v-model="selectedHour"
                    class="block w-[4rem] text-center p-2 pr-8 bg-white border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-300 appearance-none">
                <option v-for="hour in hours" :key="hour" :value="hour">{{ hour }}</option>
            </select>

            <select v-model="selectedMinute"
                    class="block w-[4rem] text-center p-2 pr-8 bg-white border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-300 appearance-none">
                <option v-for="minute in minutes" :key="minute" :value="minute">{{ minute }}</option>
            </select>
        </div>

        <BaseButton label="Add Option" @click="addOption" :disabled="!selectedDate" />
    </div>
</template>
