<script setup>
import DefaultLayout from "@/Layouts/DefaultLayout.vue";
import EventInviteDetails from "@/Pages/Events/Partials/EventInviteDetails.vue";
import EventRegisterForm from "@/Pages/Events/Partials/EventRegisterForm.vue";
import EventParticipantsList from "@/Pages/Events/Partials/EventParticipantsList.vue";
import EventAddToCalendar from "@/Pages/Events/Partials/EventAddToCalendar.vue";
import { defineProps, ref } from "vue";

const props = defineProps({
    event: {
        type: Object,
        required: true
    }
});

const moveEventRegisterDown = ref(false);
const eventOwner = ref(props.event.eventOwner);

</script>

<template>
    <DefaultLayout>
        <div class="py-16 md:py-24 px-8 lg:px-0 bg-slate-100">
            <div class="flex flex-col items-center">
                <EventInviteDetails :event="event"/>
            </div>
            <div
                :class="moveEventRegisterDown ? 'flex-col-reverse' : 'flex-col'"
                class="flex flex-col items-center"
            >
                <EventRegisterForm :event="event" @register-success="moveEventRegisterDown = true"/>
                <EventParticipantsList
                    :participants="event.participants"
                    :eventOwner="eventOwner"
                />
            </div>
            <div class="flex flex-col items-center">
                <EventAddToCalendar :event="event" class="mt-2"/>
            </div>
        </div>
    </DefaultLayout>
</template>
