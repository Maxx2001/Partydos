<script setup>
import BaseModal from "@/Components/Base/BaseModal.vue";
import {defineExpose, ref} from "vue";
import {router} from "@inertiajs/vue3";

const showModal = ref(false);

const openModal = () =>  showModal.value = true;

defineExpose({openModal});
const handleConfirm = () => router.delete(route('user-destroy'));
</script>

<template>
    <BaseModal
        :isVisible="showModal"
        @close="showModal = false"
        @confirm="handleConfirm"
        title="Are you sure you want to delete your account?"
    >
        <div class="text-center">
            <p class="text-lg text-gray-600">
                Deleting your account is irreversible. Are you sure you want to proceed?
            </p>
        </div>
    </BaseModal>
</template>
