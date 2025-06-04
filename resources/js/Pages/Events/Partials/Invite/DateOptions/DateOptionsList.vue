<script setup>
import {defineProps} from 'vue';
import {router} from '@inertiajs/vue3';

const props = defineProps({
    event: Object,
    options: Array
});

const vote = (id) => {
    router.post(route('event.date-option.vote', {event: props.event.uniqueIdentifier, option: id}), {}, {preserveScroll: true});
};
</script>

<template>
    <div class="space-y-2">
        <div v-for="option in options" :key="option.id" class="flex justify-between items-center border p-2 rounded">
            <span>{{ option.formattedDate }} {{ option.formattedTime }}</span>
            <div class="flex items-center space-x-2">
                <span class="text-sm text-gray-600">{{ option.votes }}</span>
                <button class="px-3 py-1 bg-indigo-600 text-white rounded" @click="vote(option.id)">Vote</button>
            </div>
        </div>
    </div>
</template>
