<script setup>
import DefaultLayout from "@/Layouts/DefaultLayout.vue";
import EventInviteDetails from "@/Pages/Events/EventInvite/Partials/EventInviteDetails.vue";
import EventRegisterForm from "@/Pages/Events/EventInvite/Partials/EventRegisterForm.vue";
import EventParticipantsList from "@/Pages/Events/EventInvite/Partials/EventParticipantsList.vue";
import EventAddToCalendar from "@/Pages/Events/EventInvite/Partials/EventAddToCalendar.vue";
import BaseModal from "@/Components/Base/BaseModal.vue";
import {defineProps, ref, toRefs} from "vue";
import SuccesMessage from "@/Components/Messages/SuccesMessage.vue";
import {useTitle} from "@/Composables/useTitle.js";
import InviteLink from "@/Pages/Events/EventCreate/Partials/InviteLink.vue";
import EventInviteLinkModal from "@/Pages/Events/EventInvite/Partials/EventInviteLinkModal.vue";
import Hero from "@/Pages/Events/EventInvite/Partials/Hero.vue";

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
const openInviteModal = ref(showInviteModal.value)
const moveEventRegisterDown = ref(false);
const eventOwner = ref(props.event.eventOwner);
const eventRegisterSuccess = ref(false);

// Reference to the EventRegisterForm component
const eventRegisterFormRef = ref(null);

const handleConfirm = () => {
    // Call the exposed method from EventRegisterForm
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

import heroImage from "@/Assets/heroImage.webp";

import AOS from "aos";
import "aos/dist/aos.css";
AOS.init();
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

            <Hero :event="event" @accept-event-invite="showModal = true"/>
            <EventInviteDetails
                :event="event"
                @accept-event-invite="showModal = true"
                class="hidden md:flex"
            />

            <div :class="moveEventRegisterDown ? 'flex-col-reverse' : 'flex-col'" class="flex flex-col items-center">
                <EventParticipantsList :participants="event.participants" :eventOwner="eventOwner" />
            </div>
            <div class="flex flex-col items-center mt-3">
                <EventAddToCalendar :event="event" class="mt-2" />
            </div>
            <div class="flex flex-col items-center mt-3">
                <div class="md:w-1/2">
                    <InviteLink :event="event"/>
                </div>
            </div>
        </div>

        <!-- Modal for event registration -->
        <BaseModal
            :isVisible="showModal"
            @close="handleClose"
            @confirm="handleConfirm"
            title="Sign up for the event"
        >
            <EventRegisterForm ref="eventRegisterFormRef" :event="event" @register-success="handleRegisterSuccess"/>
        </BaseModal>
        <EventInviteLinkModal
            :event="event"
            :isVisible="openInviteModal"
            @confirm="handleShowInviteModalClose"
            @close="handleShowInviteModalClose"
        />
    </DefaultLayout>
</template>
