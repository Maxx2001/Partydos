<script setup>
import {Head, Link, useForm} from '@inertiajs/vue3';
import TextInput from "@/Components/Form/TextInput.vue";
import EmailInput from "@/Components/Form/EmailInput.vue";
import PasswordInput from "@/Components/Form/PasswordInput.vue";
import SubmitButton from "@/Components/Form/SubmitButton.vue";

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
                <TextInput
                    id="name"
                    label="Name"
                    :model-value="form.name"
                    placeholder="John Doe"
                    v-model=form.name
                    :error-message="form.errors.name"
                />

                <EmailInput
                    id="email"
                    label="Email"
                    :model-value="form.email"
                    placeholder="party@dos.com"
                    v-model=form.email
                    :error-message="form.errors.email"
                />

                <PasswordInput
                    id="password"
                    label="Password"
                    :model-value="form.password"
                    v-model=form.password
                    :error-message="form.errors.password"
                />

                <PasswordInput
                    id="password_confirmation"
                    label="Password Password"
                    :model-value="form.password_confirmation"
                    v-model=form.password_confirmation
                    :error-message="form.errors.password_confirmation"
                />

                <div class="flex items-start mt-4">
                    <input
                        id="terms"
                        v-model="form.terms"
                        type="checkbox"
                        class="w-5 h-5 text-blue-600 rounded focus:ring-4 focus:ring-blue-400 border-gray-300"
                    />
                    <label for="terms" class="ml-3 text-sm text-gray-700">
                        I agree to the
                        <a
                            :href="route('privacy-policy')"
                            target="_blank"
                            class="text-blue-600 underline hover:text-blue-800"
                        >
                            Terms of Service and Privacy Policy
                        </a>
                    </label>
                    <p v-if="form.errors.terms" class="text-sm text-red-500 mt-1 ml-8">
                        {{ form.errors.terms }}
                    </p>
                </div>

                <SubmitButton label="Register"/>

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
