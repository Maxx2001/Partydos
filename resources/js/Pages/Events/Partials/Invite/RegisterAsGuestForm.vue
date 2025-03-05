<script setup>
import EmailInput from "@/Components/Form/EmailInput.vue";
import TextInput from "@/Components/Form/TextInput.vue";
import BaseButton from "@/Components/Base/BaseButton.vue";
import {reactive} from "vue";
import {router, useForm} from "@inertiajs/vue3";

const props = defineProps({
    event: {
        type: Object,
        required: true,
    },
});

const form = useForm({
    name: '',
    email: '',
});

const errors = reactive({
    name: '',
    email: '',
});

const resetForm = () => {
    form.name = '';
    form.email = '';
    clearErrors();
};

const clearErrors = () => {
    errors.name = '';
    errors.email = '';
};

const validateForm = () => {
    clearErrors();

    let isValid = true;

    if (!form.name) {
        errors.name = 'Name is required.';
        isValid = false;
    }

    const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!form.email) {
        errors.email = 'Email is required.';
        isValid = false;
    } else if (!emailPattern.test(form.email)) {
        errors.email = 'Please enter a valid email address.';
        isValid = false;
    }

    return isValid;
};

const submitGuestRegister = () => {
    if (validateForm()) {
        form.post(
            route('events.register-guest', props.event.uniqueIdentifier),
            {
                preserveScroll: true,
                onSuccess: () => {
                    resetForm();
                    emit('registerSuccess');
                },
            }
        );
    }
};

const emit = defineEmits(['showLoginForm']);
</script>

<template>
    <div
        class="w-full space-y-6 pb-3"
    >
        <p class="text-center md:text-left text-xl md:text-3xl font-bold">
            <span class="text-indigo-600">
                Join
            </span>
            without an account!
        </p>
        <TextInput
            id="name"
            placeholder="Your name"
            label="Enter your name"
            v-model=form.name
            :model-value="form.name"
            :error-message="form.errors.name"
        />
        <EmailInput
            id="email"
            label="Your Email"
            v-model=form.email
            placeholder="party@dos.com"
            :error-message="form.errors.email"
        />
    </div>
    <BaseButton
        type="button"
        variant="submit"
        label="Join event!"
        class="w-full"
        @click="submitGuestRegister"
    />
    <hr class="border-blue-500 my-2 w-full">
    <BaseButton
        type="button"
        label="Login and join the event!"
        class="w-full"
        @click="emit('showLoginForm')"
    />
</template>
