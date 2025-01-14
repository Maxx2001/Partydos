<script setup>
import { useForm } from "@inertiajs/vue3";
import {ref} from "vue";
import TextInput from "@/Components/Form/TextInput.vue";
import PasswordInput from "@/Components/Form/PasswordInput.vue";
import SubmitButton from "@/Components/Form/SubmitButton.vue";
import ButtonLink from "@/Components/Form/ButtonLink.vue";
import EmailInput from "@/Components/Form/EmailInput.vue";

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const formErrors = ref();

const handleSubmit = () => {
    form.transform(data => ({
        ...data,
        remember: form.remember,
    })).post(route('login.authenticate'), {
        onError: () => {
            console.log('error');
            form.reset('password');
            formErrors.value = 'Invalid email or password.';
        },
        onFinish: () => {
            form.reset('password');
        },
    });
};
</script>

<template>
    <div class="flex items-center justify-center min-h-screen bg-gradient-to-br from-blue-500 to-purple-600 animate-gradient px-6 overflow-auto">
        <div class="w-full max-w-md bg-white shadow-lg rounded-xl p-8 ">
            <h2 class="text-3xl font-extrabold text-center mb-4">
                <a :href="route('home')">
                    <span>Party</span>
                    <span class="text-blue-700">dos</span>
                </a>
            </h2>
            <form @submit.prevent="handleSubmit" class="space-y-6">
                <div>
                    <EmailInput
                        id="email"
                        label="Your Email"
                        v-model=form.email
                        placeholder="party@dos.com"
                        :error-message="form.errors.email"
                    />
                </div>
                <div>
                    <PasswordInput
                        id="password"
                        label="Your Password"
                        :model-value="form.password"
                        v-model=form.password
                        placeholder="********"
                        :error-message="form.errors.password"
                    />
                    <div class="mt-4">
                        <label for="remember" >
                            <input
                                type="checkbox"
                                name="remember"
                                id="remember"
                                v-model="form.remember"
                                class=" rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800"

                            >
                            Remember
                            <span class="text-blue-500 font-bold">
                                Me!
                            </span>
                        </label>
                    </div>
                </div>
                <SubmitButton label="Let’s Party!"/>
            </form>
            <p class="mt-6 text-sm text-center text-gray-700">
                New here?
                <ButtonLink
                    :route="route('register')"
                    label="Sign up and Join the Fun! 🚀"
                />
                <a
                    :href="route('forgotPassword')"
                    class="inline-block w-full mt-4 py-3 text-sm text-blue-500"
                >
                    Forgot your password?
                </a>
                <hr class="border-blue-500">
                <a
                    :href="route('home')"
                    class="inline-block w-full text-sm text-blue-500 mt-2"
                >
                    Go back
                </a>
            </p>
        </div>
    </div>
</template>

<style>
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



