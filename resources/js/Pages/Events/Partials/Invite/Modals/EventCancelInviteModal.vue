<script setup>
import BaseModal from "@/Components/Base/BaseModal.vue";
import { ref, defineExpose } from "vue";

const eventCancelFormRef = ref(null);

const showModal = ref(false);

const handleConfirm = () => {
    // Event confirmation handled
};
const openModal = () => showModal.value = true;

defineExpose({openModal});

</script>

<template>
    <BaseModal
        :isVisible="showModal"
        @close="showModal = false"
        @confirm="handleConfirm"
        title="Cancel this event"
    >
       lmao
    </BaseModal>
</template>
