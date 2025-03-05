<script setup>
import {useForm} from "@inertiajs/vue3";
import {ref} from "vue";
import EmailInput from "@/Components/Form/EmailInput.vue";
import PasswordInput from "@/Components/Form/PasswordInput.vue";
import BaseButton from "@/Components/Base/BaseButton.vue";

const props = defineProps({
    event: {
        type: Object,
        required: true,
    }
})

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
    })).post(route('event.authenticate', props.event.uniqueIdentifier), {
        onError: () => {
            form.reset('password');
            formErrors.value = 'Invalid email or password.';
        },
        onFinish: () => {
            form.reset('password');
        },
        onSuccess: () => {
            emit('registerSuccess');
        },
    });
};

const emit = defineEmits(['showLoginForm', 'registerSuccess']);
</script>

<template>
    <form @submit.prevent="handleSubmit" class="space-y-6 w-full">
        <p class="text-center md:text-left text-xl md:text-3xl font-bold">
            <span class="text-indigo-600">
                Login
            </span>
            and
            <span class="text-indigo-600">
                accept
            </span>
             this invite!
        </p>
        <EmailInput
            id="email"
            label="Your Email"
            v-model=form.email
            placeholder="party@dos.com"
            :error-message="form.errors.email"
        />
            <PasswordInput
                id="password"
                label="Your Password"
                :model-value="form.password"
                v-model=form.password
                placeholder="********"
                :error-message="form.errors.password"
            />
            <label for="remember" class="flex justify-center items-center md:justify-start">
                <input
                    type="checkbox"
                    name="remember"
                    id="remember"
                    v-model="form.remember"
                    class="mr-2 rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800"
                >
                Remember
                <span class="text-blue-500 font-bold pl-1">
                    Me!
                </span>
            </label>
            <div class="space-y-4">
                <BaseButton
                    type="submit"
                    label="Login and join the event!"
                    class="w-full"
                />
                <hr class="border-blue-500 my-2 w-full">
                <BaseButton
                    type="button"
                    variant="submit"
                    label="Join event without account"
                    class="w-full"
                    @click="emit('showLoginForm')"
                />
            </div>
    </form>
</template>

