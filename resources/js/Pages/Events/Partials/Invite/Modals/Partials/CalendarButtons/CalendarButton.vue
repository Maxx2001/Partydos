<script setup>
import {defineProps} from 'vue'
import GoogleCalendarIcon from "@/Assets/Icons/GoogleCalendarIcon.vue";
import OutlookCalendarIcon from "@/Assets/Icons/OutlookCalendarIcon.vue";
import CalendarIcon from "@/Assets/Icons/CalendarIcon.vue";

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
        class="border p-2 rounded bg-purple-100 hover:bg-purple-300 flex justify-center items-center text-white text-xl font-bold h-40 w-full"
    >
        <div class="flex flex-col">
            <CalendarIcon class="h-20 w-20 mt-2"/>
            <span class="text-black text-center pb-3 text-base">
                    Other
            </span>
        </div>
    </a>
</template>
