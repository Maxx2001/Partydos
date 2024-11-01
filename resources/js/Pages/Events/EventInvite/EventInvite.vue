<script setup>
import DefaultLayout from "@/Layouts/DefaultLayout.vue";
import EventInviteBanner from "@/Pages/Events/EventInvite/Partials/EventInviteBanner.vue";
import EventRegisterForm from "@/Pages/Events/EventInvite/Partials/EventRegisterForm.vue";
import EventParticipantsList from "@/Pages/Events/EventInvite/Partials/EventParticipantsList.vue";
import EventAddToCalendar from "@/Pages/Events/EventInvite/Partials/EventAddToCalendar.vue";
import BaseModal from "@/Components/Base/BaseModal.vue";
import {defineProps, ref, toRefs} from "vue";
import SuccesMessage from "@/Components/Messages/SuccesMessage.vue";
import {useTitle} from "@/Composables/useTitle.js";
import EventInviteHero from "@/Pages/Events/EventInvite/Partials/EventInviteHero.vue";
import AOS from "aos";
import "aos/dist/aos.css";
import EventInviteDetails from "@/Pages/Events/EventInvite/Partials/EventInviteDetails.vue";
import InviteLink from "@/Pages/Events/EventCreate/Partials/InviteLink.vue";

const props = defineProps({
    event: {
        type: Object,
        required: true
    },
    showInviteModal: {
        type: Boolean,
        required: false,
    }
});

useTitle(`Invite to ${props.event.title}`);

const { showInviteModal } = toRefs(props);


const showModal = ref(false);
// const openInviteModal = ref(showInviteModal.value)
const openInviteModal = ref(true)
const openAddToCalendarModal = ref(false);
const moveEventRegisterDown = ref(false);
const eventOwner = ref(props.event.eventOwner);
const eventRegisterSuccess = ref(false);

const eventRegisterFormRef = ref(null);

const handleConfirm = () => {
    if (eventRegisterFormRef.value) {
        eventRegisterFormRef.value.submitRegisterForm();
    }
};

const handleClose = () => {
    showModal.value = false;
}

const handleShowInviteModalClose = () => {
    openInviteModal.value = false;
}

const handleRegisterSuccess = () => {
    showModal.value = false;
    eventRegisterSuccess.value = true;
}


AOS.init();
const copied = ref(false);
const copyToClipboard = async() => {
    try {
        await navigator.clipboard.writeText(props.event.shareLink);
        copied.value = true;

        setTimeout(() => {
            copied.value = false;
        }, 2000);
    } catch(err) {
        console.error('Failed to copy: ', err);
    }
}
</script>

<template>
    <DefaultLayout>
        <div class="md:px-8 lg:px-0 bg-slate-100">
            <div class="flex flex-col items-center">
                <div class="w-full lg:w-1/2">
                    <SuccesMessage
                        message="You have successfully registered for this event!"
                        v-if="eventRegisterSuccess"
                        @closeMessage="eventRegisterSuccess = false"
                    />
                </div>
            </div>

            <EventInviteHero
                :event="event"
                @accept-event-invite="showModal = true"
            />

            <EventInviteBanner
                :event="event"
                @accept-event-invite="showModal = true"
                class="hidden md:flex"
            />

            <EventParticipantsList
                :guest-users="event.guestUsers"
                :eventOwner="eventOwner"
                :moveEventRegisterDown="moveEventRegisterDown"
            />

            <EventInviteDetails :event="event" @open-add-to-calendar-modal="openAddToCalendarModal = true"/>

        </div>

        <BaseModal
            :isVisible="showModal"
            @close="showModal = false"
            @confirm="handleConfirm"
            title="Join this event!"
        >
            <EventRegisterForm ref="eventRegisterFormRef" :event="event" @register-success="handleRegisterSuccess"/>
        </BaseModal>

        <BaseModal
            :isVisible="openAddToCalendarModal"
            @close="openAddToCalendarModal = false"
            :show-submit-button="false"
            title="Add event to calendar"
            cancel-button-label="Close"
        >
            <EventAddToCalendar :event="event"/>
        </BaseModal>

        <BaseModal
            :isVisible="openInviteModal"
            @close="handleShowInviteModalClose"
            :show-submit-button="false"
            @confirm="handleShowInviteModalClose"
            cancel-button-label="Close"
            title="Invite your friends"
        >
        <InviteLink :event="event"/>
        </BaseModal>

    </DefaultLayout>
</template>
