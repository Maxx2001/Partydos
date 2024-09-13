<script setup>
import googleCalendarLink from "@/Helpers/agendaLinkHelpers.js";
import { defineProps } from "vue";
import BaseCalendarLink from "@/Components/Base/BaseCalendarLink.vue";

const props = defineProps({
    event: {
        type: Object,
        required: true
    }
});

const { isoStartDateTime, isoEndDateTime, title, description, location } = props.event;
const fullGoogleCalendarLink = googleCalendarLink(isoStartDateTime, isoEndDateTime, title, description, location);

const calendarLink = `/event/${props.event.id}/download-ics`;
</script>

<template>
    <div class="w-full lg:w-1/2 border bg-white mx-4">
        <div class="flex flex-col justify-center w-full items-center bg-white py-16">
             <span class="text-xl pb-2">
                    Add to your calendar:
            </span>
            <div class="w-10/12 grid grid-cols-2 lg:grid-cols-4 gap-3">
                <BaseCalendarLink :url="fullGoogleCalendarLink" label="Google"/>
                <BaseCalendarLink :url="calendarLink" label="Apple"/>
                <BaseCalendarLink :url="calendarLink" label="Outlook"/>
                <BaseCalendarLink :url="calendarLink" label="Other"/>
            </div>

        </div>
    </div>
</template>
