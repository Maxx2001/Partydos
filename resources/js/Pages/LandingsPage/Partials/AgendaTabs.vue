<script setup>
// Import AOS styles and initialize AOS
import AOS from "aos";
import "aos/dist/aos.css";

// Initialize AOS with settings
AOS.init({
    duration: 1000, // Default duration for all animations
    once: true,     // Animations should happen only once when scrolled into view
    offset: 40,    // Offset from the original trigger point
});
</script>

<template>
    <div class="flex flex-col items-center justify-center py-16 px-6 bg-gradient-to-b from-blue-50 to-white">
        <h1 class="text-2xl md:text-4xl font-bold pb-12 text-gray-900 text-center">
            Easily add events to your calendar!
        </h1>

        <div
            class="w-full max-w-2xl bg-white rounded-lg shadow-lg overflow-hidden"
             data-aos="fade-up"
             data-aos-duration="700"
             data-aos-easing="ease-in-out"
        >
            <div class="flex divide-x divide-gray-200">
                <div class="flex-1 py-6 text-center text-lg md:text-xl font-medium bg-blue-500 text-white cursor-pointer transition-colors duration-300 ease-in-out hover:animate-pulse">
                    Apple
                </div>
                <div class="flex-1 py-6 text-center text-lg md:text-xl font-medium text-gray-600 cursor-pointer transition-colors duration-200 ease-out hover:bg-blue-500 hover:text-white hover:shadow-lg hover:animate-pulse">
                    Google
                </div>
                <div class="flex-1 py-6 text-center text-lg md:text-xl font-medium text-gray-600 cursor-pointer transition-colors duration-200 ease-out hover:bg-blue-500 hover:text-white hover:shadow-lg hover:animate-pulse">
                    Hotmail
                </div>
                <div class="flex-1 py-6 text-center text-lg md:text-xl font-medium text-gray-600 cursor-pointer transition-colors duration-200 ease-out hover:bg-blue-500 hover:text-white hover:shadow-lg hover:animate-pulse">
                    Other
                </div>
            </div>
        </div>
    </div>
</template>
