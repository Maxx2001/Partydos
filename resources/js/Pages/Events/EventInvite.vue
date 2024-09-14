<script setup>
import DefaultLayout from "@/Layouts/DefaultLayout.vue";
import EventInviteDetails from "@/Pages/Events/Partials/EventInviteDetails.vue";
import EventRegisterForm from "@/Pages/Events/Partials/EventRegisterForm.vue";
import EventParticipantsList from "@/Pages/Events/Partials/EventParticipantsList.vue";
import EventAddToCalendar from "@/Pages/Events/Partials/EventAddToCalendar.vue";
import BaseModal from "@/Components/Base/BaseModal.vue";
import { defineProps, ref } from "vue";
import SuccesMessage from "@/Components/Messages/SuccesMessage.vue";
import {useTitle} from "@/composables/useTitle.js";

const props = defineProps({
    event: {
        type: Object,
        required: true
    }
});

useTitle(`Invite to ${props.event.title}`);


const showModal = ref(false);
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

const handleRegisterSuccess = () => {
    showModal.value = false;
    eventRegisterSuccess.value = true;
}
</script>

<template>
    <DefaultLayout>
        <div class="py-8 md:py-12 px-8 lg:px-0 bg-slate-100">
            <div class="flex flex-col items-center">
                <div class="w-full lg:w-1/2">
                    <SuccesMessage
                        message="You have successfully registered for this event!"
                        v-if="eventRegisterSuccess"
                        @closeMessage="eventRegisterSuccess = false"
                    />
                </div>
            </div>
            <div class="flex flex-col items-center ">
                <EventInviteDetails :event="event" @accept-event-invite="showModal = true" />
            </div>
            <div :class="moveEventRegisterDown ? 'flex-col-reverse' : 'flex-col'" class="flex flex-col items-center">
                <EventParticipantsList :participants="event.participants" :eventOwner="eventOwner" />
            </div>
            <div class="flex flex-col items-center mt-3">
                <EventAddToCalendar :event="event" class="mt-2" />
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
    </DefaultLayout>
</template>
