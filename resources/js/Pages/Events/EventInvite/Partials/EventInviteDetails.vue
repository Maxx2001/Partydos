<script setup>
import {defineProps} from "vue";
import EventAddToCalendar from "@/Pages/Events/EventInvite/Partials/EventAddToCalendar.vue";

const props = defineProps({
    event: {
        type: Object,
        required: true
    },
});
</script>

<template>
    <div class="flex items-center justify-center">
        <div class="max-w-6xl py-6 lg:py-12 flex-row w-full grid lg:grid-cols-2 lg:text-4xl text-2xl text-indigo-600 font-semibold">
            <div class="flex flex-col items-center">
                <span>
                    {{  event.formattedDate }}
                </span>
            </div>
            <div class="flex flex-col items-center">
                <p>
                    {{ event.formattedTime }}
                </p>
            </div>
      </div>
    </div>
</template>
