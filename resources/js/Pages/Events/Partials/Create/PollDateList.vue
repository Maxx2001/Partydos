<script setup>
import { format, parseISO } from 'date-fns';
import BaseOutlineButton from '@/Components/Base/BaseOutlineButton.vue';

const props = defineProps({
    options: {
        type: Array,
        required: true,
    },
});

const emits = defineEmits(['removeOption']);

const displayDate = (option) => {
    if (!option || !option.selectedDate) {
        return 'Invalid date';
    }
    try {
        const date = new Date(option.selectedDate);
        return  format(date, 'EEEE, dd MMM yyyy');
        // const formattedDate = format(date, 'EEEE, dd MMM yyyy');
        // const startTime = `${option.selectedHour}:${option.selectedMinute}`;
        // const endTime = option.selectedEndHour && option.selectedEndMinute ? `${option.selectedEndHour}:${option.selectedEndMinute}` : 'No end time';
        // return `${formattedDate} from ${startTime} to ${endTime}`;
    } catch (error) {
        return 'Invalid date';
    }
};

const displayTime = (option) => {
    if (!option || !option.selectedHour || !option.selectedMinute) {
        return 'No time selected';
    }
    const startTime = `${option.selectedHour}:${option.selectedMinute}`;
    const endTime = option.selectedEndHour && option.selectedEndMinute ? `${option.selectedEndHour}:${option.selectedEndMinute}` : 'No end time';
    return `From ${startTime} to ${endTime}`;
};
</script>

<template>
    <div v-if="options.length" class="p-4 bg-white rounded-lg shadow space-y-4">
        <h2 class="text-lg font-semibold">Date Options</h2>

        <ul class="space-y-2">
            <li v-for="(option, index) in options" :key="index" class="flex justify-between items-center border p-2 rounded">
                <div>
                    <div class="font-medium">{{ displayDate(option) }}</div>    
                    <div v-if="option.selectedHour && option.selectedMinute" class="text-sm text-gray-600">
                        From: {{ option.selectedHour }}:{{ option.selectedMinute }} 
                        <span v-if="option.selectedEndHour && option.selectedEndMinute">
                            to {{ option.selectedEndHour }}:{{ option.selectedEndMinute }}
                        </span>
                    </div>
                    <div v-else class="text-sm text-gray-400 italic">No time selected</div>
                </div>
                <BaseOutlineButton label="Remove" @click="emits('removeOption', index)" />
            </li>
        </ul>
    </div>
</template>
