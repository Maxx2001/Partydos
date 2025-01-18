<script setup>
import { Link } from '@inertiajs/vue3';
import {computed} from "vue";

const props = defineProps({
    event: {
        type: Object,
        required: true
    },
    index: {
        type: Number,
        required: true
    }
});

const textColor = "text-white";

const gradientColors = [
    ["from-blue-600", "to-blue-400"],
    ["from-green-600", "to-green-400"],
    ["from-yellow-600", "to-yellow-400"],
    ["from-purple-600", "to-purple-400"],
    ["from-pink-600", "to-pink-400"],
    ["from-red-600", "to-red-400"],
    ["from-indigo-600", "to-indigo-400"],
    ["from-teal-600", "to-teal-400"],
    ["from-orange-600", "to-orange-400"],
    ["from-gray-600", "to-gray-400"]
];

const gradientForEvent = computed(() => {
    return gradientColors[props.index];
});

</script>

<template>
    <li :class="`relative rounded-lg bg-gradient-to-br ${gradientForEvent[0]} ${gradientForEvent[1]} shadow-md hover:shadow-lg transition-shadow`">
        <Link :href="route('guest-events.create')" class="block p-4">
            <div class="flex justify-between">
                <h3 :class="`text-lg font-semibold ${textColor}`">{{ event.title }}</h3>
            </div>
            <p :class="`${textColor} text-sm mt-2 flex items-center py-2`">
                {{ event.description}}
            </p>
        </Link>
    </li>
</template>
