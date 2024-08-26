<script setup>
import DefaultLayout from "@/Layouts/DefaultLayout.vue";
import EventInviteDetails from "@/Pages/EventInvite/Partials/EventInviteDetails.vue";
import EventRegisterForm from "@/Pages/EventInvite/Partials/EventRegisterForm.vue";
import ParticipantsList from "@/Pages/EventInvite/Partials/ParticipantsList.vue";
import { onMounted, ref } from "vue";

const props = defineProps({
    event: {
        type: Object,
        required: true
    }
});


onMounted(() => {
    document.title = title || "Default Title";
    document.querySelector('meta[name="description"]')
            .setAttribute('content', props.event.description || "Default description");

    document.querySelector('meta[property="og:title"]').setAttribute('content', props.event.title || "Default Title");
    document.querySelector('meta[property="og:description"]')
            .setAttribute('content', props.event.description || "Default description");
    document.querySelector('meta[property="og:image"]')
            .setAttribute('content', props.event.data.image || "default-image.jpg");
    document.querySelector('meta[property="og:url"]').setAttribute('content', window.location.href);
});

const moveEventRegisterDown = ref(false);
</script>

<template>
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
