<script setup>
import {defineProps} from "vue";
import BaseButton from "@/Components/Base/BaseButton.vue";

const props = defineProps({
    event: {
        type: Object,
        required: true
    },
});

const emit = defineEmits(['openAddToCalendarModal']);
</script>

<template>
    <div class="flex items-center justify-center">
        <div class="max-w-6xl py-6 px-12 lg:px-0 lg:py-12 flex-row w-full grid grid-cols-2 lg:grid-cols-3 lg:text-4xl text-2xl text-indigo-600 font-semibold">
            <div class="flex flex-col items-center justify-center">
                <span>
                    {{  event.formattedDate }}
                </span>
            </div>
            <div class="flex flex-col items-center justify-center">
                <p>
                    {{ event.formattedTime }}
                </p>
            </div>
            <div class="flex flex-col items-center lg:justify-center col-span-2 lg:col-span-1 mt-4 lg:mt-0">
                <span class="text-2xl">
                    {{ event.title }}
                </span>
                <BaseButton label="Add to Calendar" @click="emit('openAddToCalendarModal')" extraClasses="text-xl" class="mt-3"/>
            </div>
      </div>
    </div>
</template>
