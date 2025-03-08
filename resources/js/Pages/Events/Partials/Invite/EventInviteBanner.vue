<script setup>
import heroImage from "@/Assets/heroImage.webp";
import "aos/dist/aos.css";
import { onMounted } from "vue";
import AOS from "aos";
import BaseButton from "@/Components/Base/BaseButton.vue";
import { getFormattedEventDateMessage } from "@/Helpers/getFormattedEventDateMessage.js";
import GoogleMapsBlockIcon from "@/Components/Icons/GoogleMapsBlockIcon.vue";
import EventShareButton from "@/Pages/Events/Partials/Invite/EventShareButton.vue";

const props = defineProps({
    event: {
        type: Object,
        required: true,
    },
    showInviteButton: {
        type: Boolean,
        default: true,
    },
    showCancelButton: {
        type: Boolean,
        default: false,
    },
});

const emits = defineEmits(['acceptEventInvite', 'cancelEventInvite', 'openAddToCalendarModal']);
onMounted(() => {
    AOS.refresh();
});

const locationMessage = () => {
    if ( props.event.address) {
        return props.event.address.location || props.event.address.address;
    }

    return 'No event location set.';
}
</script>

<template>
    <div class="flex items-center justify-center bg-slate-100 py-12">
        <div class="max-w-6xl flex flex-row w-full">
            <div class="w-1/2 flex items-center justify-center">
                <div
                    v-if="event.media.length"
                    class="h-[380px] w-full bg-gray-200 rounded-l-lg overflow-hidden flex items-center justify-center shadow-lg"
                    style="aspect-ratio: 4 / 3;"
                >
                    <img
                        :src="event.media[0].url"
                        alt="Event Planning Illustration"
                        class="w-full h-full object-cover"
                        data-aos="fade-in"
                        data-aos-duration="1000"
                    />
                </div>
                <div
                    v-else
                    class="h-[380px] w-full bg-gray-200 rounded-l-lg overflow-hidden flex items-center justify-center shadow-lg"
                    style="aspect-ratio: 4 / 3;"
                >
                    <img
                        :src="heroImage"
                        alt="Event Planning Illustration"
                        class="w-full h-full object-cover"
                        data-aos="fade-in"
                        data-aos-duration="1000"
                    />
                </div>
            </div>
            <div
                class="w-1/2 h-[380px] bg-gradient-to-br from-blue-500  rounded-r-lg to-purple-600 text-white font-extrabold leading-tight text-2xl flex flex-col items-center justify-center"
            >
                <div class="flex flex-col pb-10 px-8">
                    <p class="flex">
                        Location:
                        <span class="ml-4 text-yellow-400">
                            {{ locationMessage() }}
                        </span>
                        <a v-if="event.address?.google_maps_ur" :href="event.address?.google_maps_url">
                            <GoogleMapsBlockIcon class="h-10 w-10"/>
                        </a>
                    </p>

                    <p class="pt-3">
                        <span class="ml-4 text-pink-300">
                           {{ getFormattedEventDateMessage(event) }}
                        </span>
                    </p>
                </div>
                <div class="flex justify-center flex-col">
                    <BaseButton
                        v-if="showInviteButton"
                        label="Join event!"
                        @click="emits('acceptEventInvite')"
                        class="h-12"
                    />
                    <EventShareButton
                        v-if="!isEventOwner"
                        :event="event"
                        variant="indigo"
                        class="mt-3"
                    />
                    <div class="hidden justify-center md:flex gap-x-2 pb-2">
                        <BaseButton
                            v-if="!showInviteButton"
                            label="Add to Calendar"
                            @click="emits('openAddToCalendarModal')"
                            extraClasses="text-2xl"
                            class="mt-3 h-14 w-42 rounded"
                        />
                        <BaseButton
                            v-if="showCancelButton"
                            label="Cancel Invite"
                            @click="emits('cancelEventInvite')"
                            class="h-14 mt-3"
                            variant="cancel"
                        />
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
