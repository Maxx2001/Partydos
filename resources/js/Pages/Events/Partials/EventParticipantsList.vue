<script setup>
defineProps({
    participants: {
        type: Array,
        default: () => []
    },
    eventOwner: {
        type: Object,
        required: true
    }
});
</script>

<template>
    <div class="w-full lg:w-1/2 shadow-lg rounded-lg bg-white mt-2">
        <div class="flex flex-col justify-center w-full items-center bg-white py-8">
            <span class="text-xl pb-2">
               Participants
            </span>
            <ul class="w-10/12">
                <li>
                    - {{ eventOwner.name }} (Event Owner)
                </li>
                <li v-for="participant in participants">
                    - {{ participant.name }}
                </li>
            </ul>
        </div>

    </div>
</template>
