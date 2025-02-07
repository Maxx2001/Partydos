<script setup>
import { Head, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import ToastList from "@/Components/Layout/Toasts/ToastList.vue";
import EmailInput from "@/Components/Form/EmailInput.vue";

const form = useForm({
    email: '',
});

const submit = () => form.post(route('password.email', form.email), {
    onSuccess: () => {
        form.email = '';
    },
});
</script>

<template>
    <div class="flex items-center justify-center min-h-screen bg-gradient-to-br from-blue-500 to-purple-600 animate-gradient px-6 overflow-auto">
        <div class="w-full max-w-md bg-white shadow-lg rounded-xl p-8">
            <h2 class="text-3xl font-extrabold text-blue-600 text-center mb-6">
                Forgot Password? 🔒
            </h2>
            <p class="mb-4 text-sm text-gray-600 dark:text-gray-400">
                Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.
            </p>
            <form @submit.prevent="submit" class="space-y-6">
                <div>
                    <EmailInput
                        id="email"
                        label="Your Email"
                        v-model=form.email
                        placeholder="party@dos.com"
                        :error-message="form.errors.email"
                    />
                </div>
                <button
                    type="submit"
                    class="w-full flex justify-center items-center py-3 bg-gradient-to-br from-blue-500 to-purple-600 text-white font-bold rounded-md shadow-lg focus:outline-none focus:ring-4 focus:ring-purple-300 transition-transform transform"
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                >
                    Email Password Reset Link
                </button>
                <p class="text-sm text-center text-gray-700">
                    <hr class="border-blue-500">
                    <a
                        :href="route('login')"
                        class="inline-block w-full text-sm text-blue-500 mt-2"
                    >
                        Go back
                    </a>
                </p>
            </form>
            <ToastList/>
        </div>
    </div>
</template>

<style scoped>
@keyframes gradientBackground {
    0% {
        background-position: 0 50%;
    }
    50% {
        background-position: 100% 50%;
    }
    100% {
        background-position: 0 50%;
    }
}

.animate-gradient {
    background: linear-gradient(
        270deg,
        #3b82f6, /* Blue */
        #5c67f6, /* Intermediate Blue */
        #7a4cf6, /* Blue-Purple */
        #9c4af6, /* Purple */
        #a855f7, /* Bright Purple */
        #9c4af6, /* Purple */
        #7a4cf6, /* Blue-Purple */
        #5c67f6, /* Intermediate Blue */
        #3b82f6  /* Blue */
    );
    background-size: 600% 600%;
    animation: gradientBackground 18s ease infinite;
}

html, body {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    height: 100%;
    overflow: hidden;
}
</style>
