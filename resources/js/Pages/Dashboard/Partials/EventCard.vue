<script setup>
import { ref } from "vue";

const props = defineProps({
    event: {
        type: Object,
        required: true
    }
});
import { Link } from '@inertiajs/vue3'

const uniqueIdentifier = ref(props.event.uniqueIdentifier);
</script>

<template>
    <li class="rounded-lg bg-gradient-to-br from-purple-100 to-blue-50 shadow-md hover:shadow-lg transition-shadow">
        <Link :href="route('events.show-invite', { event: uniqueIdentifier })" class="block p-4">
            <h3 class="text-lg font-semibold text-blue-600">{{ event.title }}</h3>
            <p class="text-gray-700 text-sm mt-2">
                <span class="font-medium">Location:</span> {{ event.location }}
            </p>
            <p class="text-gray-500 text-xs mt-1">{{ event.date | formatDate }}</p>
        </Link>
    </li>
</template>
