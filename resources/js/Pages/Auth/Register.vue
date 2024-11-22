<script setup>
import {Head, Link, useForm} from '@inertiajs/vue3';

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
    terms: false,
});

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <Head title="Register"/>

    <div
        class="flex items-center justify-center min-h-screen bg-gradient-to-br from-blue-500 to-purple-600 animate-gradient px-6 overflow-auto">
        <div class="w-full max-w-md bg-white shadow-lg rounded-xl p-8">
            <h2 class="text-3xl font-extrabold text-blue-600 text-center mb-6">
                Join the Party! 🎉
            </h2>
            <form @submit.prevent="submit" class="space-y-6">
                <div>
                    <label for="name" class="block text-sm font-bold text-gray-700 mb-1">
                        Name
                    </label>
                    <input
                        id="name"
                        v-model="form.name"
                        type="text"
                        placeholder="John Doe"
                        class="block w-full px-4 py-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-4 focus:ring-blue-400 focus:border-blue-400 placeholder-gray-400 text-gray-700"
                    />
                    <p v-if="form.errors.name" class="text-sm text-red-500 mt-1">
                        {{ form.errors.name }}
                    </p>
                </div>

                <div>
                    <label for="email" class="block text-sm font-bold text-gray-700 mb-1">
                        Email
                    </label>
                    <input
                        id="email"
                        v-model="form.email"
                        type="email"
                        placeholder="party@dos.com"
                        class="block w-full px-4 py-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-4 focus:ring-blue-400 focus:border-blue-400 placeholder-gray-400 text-gray-700"
                    />
                    <p v-if="form.errors.email" class="text-sm text-red-500 mt-1">
                        {{ form.errors.email }}
                    </p>
                </div>

                <div>
                    <label for="password" class="block text-sm font-bold text-gray-700 mb-1">
                        Password
                    </label>
                    <input
                        id="password"
                        v-model="form.password"
                        type="password"
                        placeholder="********"
                        class="block w-full px-4 py-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-4 focus:ring-blue-400 focus:border-blue-400 placeholder-gray-400 text-gray-700"
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
                        v-model="form.password_confirmation"
                        type="password"
                        placeholder="********"
                        class="block w-full px-4 py-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-4 focus:ring-blue-400 focus:border-blue-400 placeholder-gray-400 text-gray-700"
                    />
                    <p v-if="form.errors.password_confirmation" class="text-sm text-red-500 mt-1">
                        {{ form.errors.password_confirmation }}
                    </p>
                </div>

                <div v-if="$page.props.jetstream.hasTermsAndPrivacyPolicyFeature" class="flex items-start mt-4">
                    <input
                        id="terms"
                        v-model="form.terms"
                        type="checkbox"
                        class="w-5 h-5 text-blue-600 rounded focus:ring-4 focus:ring-blue-400 border-gray-300"
                    />
                    <label for="terms" class="ml-3 text-sm text-gray-700">
                        I agree to the
                        <a
                            :href="route('terms.show')"
                            target="_blank"
                            class="text-blue-600 underline hover:text-blue-800"
                        >
                            Terms of Service
                        </a>
                        and
                        <a
                            :href="route('policy.show')"
                            target="_blank"
                            class="text-blue-600 underline hover:text-blue-800"
                        >
                            Privacy Policy
                        </a>.
                    </label>
                    <p v-if="form.errors.terms" class="text-sm text-red-500 mt-1 ml-8">
                        {{ form.errors.terms }}
                    </p>
                </div>

                <button
                    type="submit"
                    class="w-full flex justify-center items-center py-3 bg-gradient-to-br from-blue-500 to-purple-600 text-white font-bold rounded-md shadow-lg focus:outline-none focus:ring-4 focus:ring-purple-300 transition-transform transform"
                >
                    Register
                </button>

                <p class="text-sm text-center text-gray-700 mt-6">
                    Already have an account?
                    <a
                        :href="route('login')"
                        class="text-blue-600 font-bold underline hover:text-blue-800"
                    >
                        Log in!
                    </a>
                </p>
            </form>
        </div>
    </div>
</template>

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
        #3b82f6,
        #4c6ff6,
        #5d5af6,
        #7a4cf6,
        #9c4af6,
        #a855f7,
        #9c4af6,
        #7a4cf6,
        #5d5af6,
        #4c6ff6,
        #3b82f6
    );
    background-size: 800% 800%;
    animation: gradientBackground 24s ease infinite;
}

html, body {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    height: 100%;
    overflow: hidden;
}
</style>
