<script setup>
import {computed} from "vue";
import GuestItem from "@/Pages/Events/EventInvite/Partials/GuestItem.vue";
import ColorService from "@/Services/colorService.js";

const props = defineProps({
    guestUsers: {
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
    }
});
const {getRandomBgColorFromString} = ColorService;

const totalGuests = computed(() => props.guestUsers.length + 1);

const guestMessage = computed(() => {
    return totalGuests.value === 1
        ? `${totalGuests.value} Friend already signed up!`
        : `${totalGuests.value} Friends already signed up!`;
});
</script>

<template>
    <div class="flex flex-col items-center lg:mt-3 mt-10">
        <div class="w-full lg:max-w-6xl mx-4">
            <p class="lg:text-3xl text-2xl text-indigo-700 font-semibold text-center">
                {{ guestMessage }}
            </p>
            <div class="flex w-full items-center mt-2">
                <ul class="grid lg:grid-cols-8 grid-cols-4 w-full gap-4 justify-items-center mt-4 mx-4 lg:mx-0">
                    <GuestItem :name="eventOwner.name" :bgColor="getRandomBgColorFromString(eventOwner.name)"/>
                    <GuestItem
                        v-for="(participant, index) in guestUsers"
                        :key="index"
                        :name="participant.name"
                        :bgColor="getRandomBgColorFromString(participant.name)"
                    />
                </ul>
            </div>
        </div>
    </div>
</template>
