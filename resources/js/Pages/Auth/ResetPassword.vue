<script setup>
import { useForm } from '@inertiajs/vue3';
import ToastList from "@/Components/Layout/Toasts/ToastList.vue";

const props = defineProps({
    email: {
        type: String,
        required: true,
    },
    token: {
        type: String,
        required: true,
    },
    userId: {
        type: Number,
        required: true,
    },
});

const form = useForm({
    token: props.token,
    email: props.email,
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('password.update', {user: props.userId}), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <div class="flex items-center justify-center min-h-screen bg-gradient-to-br from-pink-500 via-purple-600 to-blue-500 animate-gradient px-6 overflow-auto">
        <div class="w-full max-w-md bg-white shadow-lg rounded-xl p-8">
            <h2 class="text-3xl font-extrabold text-blue-600 text-center mb-6">
                Reset Your Password 🔒
            </h2>
            <p class="mb-4 text-sm text-gray-600 dark:text-gray-400">
                Enter your email, a new password, and confirm it to reset your account password.
            </p>
            <form @submit.prevent="submit" class="space-y-6">
                <div>
                    <label for="email" class="block text-sm font-bold text-gray-700 mb-1">
                        Your Email
                    </label>
                    <input
                        id="email"
                        type="email"
                        v-model="form.email"
                        placeholder="you@example.com"
                        required
                        class="block w-full px-4 py-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-4 focus:ring-pink-400 focus:border-pink-400 placeholder-gray-400 text-gray-700"
                        :class="{ 'border-red-500': form.errors.email }"
                    />
                    <p v-if="form.errors.email" class="text-sm text-red-500 mt-1">
                        {{ form.errors.email }}
                    </p>
                </div>

                <div>
                    <label for="password" class="block text-sm font-bold text-gray-700 mb-1">
                        New Password
                    </label>
                    <input
                        id="password"
                        type="password"
                        v-model="form.password"
                        placeholder="********"
                        required
                        class="block w-full px-4 py-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-4 focus:ring-purple-400 focus:border-purple-400 placeholder-gray-400 text-gray-700"
                        :class="{ 'border-red-500': form.errors.password }"
                    />
                    <p v-if="form.errors.password" class="text-sm text-red-500 mt-1">
                        {{ form.errors.password }}
                    </p>
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm font-bold text-gray-700 mb-1">
                        Confirm Password
                    </label>
                    <input
                        id="password_confirmation"
                        type="password"
                        v-model="form.password_confirmation"
                        placeholder="********"
                        required
                        class="block w-full px-4 py-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-4 focus:ring-blue-400 focus:border-blue-400 placeholder-gray-400 text-gray-700"
                        :class="{ 'border-red-500': form.errors.password_confirmation }"
                    />
                    <p
                        v-if="form.errors.password_confirmation"
                        class="text-sm text-red-500 mt-1"
                    >
                        {{ form.errors.password_confirmation }}
                    </p>
                </div>

                <button
                    type="submit"
                    class="w-full flex justify-center items-center py-3 bg-gradient-to-br from-blue-500 to-purple-600 text-white font-bold rounded-md shadow-lg focus:outline-none focus:ring-4 focus:ring-purple-300 transition-transform transform"
                    :class="{ 'opacity-50': form.processing }"
                    :disabled="form.processing"
                >
                    Reset Password
                </button>
            </form>
            <ToastList />
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

