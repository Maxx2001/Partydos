<script setup>
import BaseOutlineButton from "@/Components/Base/BaseOutlineButton.vue";
import EventBanner from "@/Pages/Events/EventInvite/Partials/EventBanner.vue";
import { getFormattedEventDateMessage } from "@/Helpers/getFormattedEventDateMessage";

const props = defineProps({
    event: {
        type: Object,
        required: true
    },
    showInviteButton: {
        type: Boolean,
        default: true,
    },
});

const emits = defineEmits(['acceptEventInvite']);
</script>

<template>
    <section
        class="relative bg-gradient-to-br from-blue-600 to-purple-800 text-white py-10 md:py-24 px-6"
        :class="showInviteButton ? '' : 'pb-8 lg:pb-0'"
    >
        <div class="max-w-5xl mx-auto flex flex-col md:flex-row items-center justify-start h-full pb-8">
            <div
                class="w-full lg:px-0 h-full flex flex-col justify-center space-y-1 md:space-y-4"
                data-aos="fade-up"
                data-aos-duration="1000"
            >
                <h1 class="text-3xl md:text-5xl font-extrabold leading-tight text-center lg:text-left flex flex-col md:flex-row justify-center mb-4">
                    You are invited to:
                    <span class="pl-2 text-yellow-300">
                        {{ event.title }}
                    </span>
                </h1>
                <div class="md:hidden flex flex-col text-center md:text-left text-xl space-y-2 md:space-y-4">
                    <p class="font-bold">
                        At:
                        <span class="text-pink-300">
                            {{ event.location || 'No event location set.' }}
                        </span>
                    </p>
                    <p class="font-semibold pt-1">
<!--                        Event starts at:-->
                        <span class="text-blue-200">
                            {{ getFormattedEventDateMessage(event) }}
                        </span>
                    </p>
                </div>
                <!-- Event Banner for Mobile -->
                <EventBanner class="block md:hidden pt-2 md:pt-0" :event="event"/>
            </div>
        </div>
        <div class="absolute top-0 left-0 w-24 h-24 bg-white/20 rounded-full blur-lg"></div>

        <!-- Button Section -->
        <div class="flex md:hidden justify-center pt-4" v-if="showInviteButton">
            <BaseOutlineButton
                label="Join event!"
                @click="emits('acceptEventInvite')"
                class="bg-blue-700 font-bold py-3 px-6 rounded-md hover:bg-blue-800 transition duration-300 ease-in-out"
            />
        </div>
    </section>
</template>
