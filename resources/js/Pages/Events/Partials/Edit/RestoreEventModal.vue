<script setup>
import BaseModal from "@/Components/Base/BaseModal.vue";
import {defineExpose, ref} from "vue";
import {router} from "@inertiajs/vue3";

const props = defineProps({
    event:{
        type: Object,
        required: true
    }
})

const showModal = ref(false);

const openModal = () => showModal.value = true;

defineExpose({openModal});
const handleConfirm = () => router.post(route('events.restore', {event: props.event.id}),
    {},
    {
        onSuccess: () => {
            showModal.value = false;
        }
    }
);
</script>

<template>
    <BaseModal
        :isVisible="showModal"
        @close="showModal = false"
        @confirm="handleConfirm"
        title="Are u sure you want to restore this event?"
    >
    </BaseModal>
</template>
