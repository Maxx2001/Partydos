<script setup>
import InviteLink from "@/Pages/Events/Partials/Invite/Modals/Partials/InviteLink.vue";
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
        @confirm="showModal = false"
        cancel-button-label="Close"
        title="Invite your friends"
    >
        <InviteLink :event="event"/>
    </BaseModal>
</template>
