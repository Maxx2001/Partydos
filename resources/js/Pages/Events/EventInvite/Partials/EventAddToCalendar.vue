<script setup>
import { defineProps } from "vue";
import BaseCalendarLink from "@/Components/Base/BaseCalendarLink.vue";

const props = defineProps({
    event: {
        type: Object,
        required: true
    }
});

const { title, description, location } = props.event;
const calendarLink = `/event/${props.event.id}/download-ics`;
</script>

<template>
    <div class="flex flex-col items-center py-2">
        <div class="w-full mx-4">
            <div class="flex flex-col justify-center w-full items-center">
                <div class="lg:w-10/12 w-full grid grid-cols-2 gap-3">
                    <BaseCalendarLink
                        :url="event.googleCalendarLink"
                        label="Google"
                        backgroundColor="bg-blue-500"
                        hoverColor="hover:bg-blue-700"
                    />
                    <BaseCalendarLink
                        :url="calendarLink"
                        label="Apple"
                        backgroundColor="bg-green-500"
                        hoverColor="hover:bg-green-700"
                    />
                    <BaseCalendarLink
                        :url="calendarLink"
                        label="Outlook"
                        backgroundColor="bg-orange-500"
                        hoverColor="hover:bg-orange-700"
                    />
                    <BaseCalendarLink
                        :url="calendarLink"
                        label="Other"
                        backgroundColor="bg-purple-500"
                        hoverColor="hover:bg-purple-700"
                    />
                </div>
            </div>
        </div>
    </div>
</template>
