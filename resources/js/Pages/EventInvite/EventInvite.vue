<script setup>
import DefaultLayout from "@/Layouts/DefaultLayout.vue";
import EventInviteDetails from "@/Pages/EventInvite/Partials/EventInviteDetails.vue";
import EventRegisterForm from "@/Pages/EventInvite/Partials/EventRegisterForm.vue";
import EventParticipantsList from "@/Pages/EventInvite/Partials/EventParticipantsList.vue";
import EventAddToCalendar from "@/Pages/EventInvite/Partials/EventAddToCalendar.vue";
import { defineProps, ref } from "vue";

const props = defineProps({
    event: {
        type: Object,
        required: true
    }
});

const moveEventRegisterDown = ref(false);

</script>

<template>
    <DefaultLayout>
        <div class="py-16 md:py-24 px-8 lg:px-0 bg-slate-100">
            <div class="flex flex-col items-center">
                <EventInviteDetails :event="event.data"/>
                <EventAddToCalendar :event="event" class="mt-2"/>

            </div>
            <div
                :class="moveEventRegisterDown ? 'flex-col-reverse' : 'flex-col'"
                class="flex flex-col items-center"
            >
                <EventRegisterForm :event="event.data" @register-success="moveEventRegisterDown = true"/>
                <EventParticipantsList
                    :participants="event.data.participants"
                    :eventOwner="event.data.guestUserEventOwner"
                />
            </div>
        </div>
    </DefaultLayout>
</template>
