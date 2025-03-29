<script setup>
import { computed } from 'vue';
import { format, parseISO } from 'date-fns';
import BaseOutlineButton from '@/Components/Base/BaseOutlineButton.vue';

const props = defineProps({
    options: {
        type: Array,
        required: true,
    },
});

const emits = defineEmits(['removeOption']);

const displayDate = (dateString) => {
    return format(parseISO(dateString), 'EEEE, dd MMM yyyy');
};
</script>

<template>
    <div v-if="options.length" class="p-4 bg-white rounded-lg shadow space-y-4">
        <h2 class="text-lg font-semibold">Date Options</h2>

        <ul class="space-y-2">
            <li v-for="(option, index) in options" :key="index" class="flex justify-between items-center border p-2 rounded">
                <div>
                    <div class="font-medium">{{ displayDate(option.date) }}</div>
                    <div v-if="option.time" class="text-sm text-gray-600">Time: {{ option.time }}</div>
                    <div v-else class="text-sm text-gray-400 italic">No time selected</div>
                </div>
                <BaseOutlineButton label="Remove" @click="emits('removeOption', index)" />
            </li>
        </ul>
    </div>
</template>
