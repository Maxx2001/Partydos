<script setup>
import {reactive, ref, defineExpose, defineEmits, onMounted} from 'vue';
import {router, usePage} from '@inertiajs/vue3';
import TextInput from "@/Components/Form/TextInput.vue";
import EmailInput from "@/Components/Form/EmailInput.vue";
import LoginForm from "@/Pages/Events/Partials/Invite/LoginForm.vue";
import BaseButton from "@/Components/Base/BaseButton.vue";

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
        {},
        {
            onSuccess: () => {
                resetForm();
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
    <div class="w-full bg-white -mt-14 -mb-6">
        <form
            @submit.prevent="submitRegisterForm"
        >
            <div>
                <p class="text-center text-lg text-gray-600" v-if="userIsLoggedIn">
                    You are already logged in. You can join this event by clicking the button below.
                </p>
                <div v-else class="flex flex-col justify-center w-full items-center bg-white gap-3 px-2 md:px-8">
                    <LoginForm :event="event"/>
                    <div class="w-full border-t border-gray-200 my-2"></div>
                    <div class="space-y-2 w-full">
                        <p class="text-center text-lg">
                            Or join as a guest user!
                        </p>
                        <TextInput
                            id="name"
                            placeholder="Enter your name"
                            label="Enter your name"
                            :model-value="form.name"
                            :error-message="errors.name"
                        />
                        <EmailInput
                            id="email"
                            label="Your Email"
                            v-model=form.email
                            placeholder="party@dos.com"
                            :error-message="errors.email"
                        />
                    </div>
                    <BaseButton
                        type="submit"
                        label="Join the event!"
                        class="w-full mt-1"
                        @click="submitRegisterForm"
                        />

<!--                    <TextInput-->
<!--                        placeholder="Enter your Email"-->
<!--                        name="email"-->
<!--                        input-type="email"-->
<!--                        :icon="EnvelopeIcon"-->
<!--                        :model-value="form.email"-->
<!--                        :required="true"-->
<!--                        :error="errors.email"-->
<!--                        @update:modelValue="val => form.email = val"-->
<!--                        class="w-full"-->
<!--                    />-->
                </div>
            </div>
        </form>
    </div>
</template>
