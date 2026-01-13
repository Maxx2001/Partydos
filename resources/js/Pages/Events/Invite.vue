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
import { Link } from "@inertiajs/vue3";

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
    polls: {
        type: Array,
        default: () => [],
    },
    canViewPolls: {
        type: Boolean,
        default: false,
    },
    canManagePolls: {
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
                @open-add-to-calendar-modal="eventAddToCalendarModel.openModal()"
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

            <div v-if="canViewPolls" class="px-6 pb-12 lg:pb-24">
                <div class="max-w-5xl mx-auto bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
                    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                        <div>
                            <h2 class="text-xl font-semibold text-slate-900">Polls</h2>
                            <p class="text-sm text-slate-500">Vote and see what the group decides.</p>
                        </div>
                        <div class="flex flex-wrap gap-3">
                            <Link
                                v-if="canManagePolls"
                                :href="route('events.polls.create', event.id)"
                                class="inline-flex items-center justify-center px-4 py-2 rounded-md bg-blue-700 text-white font-semibold hover:bg-blue-800 transition"
                            >
                                Create poll
                            </Link>
                            <Link
                                :href="route('events.polls.index', event.id)"
                                class="inline-flex items-center justify-center px-4 py-2 rounded-md border border-blue-700 text-blue-700 font-semibold hover:bg-blue-50 transition"
                            >
                                View all polls
                            </Link>
                        </div>
                    </div>

                    <div v-if="polls.length" class="mt-6 space-y-3">
                        <div
                            v-for="poll in polls"
                            :key="poll.id"
                            class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between border border-slate-200 rounded-xl p-4"
                        >
                            <div>
                                <p class="text-base font-semibold text-slate-900">{{ poll.question }}</p>
                                <p class="text-sm text-slate-500 mt-1">
                                    <span class="capitalize">{{ poll.status }}</span>
                                    · <span class="capitalize">{{ poll.voteMode }}</span>
                                    · {{ poll.votesCount }} votes
                                </p>
                            </div>
                            <Link
                                :href="route('events.polls.show', { event: event.id, poll: poll.id })"
                                class="inline-flex items-center justify-center px-4 py-2 rounded-md bg-slate-900 text-white font-semibold hover:bg-slate-800 transition"
                            >
                                Open
                            </Link>
                        </div>
                    </div>
                    <p v-else class="mt-4 text-sm text-slate-500">No polls yet. Be the first to create one.</p>
                </div>
            </div>


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
