<script setup>
import DefaultLayout from "@/Layouts/DefaultLayout.vue";
import EventInviteBanner from "@/Pages/Events/EventInvite/Partials/EventInviteBanner.vue";
import EventParticipantsList from "@/Pages/Events/EventInvite/Partials/EventParticipantsList.vue";
import {defineProps, onMounted, ref, toRefs} from "vue";
import {useTitle} from "@/Composables/useTitle.js";
import EventInviteHero from "@/Pages/Events/EventInvite/Partials/EventInviteHero.vue";
import AOS from "aos";
import "aos/dist/aos.css";
import EventRegisterModal from "@/Pages/Events/EventInvite/Partials/Modals/EventRegisterModal.vue";
import EventAddToCalendarModel from "@/Pages/Events/EventInvite/Partials/Modals/EventAddToCalendarModel.vue";
import EventInviteLinkeModal from "@/Pages/Events/EventInvite/Partials/Modals/EventInviteLinkeModal.vue";
import EventDescription from "@/Pages/Events/EventInvite/Partials/EventDescription.vue";

const props = defineProps({
    event: {
        type: Object,
        required: true
    },
    showInviteModal: {
        type: Boolean,
        required: false,
    },
    showInviteButton: {
        type: Boolean,
        default: true,
    },
});

useTitle(`Invite to ${props.event.title}`);

const { showInviteModal } = toRefs(props);

const moveEventRegisterDown = ref(false);

const eventRegisterModal = ref('eventRegisterModal');
const eventAddToCalendarModel = ref('eventAddToCalendarModel');
const eventInviteLineModel = ref('eventInviteLineModel');

onMounted(() => {
    AOS.init();

    if (showInviteModal.value) {
        eventInviteLineModel.value?.openModal()
    }
});

</script>

<template>
    <DefaultLayout>
        <div class="md:px-8 lg:px-0 bg-slate-100">
            <EventInviteHero
                :event="event"
                @accept-event-invite="eventRegisterModal.openModal()"
                :show-invite-button="showInviteButton"
            />

            <EventInviteBanner
                :event="event"
                @accept-event-invite="eventRegisterModal.openModal()"
                :show-invite-button="showInviteButton"
                class="hidden md:flex"
            />

            <EventParticipantsList
                class="pb-6 lg:pb-24"
                :invited-users="event.invitedUsers"
                :eventOwner="event.eventOwner"
                :moveEventRegisterDown="moveEventRegisterDown"
                @open-add-to-calendar-modal="eventAddToCalendarModel.openModal()"
            />

            <EventDescription :event="event" class="pb-12"/>
        </div>

        <EventRegisterModal
            :event="event"
            ref="eventRegisterModal"
        />

        <EventAddToCalendarModel
            :event="event"
            ref="eventAddToCalendarModel"
        />

        <EventInviteLinkeModal
            :event="event"
            ref="eventInviteLineModel"
        />
    </DefaultLayout>
</template>
