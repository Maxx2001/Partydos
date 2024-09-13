<script setup>
import DefaultLayout from "@/Layouts/DefaultLayout.vue";
import EventInviteDetails from "@/Pages/Events/Partials/EventInviteDetails.vue";
import EventRegisterForm from "@/Pages/Events/Partials/EventRegisterForm.vue";
import EventParticipantsList from "@/Pages/Events/Partials/EventParticipantsList.vue";
import EventAddToCalendar from "@/Pages/Events/Partials/EventAddToCalendar.vue";
import BaseModal from "@/Components/Base/BaseModal.vue";
import { defineProps, ref } from "vue";
const props = defineProps({
    event: {
        type: Object,
        required: true
    }
});

const showModal = ref(false);
const moveEventRegisterDown = ref(false);
const eventOwner = ref(props.event.eventOwner);

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
</script>

<template>
    <DefaultLayout>
        <div class="py-16 md:py-24 px-8 lg:px-0 bg-slate-100">
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
            <EventRegisterForm ref="eventRegisterFormRef" :event="event" @register-success="showModal = false"/>
        </BaseModal>
    </DefaultLayout>
</template>
