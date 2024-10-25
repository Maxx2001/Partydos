<script setup>

import heroImage from "@/Assets/heroImage.webp";
import AOS from "aos";
import "aos/dist/aos.css";
AOS.init();

</script>

<template>
    <div class="md:w-1/2">
        <div
            class="flex h-full items-center justify-center"
            data-aos="fade-right"
            data-aos-duration="1000"
            data-aos-delay="400"
        >
            <img :src="heroImage" alt="Event Planning Illustration" class="w-full h-auto rounded-xl"/>
        </div>
    </div>
</template>
