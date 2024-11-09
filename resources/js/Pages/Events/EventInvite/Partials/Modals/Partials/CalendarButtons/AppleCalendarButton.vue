<script setup>
import {defineProps} from 'vue'
import AppleCalendarIcon from "@/Assets/Icons/AppleCalendarIcon.vue";

const props = defineProps({
    url: {
        type: String,
        required: true,
    },
})
</script>

<template>
    <a
        :href="url"
        target="_blank"
        class="border p-2 rounded bg-green-500 hover:bg-green-700 flex justify-center items-center text-white text-xl font-bold h-40 w-full"
    >
        <div class="flex flex-col">
            <AppleCalendarIcon class="mt-5" />
            <span class="text-black text-center pb-3 -pt-8">
                Apple
            </span>
        </div>
    </a>
</template>
