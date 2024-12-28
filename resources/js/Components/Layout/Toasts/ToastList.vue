<script setup>
import Toast from "./Toast.vue";
import { usePage } from "@inertiajs/vue3";
import toastsStore from "@/Stores/ToastMessages.ts";
import { storeToRefs } from "pinia";

const page = usePage();
const useToasts = toastsStore();
const { toasts } = storeToRefs(useToasts);

</script>

<template>
    <div class="fixed bottom-4 right-4 z-50 space-y-4 w-full max-w-xs">
        <Toast v-for="toast in toasts" v-bind="toast" />
    </div>
</template>
