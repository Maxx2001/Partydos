<script setup>
import EventRegisterForm from "@/Pages/Events/Partials/Invite/Modals/Partials/EventRegisterForm.vue";
import BaseModal from "@/Components/Base/BaseModal.vue";
import { ref, defineExpose } from "vue";

const props = defineProps({
    event: {
        type: Object,
        required: true,
    },
});
const eventRegisterFormRef = ref(null);

const showModal = ref(false);

const handleConfirm = () => eventRegisterFormRef.value.submitRegisterForm();
const openModal = () => showModal.value = true;

defineExpose({openModal});

</script>

<template>
    <BaseModal
        :isVisible="showModal"
        @close="showModal = false"
        @confirm="handleConfirm"
        title="Join this event!"
    >
        <EventRegisterForm
            ref="eventRegisterFormRef"
            :event="event"
           @register-success="showModal = false"
        />
    </BaseModal>
</template>
