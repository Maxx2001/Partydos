<script setup>
import {useForm} from "@inertiajs/vue3";
import {ref} from "vue";
import EmailInput from "@/Components/Form/EmailInput.vue";
import SubmitButton from "@/Components/Form/SubmitButton.vue";
import PasswordInput from "@/Components/Form/PasswordInput.vue";

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
    <form @submit.prevent="handleSubmit" class="space-y-3 w-full">
        <p class="text-center text-lg">
            Login and accept this invite!
        </p>
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
            <div>
<!--                <label for="remember" class="flex justify-center items-center py-4">-->
<!--                    <input-->
<!--                        type="checkbox"-->
<!--                        name="remember"-->
<!--                        id="remember"-->
<!--                        v-model="form.remember"-->
<!--                        class="mr-2 rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800"-->
<!--                    >-->
<!--                    Remember-->
<!--                    <span class="text-blue-500 font-bold pl-1">-->
<!--                        Me!-->
<!--                    </span>-->
<!--                </label>-->
                <SubmitButton label="Login and join the event!" class="mt-4"/>
            </div>
        </div>
    </form>
</template>

