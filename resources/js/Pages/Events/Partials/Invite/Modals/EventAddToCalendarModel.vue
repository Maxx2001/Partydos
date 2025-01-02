<script setup>
import EventAddToCalendar from "@/Pages/Events/Partials/Invite/Modals/Partials/EventAddToCalendar.vue";
import BaseModal from "@/Components/Base/BaseModal.vue";
import {defineExpose, ref} from "vue";

defineProps({
    event: {
        type: Object,
        required: true,
    },
});
const showModal = ref(false);
const openModal = () =>  showModal.value = true;

defineExpose({openModal});
</script>

<template>
    <BaseModal
        :isVisible="showModal"
        @close="showModal = false"
        :show-submit-button="false"
        title="Add event to calendar"
        cancel-button-label="Close"
    >
        <EventAddToCalendar :event="event"/>
    </BaseModal>
</template>
