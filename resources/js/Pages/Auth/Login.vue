<template>
    <div class="flex items-center justify-center min-h-screen bg-gradient-to-br from-blue-500 to-purple-600 animate-gradient px-6 overflow-auto">
        <div class="w-full max-w-md bg-white shadow-lg rounded-xl p-8">
            <h2 class="text-3xl font-extrabold text-blue-600 text-center mb-6">
                Welcome Back! 🎉
            </h2>
            <form @submit.prevent="handleSubmit" class="space-y-6">
                <div>
                    <label for="email" class="block text-sm font-bold text-gray-700 mb-1">
                        Your Email
                    </label>
                    <input
                        id="email"
                        type="email"
                        v-model="form.email"
                        placeholder="party@dos.com"
                        class="block w-full px-4 py-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-4 focus:ring-blue-400 focus:border-blue-400 placeholder-gray-400 text-gray-700"
                        :class="{ 'border-red-500': form.errors.email }"
                        @focus="adjustScroll($event, 300)"
                    />
                    <p v-if="form.errors.email" class="text-sm text-red-500 mt-1">
                        {{ form.errors.email }}
                    </p>
                </div>
                <div>
                    <label for="password" class="block text-sm font-bold text-gray-700 mb-1">
                        Your Password
                    </label>
                    <input
                        id="password"
                        type="password"
                        v-model="form.password"
                        placeholder="********"
                        class="block w-full px-4 py-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-4 focus:ring-blue-400 focus:border-blue-400 placeholder-gray-400 text-gray-700"
                        :class="{ 'border-red-500': form.errors.password }"
                        @focus="adjustScroll($event, 100)"
                    />
                    <p v-if="form.errors.password" class="text-sm text-red-500 mt-1">
                        {{ form.errors.password }}
                    </p>
                </div>
                <button
                    type="submit"
                    class="w-full flex justify-center items-center py-3 bg-gradient-to-br from-blue-500 to-purple-600 text-white font-bold rounded-md shadow-lg focus:outline-none focus:ring-4 focus:ring-purple-300 transition-transform transform"
                >
                    Let’s Party!
                </button>
            </form>
            <p class="mt-6 text-sm text-center text-gray-700">
                New here?
                <a
                    :href="route('register')"
                    class="inline-block w-full mt-4 py-3 bg-gradient-to-br from-purple-500 to-pink-500 text-white font-bold text-center rounded-md shadow-md focus:ring-4 focus:ring-pink-300 focus:outline-none transition-transform transform"
                >
                    Sign up and Join the Fun! 🚀
                </a>
            </p>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import { useForm } from "@inertiajs/vue3";

const email = ref('');
const password = ref('');
const isInputFocused = ref(false);

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const handleSubmit = () => {
    form.transform(data => ({
        ...data,
        remember: form.remember ? 'on' : '',
    })).post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};

// Custom scroll adjustment function
const adjustScroll = (event, offset = 0) => {
    const target = event.target;
    const targetRect = target.getBoundingClientRect();
    const scrollTop = window.scrollY || document.documentElement.scrollTop;

    // Calculate the top position with an offset
    const scrollTo = scrollTop + targetRect.top - offset;

    // Smooth scroll to the calculated position
    window.scrollTo({
        top: scrollTo,
        behavior: 'smooth',
    });
};
</script>


<style>
@keyframes gradientBackground {
    0% {
        background-position: 0% 50%;
    }
    50% {
        background-position: 100% 50%;
    }
    100% {
        background-position: 0% 50%;
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
    background-size: 600% 600%; /* Slightly smaller for faster motion */
    animation: gradientBackground 18s ease infinite; /* Adjusted timing for smoother, dynamic transitions */
}


html, body {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    height: 100%;
    overflow: hidden; /* Prevent scrolling */
}
</style>



