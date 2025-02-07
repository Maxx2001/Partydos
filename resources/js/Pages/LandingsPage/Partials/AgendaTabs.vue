<script setup>
import AppleCalendarIcon from "@/Assets/Icons/AppleCalendarIcon.vue";
import GoogleCalendarIcon from "@/Assets/Icons/GoogleCalendarIcon.vue";
import OutlookCalendarIcon from "@/Assets/Icons/OutlookCalendarIcon.vue";
</script>

<template>
    <div class="flex flex-col items-center justify-center py-16 px-6 bg-gradient-to-b from-blue-50 to-white border">
        <h1 class="text-2xl md:text-4xl font-bold pb-12 text-gray-900 text-center">
            Easily add events to your calendar!
        </h1>
        <div
            class="w-full max-w-2xl bg-white rounded-lg shadow-lg overflow-hidden"
        >
            <div class="grid grid-cols-3 divide-x divide-gray-200 my-4">
                <div class="flex justify-center">
                    <AppleCalendarIcon  class="h-24 w-24"/>
                </div>
                <div class="flex justify-center">
                    <GoogleCalendarIcon class="h-24 w-24"/>
                </div>
                <div class="flex justify-center">
                    <OutlookCalendarIcon class="h-24 w-24"/>
                </div>
            </div>
        </div>
    </div>
</template>
