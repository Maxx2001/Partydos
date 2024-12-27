<script setup>
import heroImage from "@/Assets/heroImage.webp";
import AOS from "aos";
import "aos/dist/aos.css";
AOS.init();

const props = defineProps({
    event: {
        type: Object,
        required: true
    }
});
</script>

<template>
    <div class="md:w-1/2">
        <div
            class="flex h-full items-center justify-center"
            data-aos="fade-in"
            data-aos-duration="500"
            data-aos-delay="400"
            v-if="event.media.length"
        >
            <img :src="event.media[0].url" alt="Event Planning Illustration" class="w-4/6 h-auto rounded-xl"/>
        </div>
        <div
            class="flex h-full items-center justify-center"
            data-aos="fade-in"
            data-aos-duration="500"
            data-aos-delay="400"
            v-else
        >
            <img :src="heroImage" alt="Event Planning Illustration" class="w-full h-auto rounded-xl"/>
        </div>
    </div>
</template>
