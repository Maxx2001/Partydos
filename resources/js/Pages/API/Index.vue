<script setup>
import ApiTokenManager from '@/Pages/API/Partials/ApiTokenManager.vue';
import DefaultLayout from "@/Layouts/DefaultLayout.vue";

defineProps({
    tokens: Array,
    availablePermissions: Array,
    defaultPermissions: Array,
});
</script>

<template>
    <DefaultLayout title="API Tokens">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                API Tokens
            </h2>
        </template>

        <div>
            <div class="max-w-6xl mx-auto py-10 sm:px-6 lg:px-8">
                <ApiTokenManager
                    :tokens="tokens"
                    :available-permissions="availablePermissions"
                    :default-permissions="defaultPermissions"
                />
            </div>
        </div>
    </DefaultLayout>
</template>
