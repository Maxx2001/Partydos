<script setup>
import { useForm } from "@inertiajs/vue3";
import { ref } from "vue";
import TextInput from "@/Components/Form/TextInput.vue";
import EmailInput from "@/Components/Form/EmailInput.vue";
import PasswordInput from "@/Components/Form/PasswordInput.vue";
import BaseButton from "@/Components/Base/BaseButton.vue";

const props = defineProps({
    event: {
        type: Object,
        required: true,
    }
});

const emit = defineEmits(['showLoginForm', 'registerSuccess', 'showLoginForm']);

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
    terms: false,
});

const formErrors = ref();

const handleSubmit = () => {
    form.post(route('event.register', props.event.uniqueIdentifier), {
        onError: () => {
            form.reset('password', 'password_confirmation');
            formErrors.value = 'Please fix the errors and try again.';
        },
        onFinish: () => {
            form.reset('password', 'password_confirmation');
            emit('registerSuccess');
        },
        onSuccess: () => {
            emit('registerSuccess');
        },
    });
};
</script>

<template>
    <form @submit.prevent="handleSubmit" class="space-y-6 w-full">
        <p class="text-center md:text-left text-xl md:text-3xl font-bold">
            <span class="text-indigo-600">
                Register
            </span>
            and
            <span class="text-indigo-600">
                join
            </span>
            the party!
        </p>

        <TextInput
            id="name"
            label="Your Name"
            v-model="form.name"
            placeholder="John Doe"
            :error-message="form.errors.name"
        />

        <EmailInput
            id="email"
            label="Your Email"
            v-model="form.email"
            placeholder="party@dos.com"
            :error-message="form.errors.email"
        />

        <PasswordInput
            id="password"
            label="Password"
            v-model="form.password"
            placeholder="********"
            :error-message="form.errors.password"
        />

        <PasswordInput
            id="password_confirmation"
            label="Confirm Password"
            v-model="form.password_confirmation"
            placeholder="********"
            :error-message="form.errors.password_confirmation"
        />

        <label for="terms" class="flex justify-center items-center md:justify-start">
            <input
                type="checkbox"
                id="terms"
                v-model="form.terms"
                class="mr-2 rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800"
            >
            I agree to the
            <a
                :href="route('privacy-policy')"
                target="_blank"
                class="text-blue-500 font-bold pl-1 underline"
            >
                Terms & Privacy Policy
            </a>
        </label>

        <p v-if="form.errors.terms" class="text-sm text-red-500">
            {{ form.errors.terms }}
        </p>

        <div class="space-y-4">
            <BaseButton
                type="submit"
                label="Register and join the event!"
                class="w-full"
            />

            <hr class="border-blue-500 my-2 w-full" />

            <div class="w-full flex justify-center items-center">
                <span class="text-indigo-600" @click="emit('showLoginForm')">
                    Already have an account?
                </span>
            </div>
        </div>
    </form>
</template>
