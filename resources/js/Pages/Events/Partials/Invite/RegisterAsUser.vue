<script setup>
import BaseButton from "@/Components/Base/BaseButton.vue";
import {router, usePage} from "@inertiajs/vue3";
import ProfilePicture from "@/Components/Profile/ProfilePicture.vue";

const emit = defineEmits(['registerSuccess']);

const props = defineProps({
    event: {
        type: Object,
        required: true,
    },
});

const submitAuthenticated = () => {
    return router.post(
        route('events.accept-invite', props.event.uniqueIdentifier),
        {},
        {
            onSuccess: () => {
                emit('registerSuccess');
            },
        }
    );
}

const user = usePage().props.auth.user;
</script>

<template>
    <p class="text-center md:text-left text-xl md:text-3xl font-bold">
        Accept event invite
    </p>
    <div class="py-4 flex flex-col justify-center items-center">
        <ProfilePicture :image-url="user.profile_photo_url"/>
        <p class="font-semibold text-lg pt-6 pb-2 px-4 text-center">
            Hi <span class="text-indigo-600">{{ user.name }}</span>, do you want to accept this invite?
        </p>
        <hr class="border-blue-500 w-full"/>
        <hr class="border-blue-500 w-full"/>
    </div>

    <BaseButton
        type="button"
        label="Join event!"
        class="w-full"
        @click="submitAuthenticated"
    />
</template>
