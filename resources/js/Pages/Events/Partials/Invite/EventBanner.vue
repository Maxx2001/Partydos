<script setup>
import AOS from "aos";
import "aos/dist/aos.css";
import { ref, onMounted } from "vue";

AOS.init();

const props = defineProps({
    event: {
        type: Object,
        required: true
    }
});

const imageOrientation = ref("horizontal");

const checkImageOrientation = () => {
    const img = new Image();
    img.src = props.event.media[0]?.url;
    img.onload = () => {
        imageOrientation.value = img.width >= img.height ? "horizontal" : "vertical";
    };
};

onMounted(() => {
    if (props.event.media.length) {
        checkImageOrientation();
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
            <img
                :src="event.media[0].url"
                alt="Event Planning Illustration"
                class="mt-4 rounded-xl"
                :class="imageOrientation === 'horizontal' ? 'w-10/12 h-auto' : 'w-auto h-96'"
            />
        </div>
    </div>
</template>
