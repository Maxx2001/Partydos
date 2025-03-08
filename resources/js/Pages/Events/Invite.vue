<script setup>
import DefaultLayout from "@/Layouts/DefaultLayout.vue";
import EventInviteBanner from "@/Pages/Events/Partials/Invite/EventInviteBanner.vue";
import EventParticipantsList from "@/Pages/Events/Partials/Invite/EventParticipantsList.vue";
import {defineProps, onMounted, ref, toRefs} from "vue";
import {useTitle} from "@/Composables/useTitle.js";
import EventInviteHero from "@/Pages/Events/Partials/Invite/EventInviteHero.vue";
import AOS from "aos";
import "aos/dist/aos.css";
import EventRegisterModal from "@/Pages/Events/Partials/Invite/Modals/EventRegisterModal.vue";
import EventAddToCalendarModel from "@/Pages/Events/Partials/Invite/Modals/EventAddToCalendarModel.vue";
import EventInviteLinkeModal from "@/Pages/Events/Partials/Invite/Modals/EventInviteLinkeModal.vue";
import EventDescription from "@/Pages/Events/Partials/Invite/EventDescription.vue";
import BaseModal from "@/Components/Base/BaseModal.vue";
import {router} from "@inertiajs/vue3";
import BaseOutlineButton from "@/Components/Base/BaseOutlineButton.vue";
import EventShareButton from "@/Pages/Events/Partials/Invite/EventShareButton.vue";

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
    showCancelButton: {
        type: Boolean,
        default: false,
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

const showCancelForm = ref(false);

const handleConfirm = () => {
    router.delete(
        route('events.cancel-invite', {'event': props.event.uniqueIdentifier}),
        {
            onSuccess: () => {
                showCancelForm.value = false;
            }
        },
    );
}
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
                @cancel-event-invite="showCancelForm = true"
                :show-invite-button="showInviteButton"
                :show-cancel-button="showCancelButton"
                class="hidden md:flex"
            />
            <EventParticipantsList
                :event="event"
                class="pb-6 lg:pb-24"
                :invited-users="event.invitedUsers"
                :eventOwner="event.eventOwner"
                :moveEventRegisterDown="moveEventRegisterDown"
                :is-event-owner="event.canEdit"
                :show-already-signed-up-message="showCancelButton"
                @open-add-to-calendar-modal="eventAddToCalendarModel.openModal()"
            />



            <div class="flex md:hidden justify-center pb-8" v-if="showCancelButton">
                <BaseOutlineButton
                    label="Cancel invite"
                    @click="showCancelForm = true"
                    class="bg-blue-700 font-bold py-3 px-6 rounded-md hover:bg-blue-800 transition duration-300 ease-in-out"
                    variant="cancel"
                />
            </div>
        </div>

        <EventRegisterModal
            :event="event"
            ref="eventRegisterModal"
        />

        <BaseModal
            :isVisible="showCancelForm"
            @close="showCancelForm = false"
            @confirm="handleConfirm"
            title="Decline this event invite"
        >
            Are you sure you want to decline this event invite?
        </BaseModal>

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
