<script setup>
defineProps({
    participants: {
        type: Array,
        default: () => []
    }
});
</script>

<template>
    <div class="w-full lg:w-1/2 border bg-white mt-2">
        <div class="flex flex-col justify-center w-full items-center bg-white py-8">
            <span class="text-xl pb-2">
               Participants
            </span>
            <ul class="w-10/12">
                <li v-for="participant in participants">
                    - {{ participant.name }}
                </li>
            </ul>
        </div>

    </div>
</template>
