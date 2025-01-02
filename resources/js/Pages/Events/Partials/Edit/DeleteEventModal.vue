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
const handleConfirm = () => router.delete(route('events.delete', {event: props.event.id}),
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
        title="Are u sure you want to delete this event?"
    >
        <span>
            This action cannot be reversed and no users will be able to find it.
        </span>
    </BaseModal>
</template>
