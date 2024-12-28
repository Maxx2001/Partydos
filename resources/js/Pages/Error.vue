<script setup>
import { computed } from 'vue';
import { defineProps } from 'vue';
import BaseButton from "@/Components/Base/BaseButton.vue";
import DefaultLayout from "@/Layouts/DefaultLayout.vue";
import {router} from "@inertiajs/vue3";

const props = defineProps({
    status: {
        type: String,
        default: '404',
    },
});

const errorTitle = computed(() => {
    const titles = {
        '400': 'Bad Request',
        '401': 'Unauthorized',
        '403': 'Forbidden',
        '404': 'Not Found',
        '405': 'Method Not Allowed',
        '429': 'Too Many Requests',
        '500': 'Server Error',
    };
    return titles[props.status] || 'Oops! Something went wrong';
});

const errorMessage = computed(() => {
    const messages = {
        '400': 'The server could not understand the request due to invalid syntax.',
        '401': 'You must be authenticated to access this page.',
        '403': 'You do not have permission to view this page.',
        '404': "It seems like we can't find what you're looking for.",
        '405': 'The method is not allowed for the requested URL.',
        '429': 'You have sent too many requests in a given amount of time.',
        '500': 'Oops! We encountered an error on our side. Our team is working on it.',
    };
    return messages[props.status] || "Something went wrong. Please try again later.";
});
</script>

<template>
    <DefaultLayout>
        <div class="error-page flex items-center justify-center h-full bg-gray-100 text-center">
            <div class="max-w-lg px-6 py-12">
                <h1 class="text-6xl font-bold text-orange-500">{{ status }}</h1>
                <h2 class="text-2xl font-semibold mt-4">{{ errorTitle }}</h2>
                <p class="text-gray-600 mt-4">
                    {{ errorMessage }}
                </p>
                <base-button
                    label="Go Back Home"
                    class="mt-6 inline-block px-6 py-3 bg-blue-500 text-white text-sm font-medium rounded shadow hover:bg-red-600 transition"
                    @click="router.get(route('home'))"
                />
            </div>
        </div>
    </DefaultLayout>
</template>

