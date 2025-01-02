<script setup>
import BaseModal from "@/Components/Base/BaseModal.vue";
import { ref, defineExpose } from "vue";
// import EventInviteCancelForm from "@/Pages/Events/EventInvite/Partials/Modals/Partials/EventInviteCancelForm.vue";

const props = defineProps({
    event: {
        type: Object,
        required: true,
    },
});
const eventCancelFormRef = ref(null);

const showModal = ref(false);

const handleConfirm = () =>  eventCancelFormRef.value.submitCancelForm();
const openModal = () =>  showModal.value = true;

defineExpose({openModal});

</script>

<template>
    <BaseModal
        :isVisible="showModal"
        @close="showModal = false"
        @confirm="handleConfirm"
        title="Are you sure you want to cancel your invite to this event?"
    >
        lmao
<!--        <EventInviteCancelForm-->
<!--            ref="eventCancelFormRef"-->
<!--            :event="event"-->
<!--            @register-success="showModal = false"-->
<!--        />-->
    </BaseModal>
</template>
