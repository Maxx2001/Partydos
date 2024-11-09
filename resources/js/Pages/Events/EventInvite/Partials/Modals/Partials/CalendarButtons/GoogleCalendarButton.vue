<script setup>
import {defineProps} from 'vue'
import GoogleCalendarIcon from "@/Assets/Icons/GoogleCalendarIcon.vue";

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
        class="border p-2 rounded bg-indigo-100 hover:bg-indigo-300 flex justify-center items-center text-white text-xl font-bold h-40 w-full"
    >
        <div class="flex flex-col">
            <GoogleCalendarIcon class="h-32 w-32"/>
            <span class="text-black text-center pb-3 -mt-3">
                    Google
            </span>
        </div>
    </a>
</template>
