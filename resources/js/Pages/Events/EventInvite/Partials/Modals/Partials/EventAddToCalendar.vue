<script setup>
import { defineProps } from "vue";
import GoogleCalendarButton
    from "@/Pages/Events/EventInvite/Partials/Modals/Partials/CalendarButtons/GoogleCalendarButton.vue";
import AppleCalendarButton
    from "@/Pages/Events/EventInvite/Partials/Modals/Partials/CalendarButtons/AppleCalendarButton.vue";
import OutlookCalendarButton
    from "@/Pages/Events/EventInvite/Partials/Modals/Partials/CalendarButtons/OutlookCalendarButton.vue";
import CalendarButton from "@/Pages/Events/EventInvite/Partials/Modals/Partials/CalendarButtons/CalendarButton.vue";

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
                    <GoogleCalendarButton :url="event.googleCalendarLink"/>
                    <AppleCalendarButton :url="calendarLink"/>
                    <OutlookCalendarButton :url="calendarLink"/>
                    <CalendarButton :url="calendarLink"/>
                </div>
            </div>
        </div>
    </div>
</template>
