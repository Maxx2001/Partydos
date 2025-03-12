<script setup>
import { ref, defineEmits, onMounted} from 'vue';
import {usePage} from '@inertiajs/vue3';
import LoginForm from "@/Pages/Events/Partials/Invite/LoginForm.vue";
import RegisterAsGuestForm from "@/Pages/Events/Partials/Invite/RegisterAsGuestForm.vue";
import RegisterAsUser from "@/Pages/Events/Partials/Invite/RegisterAsUser.vue";
import RegisterForm from "@/Pages/Events/Partials/Invite/RegisterForm.vue";
const props = defineProps({
    event: {
        type: Object,
        required: true,
    },
});

const emit = defineEmits(['registerSuccess']);

const userIsLoggedIn = ref(false);

onMounted(() => {
    userIsLoggedIn.value = usePage().props.auth.user !== null;
});

const showLoginForm = ref(true);

const showRegisterForm = ref(false);

const setRegisterForm = () => {
    showRegisterForm.value = true;
    showLoginForm.value = false;
};

const setLoginForm = () => {
    showRegisterForm.value = false;
    showLoginForm.value = true;
};
</script>

<template>
    <div class="w-full bg-white -mt-14">
        <form>
            <RegisterAsUser
                v-if="userIsLoggedIn"
                :event="event"
                @register-success="emit('registerSuccess')"
            />
            <div v-else class="flex flex-col justify-center w-full items-center bg-white gap-3 px-2 md:px-8">
                <LoginForm
                    :event="event"
                    v-if="showLoginForm"
                    @show-login-form="showLoginForm = false"
                    @register-success="emit('registerSuccess')"
                    @show-register-form="setRegisterForm()"
                />

                <RegisterAsGuestForm
                    v-if="!showLoginForm && !showRegisterForm"
                    @show-login-form="showLoginForm = true"
                    :event="event"
                    @register-success="emit('registerSuccess')"
                />

                <RegisterForm
                    v-if="showRegisterForm"
                    :event="event"
                    @show-login-form="setLoginForm()"
                    @register-success="emit('registerSuccess')"
                />
            </div>
        </form>
    </div>
</template>
