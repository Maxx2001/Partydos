<script setup>
import {computed} from "vue";
import ProfileItem from "@/Pages/Events/Partials/Invite/ProfileItem.vue";
import ColorService from "@/Services/colorService.js";
import BaseButton from "@/Components/Base/BaseButton.vue";

const props = defineProps({
    invitedUsers: {
        type: Array,
        default: () => []
    },
    eventOwner: {
        type: Object,
        required: true
    },
    moveEventRegisterDown: {
        type: Boolean,
        required: false
    },
    isEventOwner: {
        type: Boolean,
        required: false
    },
    showAlreadySignedUpMessage:{
        type: Boolean,
        default: false
    },
    event: {
        type: Object,
        required: true
    },
});
const {getRandomBgColorFromString} = ColorService;

const totalGuests = computed(() => props.invitedUsers.length);

const guestMessage = computed(() => {
    if (props.showAlreadySignedUpMessage) {
        if (totalGuests.value === 1) {
            return 'Cool! You are the first that signed up!';
        } else if (totalGuests.value === 2) {
            return `Cool! You already signed up together with ${totalGuests.value - 1} friend.`;
        } else {
            return `Cool! You already signed up together with ${totalGuests.value - 1} friends.`;
        }
    } else if (totalGuests.value === 0) {
        return props.isEventOwner ? 'Start inviting friends!' : 'Be the first to sign-up!';
    } else if (totalGuests.value === 1) {
        return '1 Friend already signed up!';
    } else {
        return `${totalGuests.value} Friends already signed up!`;
    }
});

const emit = defineEmits(['openAddToCalendarModal']);
</script>

<template>
    <div class="flex flex-col items-center lg:mt-3 mt-6">
        <div class="w-full lg:max-w-6xl mx-4 pt-4 lg:pt-0">
            <p
                v-if="event.canceledAt"
                class="text-2xl lg:text-5xl font-bold text-red-500 text-center pb-6 lg:hidden"
            >
                This event has been canceled.
            </p>
            <p class="lg:text-3xl text-2xl text-indigo-700 font-semibold text-center lg:hidden">
                Organised by
            </p>
            <ProfileItem
                class="lg:hidden"
                :participant="eventOwner"
                :bgColor="getRandomBgColorFromString(eventOwner.name)"
            />
            <p class="lg:text-3xl text-2xl text-indigo-700 px-10 font-semibold text-center mt-2 lg:mt-0">
                {{ guestMessage }}
            </p>
            <div class="lg:grid grid-cols-8">
                <div class="grid-cols-1 hidden items-center lg:flex-row h-32 lg:flex">
                    <div>
                        <span class="text-lg text-indigo-600 mb-3">
                            Organised by
                        </span>
                        <ProfileItem
                            :participant="eventOwner"
                            :bgColor="getRandomBgColorFromString(eventOwner.name)"
                        />
                    </div>
                    <div class="border-2 border-gray-300 h-32 ml-4"></div>
                </div>
                <div class="flex w-full items-center mt-2 col-span-6 px-10">
                    <ul class="grid lg:grid-cols-7 grid-cols-4 w-full gap-4 justify-items-center mt-4 lg:mx-0">
                        <ProfileItem
                            v-for="(participant, index) in invitedUsers"
                            :key="index"
                            :participant="participant"
                            :bgColor="getRandomBgColorFromString(participant.name)"
                        />
                    </ul>
                </div>
                <div class=" flex justify-center mt-4 lg:mt-0">
                    <BaseButton
                        label="Add to Calendar"
                        @click="emit('openAddToCalendarModal')"
                        extraClasses="text-xl"
                        class="mt-3 lg:h-24"
                    />
                </div>
            </div>
        </div>
    </div>
</template>
