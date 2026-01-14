<script setup>
import DefaultLayout from "@/Layouts/DefaultLayout.vue";
import EventInviteBanner from "@/Pages/Events/Partials/Invite/EventInviteBanner.vue";
import EventParticipantsList from "@/Pages/Events/Partials/Invite/EventParticipantsList.vue";
import {computed, defineProps, onMounted, ref, toRefs} from "vue";
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
    googleAccountConnected: {
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

const googleIntegration = computed(() => props.event.googleIntegration);
const googleSyncEnabled = computed(() => googleIntegration.value?.syncEnabled ?? false);

const exportToGoogle = () => {
    router.post(route('events.google.sync', { event: props.event.id }));
};

const toggleGoogleSync = () => {
    router.post(route('events.google.toggle', { event: props.event.id }));
};

const formattedLastSyncedAt = computed(() => {
    if (!googleIntegration.value?.lastSyncedAt) {
        return null;
    }

    return new Date(googleIntegration.value.lastSyncedAt).toLocaleString();
});

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

            <div v-if="event.canEdit" class="mx-auto w-full max-w-5xl px-6 pb-8">
                <div class="rounded-2xl bg-white p-6 shadow-sm">
                    <div class="flex flex-col gap-2 md:flex-row md:items-center md:justify-between">
                        <div>
                            <h2 class="text-lg font-semibold text-slate-900">Google Calendar</h2>
                            <p class="text-sm text-slate-500">
                                Sync this event to your Google Calendar (one-way).
                            </p>
                        </div>
                        <span
                            class="w-fit rounded-full px-3 py-1 text-xs font-semibold"
                            :class="googleAccountConnected ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-200 text-slate-600'"
                        >
                            {{ googleAccountConnected ? "Connected" : "Not connected" }}
                        </span>
                    </div>

                    <div class="mt-4 flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                        <div class="flex flex-col gap-2">
                            <div class="flex items-center gap-2 text-sm text-slate-600">
                                <span class="font-medium">Auto-sync changes</span>
                                <span
                                    class="rounded-full px-2 py-0.5 text-xs font-semibold"
                                    :class="googleSyncEnabled ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-200 text-slate-600'"
                                >
                                    {{ googleSyncEnabled ? "Enabled" : "Off" }}
                                </span>
                            </div>
                            <p v-if="formattedLastSyncedAt" class="text-xs text-slate-500">
                                Last synced: {{ formattedLastSyncedAt }}
                            </p>
                            <p v-else class="text-xs text-slate-500">
                                Not synced yet.
                            </p>
                        </div>
                        <div class="flex flex-col gap-2 sm:flex-row sm:items-center">
                            <button
                                type="button"
                                class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white shadow hover:bg-blue-700 disabled:cursor-not-allowed disabled:opacity-60"
                                :disabled="!googleAccountConnected"
                                @click="exportToGoogle"
                            >
                                Export to Google Calendar
                            </button>
                            <button
                                type="button"
                                class="rounded-lg border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50 disabled:cursor-not-allowed disabled:opacity-60"
                                :disabled="!googleAccountConnected"
                                @click="toggleGoogleSync"
                            >
                                {{ googleSyncEnabled ? "Disable auto-sync" : "Enable auto-sync" }}
                            </button>
                        </div>
                    </div>
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
