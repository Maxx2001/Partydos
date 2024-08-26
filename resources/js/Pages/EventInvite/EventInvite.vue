<script setup>
import DefaultLayout from "@/Layouts/DefaultLayout.vue";
import EventInviteDetails from "@/Pages/EventInvite/Partials/EventInviteDetails.vue";
import EventRegisterForm from "@/Pages/EventInvite/Partials/EventRegisterForm.vue";
import ParticipantsList from "@/Pages/EventInvite/Partials/ParticipantsList.vue";
import { ref } from "vue";
import { Head } from '@inertiajs/vue3'

const props = defineProps({
    event: {
        type: Object,
        required: true
    }
});

const moveEventRegisterDown = ref(false);
</script>

<template>
    <Head>
        <title>My app</title>
        <meta head-key="description" name="description" content="This is the default description" />
    </Head>
    <DefaultLayout>
        <div class="py-16 md:py-24 px-8 lg:px-0 bg-slate-100">
            <div class="flex flex-col items-center">
                <EventInviteDetails :event="event.data"/>
            </div>
            <div
                :class="moveEventRegisterDown ? 'flex-col-reverse' : 'flex-col'"
                class="flex flex-col items-center"
            >
                <EventRegisterForm :event="event.data" @register-success="moveEventRegisterDown = true"/>
                <ParticipantsList
                    :participants="event.data.participants"
                    :eventOwner="event.data.guestUserEventOwner"
                />
            </div>
        </div>
    </DefaultLayout>
</template>
