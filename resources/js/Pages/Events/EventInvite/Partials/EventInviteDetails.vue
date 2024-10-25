<script setup>
import heroImage from "@/Assets/heroImage.webp";

import AOS from "aos";
import "aos/dist/aos.css";
import {computed} from "vue";
import BaseOutlineButton from "@/Components/Base/BaseOutlineButton.vue";

const props = defineProps({
    event: {
        type: Object,
        required: true,
    },
});

AOS.init();

const formattedStartDate = computed(() => {
    return props.event.startDateTime ? formatDate(props.event.startDateTime) : 'No start date set.';
});

const formatDate = (dateString) => {
    const options = {
        day: 'numeric',
        month: 'long',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
    };
    return new Intl.DateTimeFormat('en-GB', options).format(new Date(dateString));
};

const emits = defineEmits(['acceptEventInvite']);
</script>

<template>
    <div class="flex items-center justify-center">
        <div class="max-w-7xl flex py-12 flex-row">
            <div class="w-1/2">
                <div
                    class="flex h-full items-center justify-center"
                    data-aos="fade-right"
                    data-aos-duration="1000"
                    data-aos-delay="400"
                >
                    <img :src="heroImage" alt="Event Planning Illustration" class="w-full h-auto shadow-lg p-0"/>
                </div>
            </div>
            <div
                class="w-1/2 bg-gradient-to-br from-blue-500 to-purple-600 text-white font-extrabold leading-tight px-0 text-2xl flex flex-col items-center justify-center"
            >
                <div class="flex flex-col pb-20">
                    <p>
                        Location:
                        <span class="ml-4 text-yellow-400">
                            {{ event.location || 'No event location set.' }}
                        </span>
                    </p>
                    <p class="pt-3">
                        Event starts at:
                        <span class="ml-4 text-pink-300">
                            {{ formattedStartDate }}
                        </span>
                    </p>
                </div>
                <div class="flex justify-center">
                    <BaseOutlineButton label="Join event!" @click="emits('acceptEventInvite')"/>
                </div>
            </div>
        </div>
    </div>
</template>

