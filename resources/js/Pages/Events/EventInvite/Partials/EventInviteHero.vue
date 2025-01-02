<script setup>
import BaseOutlineButton from "@/Components/Base/BaseOutlineButton.vue";
import EventBanner from "@/Pages/Events/EventInvite/Partials/EventBanner.vue";
import { getFormattedEventDateMessage } from "@/Helpers/getFormattedEventDateMessage";
import {PencilSquareIcon} from "@heroicons/vue/20/solid/index.js";
import {Link} from "@inertiajs/vue3";

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
        class="relative bg-gradient-to-br from-blue-600 to-purple-800 text-white pt-10 md:py-24 px-6 pb-4"
    >
        <div class="max-w-5xl mx-auto flex flex-col md:flex-row items-center justify-start h-full">
            <div
                class="w-full lg:px-0 h-full flex flex-col justify-center space-y-1 md:space-y-4"
                data-aos="fade-up"
                data-aos-duration="1000"
            >
                <div class="text-3xl md:text-5xl font-extrabold leading-tight text-center lg:text-left flex flex-col md:flex-row justify-center mb-4">
                    <span v-if="event.canEdit" class="lg:mb-24">
                        You are organizing:
                    </span>
                    <span v-else>
                        You are invited to:
                    </span>
                    <span class="pl-2 text-yellow-300">
                        {{ event.title }}
                    </span>
                    <Link :href="route('events.edit', { event: event.uniqueIdentifier })" class="hidden lg:block">
                        <PencilSquareIcon v-if="event.canEdit" class="pl-6 h-12"/>
                    </Link>
                </div>
                <div class="md:hidden flex flex-col text-center md:text-left text-xl space-y-2 md:space-y-4">
                    <p class="font-bold">
                        At:
                        <span class="text-pink-300">
                            {{ event.location || 'No event location set.' }}
                        </span>
                    </p>
                    <p class="font-semibold pt-1">
                        <span class="text-blue-200">
                            {{ getFormattedEventDateMessage(event) }}
                        </span>
                    </p>
                </div>

                <EventBanner class="block md:hidden" :event="event"/>
                <div class="flex justify-end lg:hidden pt-2">
                    <Link :href="route('events.edit', { event: event.uniqueIdentifier })" class="flex justify-end w-1/4 pr-2">
                        <PencilSquareIcon v-if="event.canEdit" class="h-7"/>
                    </Link>
                </div>
            </div>
        </div>
        <div class="absolute top-0 left-0 w-24 h-24 bg-white/20 rounded-full blur-lg"></div>

        <!-- Button Section -->
        <div class="flex md:hidden justify-center pt-4 pb-8" v-if="showInviteButton">
            <BaseOutlineButton
                label="Join event!"
                @click="emits('acceptEventInvite')"
                class="bg-blue-700 font-bold py-3 px-6 rounded-md hover:bg-blue-800 transition duration-300 ease-in-out"
            />
        </div>
    </section>
</template>
