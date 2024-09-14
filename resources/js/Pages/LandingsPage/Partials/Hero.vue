<template>
    <section class="relative bg-gradient-to-br from-blue-500 to-purple-600 text-white py-12 md:py-24 px-6">
        <div class="max-w-7xl mx-auto flex flex-col md:flex-row items-center justify-start h-full">
            <!-- Text Content (2/3 width) -->
            <div
                class="w-full md:w-2/3 h-full flex flex-col justify-center space-y-6"
                data-aos="fade-up"
                data-aos-duration="1000"
            >
                <h1 class="text-2xl md:text-6xl font-extrabold leading-tight text-center md:text-left hidden md:block">
                    Plan Your Events Effortlessly with
                    <span class="">
                        Partydos
                    </span>
                </h1>
                <h2 class="text-xl md:text-xl text-center md:text-left">
                    Effortlessly
                    <span class="text-yellow-400 font-extrabold">
                        plan events
                    </span>,
                    <span class="text-teal-300 font-extrabold">
                        send personalized invites
                    </span>,
                    <span class="text-pink-300 font-extrabold">
                        manage attendees
                    </span>,
                        and organize your schedule with
                    <span class="text-orange-400 font-extrabold">
                        dynamic polls.
                    </span>
                </h2>

                <!-- Call to Action -->
                <div
                    class="flex space-x-4 mt-6 justify-center md:justify-start"
                    data-aos="fade-up"
                    data-aos-duration="1000"
                    data-aos-delay="200"
                >
                    <BaseOutlineButton
                        @click="router.get(route('events.create'))"
                        label="Create Event!"
                    />
                </div>
            </div>

            <!-- Image Content (1/3 width) -->
            <div
                class="hidden md:flex md:w-5/12 h-full items-center justify-center"
                data-aos="fade-left"
                data-aos-duration="1000"
                data-aos-delay="400"
            >
                <img :src="heroImage" alt="Event Planning Illustration" class="w-full h-auto rounded-lg shadow-lg"/>
            </div>
        </div>

        <!-- Static Decorative Shapes -->
        <div class="absolute top-0 left-0 w-24 h-24 bg-white/20 rounded-full blur-lg"></div>
    </section>
</template>

<script setup lang="ts">
import { router } from "@inertiajs/vue3";
import heroImage from "@/Assets/heroImage.webp";

// Import AOS styles and initialize AOS
import AOS from "aos";
import "aos/dist/aos.css";
import BaseOutlineButton from "../../../Components/Base/BaseOutlineButton.vue";

AOS.init();
</script>

<style scoped>
/* Removed pulsing animations; background shapes remain static */
</style>
