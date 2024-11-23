<script setup>
import {reactive, ref, defineExpose, defineEmits, onMounted} from 'vue';
import {router, usePage} from '@inertiajs/vue3';
import TextInput from "@/Components/Inputs/TextInput.vue";
import { EnvelopeIcon } from '@heroicons/vue/20/solid';

const form = reactive({
    name: '',
    email: '',
});

const props = defineProps({
    event: {
        type: Object,
        required: true,
    },
});

const emit = defineEmits(['registerSuccess']);

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
        return router.post(
            route('events.register-guest', props.event.uniqueIdentifier),
            form,
            {
                onSuccess: () => {
                    resetForm();
                    emit('registerSuccess');
                },
            }
        );
    }
};

const submitAuthenticated = () => {
    return router.post(
        route('events.accept-invite', props.event.uniqueIdentifier),
        {
            onSuccess: () => {
                emit('registerSuccess');
            },
        }
    );
}

const userIsLoggedIn = ref(false);

onMounted(() => {
    userIsLoggedIn.value = usePage().props.auth.user !== null;
});

const submitRegisterForm = () => {
    if (userIsLoggedIn.value) {
        submitAuthenticated();
    } else {
        submitGuestRegister();

    }
};

defineExpose({submitRegisterForm});
</script>

<template>
    <div class="w-full bg-white">
        <form
            @submit.prevent="submitRegisterForm"
        >
            <div>
                <p class="text-center text-lg text-gray-600" v-if="userIsLoggedIn">
                    You are already logged in. You can join this event by clicking the button below.
                </p>
                <div v-else class="flex flex-col justify-center w-full items-center bg-white gap-5">
                    <TextInput
                        placeholder="Enter your name"
                        name="name"
                        :model-value="form.name"
                        :required="true"
                        :error="errors.name"
                        @update:modelValue="val => form.name = val"
                        class="w-full"
                    />
                    <TextInput
                        placeholder="Enter your Email"
                        name="email"
                        input-type="email"
                        :icon="EnvelopeIcon"
                        :model-value="form.email"
                        :required="true"
                        :error="errors.email"
                        @update:modelValue="val => form.email = val"
                        class="w-full"
                    />
                </div>
            </div>
        </form>
    </div>
</template>
