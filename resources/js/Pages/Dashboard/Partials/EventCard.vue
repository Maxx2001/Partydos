<script setup>
import { ref, computed } from "vue";
import { getFormattedEventDateMessage } from "@/Helpers/getFormattedEventDateMessage";
import { MapPinIcon, UserGroupIcon, CalendarDaysIcon, PencilSquareIcon } from "@heroicons/vue/20/solid";
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    event: {
        type: Object,
        required: true
    },
    canEdit: {
        type: Boolean,
        default: false
    }
});

const uniqueIdentifier = ref(props.event.uniqueIdentifier);

const gradientColors = [
    ["from-red-600", "to-red-400"],
    ["from-blue-600", "to-blue-400"],
    ["from-green-600", "to-green-400"],
    ["from-yellow-600", "to-yellow-400"],
    ["from-purple-600", "to-purple-400"],
    ["from-pink-600", "to-pink-400"],
    ["from-indigo-600", "to-indigo-400"],
    ["from-teal-600", "to-teal-400"],
    ["from-orange-600", "to-orange-400"],
    ["from-gray-600", "to-gray-400"]
];

const textColor = "text-white";

const hashToIndex = (identifier, arrayLength) => {
    let hash = 0;
    for (let i = 0; i < identifier.length; i++) {
        hash = (hash << 5) - hash + identifier.charCodeAt(i);
        hash |= 0; // Convert to 32-bit integer
    }
    return Math.abs(hash) % arrayLength;
};

const gradientForEvent = computed(() => {
    const index = hashToIndex(uniqueIdentifier.value, gradientColors.length);
    return gradientColors[index];
});
const eventLink = () => props.canEdit ? route('events.edit', { event: uniqueIdentifier.value }) : route('events.show-invite', { event: uniqueIdentifier.value });
</script>

<template>
    <li :class="`rounded-lg bg-gradient-to-br ${gradientForEvent[0]} ${gradientForEvent[1]} shadow-md hover:shadow-lg transition-shadow`">
        <Link :href="eventLink()" class="block p-4">
            <div class="flex justify-between">
                <h3 :class="`text-lg font-semibold ${textColor}`">{{ event.title }}</h3>
                <PencilSquareIcon v-if="canEdit" :class="`${textColor} h-6`"/>
            </div>
            <p :class="`${textColor} text-sm mt-2 flex items-center`">
                <MapPinIcon :class="`h-5 ${textColor} pr-1`"/>
                <span v-if="event.location">
                    {{ event.location }}
                </span>
                <span v-else>
                    No location set
                </span>
            </p>
            <p :class="`${textColor} text-sm mt-2 flex items-center`">
                <CalendarDaysIcon :class="`h-5 ${textColor} pr-1`"/>
                {{ getFormattedEventDateMessage(event) }}
            </p>
            <p :class="`${textColor} text-sm mt-2 flex items-center`">
                <UserGroupIcon :class="`h-5 ${textColor} pr-1`"/>
                {{ event.invitedUsers.length }}
            </p>
        </Link>
    </li>
</template>
