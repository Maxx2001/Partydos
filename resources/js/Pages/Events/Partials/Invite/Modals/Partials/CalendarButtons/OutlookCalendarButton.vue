<script setup>
import {defineProps} from 'vue'
import GoogleCalendarIcon from "@/Assets/Icons/GoogleCalendarIcon.vue";
import OutlookCalendarIcon from "@/Assets/Icons/OutlookCalendarIcon.vue";

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
            <OutlookCalendarIcon class="h-20 w-20"/>
            <span class="text-black text-center pb-3 -pt-8 text-base">
                Outlook
            </span>
        </div>
    </a>
</template>
