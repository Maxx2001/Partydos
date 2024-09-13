<script setup>
// Import AOS styles and initialize AOS
import AOS from "aos";
import "aos/dist/aos.css";

// Initialize AOS with settings
AOS.init({
    duration: 800, // Default duration for all animations
    once: true,     // Animations should happen only once when scrolled into view
    offset: 120,    // Offset from the original trigger point
});
</script>

<template>
    <div class="flex flex-col items-center justify-center py-16 md:py-20 px-6 bg-gradient-to-b from-blue-50 to-white">
        <h1 class="text-4xl font-bold pb-12 text-gray-900">
            Easily add events to your calendar!
        </h1>

        <!-- Tabs Navigation with AOS Load-In Animation -->
        <div class="w-full max-w-2xl bg-white rounded-lg shadow-lg overflow-hidden" data-aos="fade-up" data-aos-duration="1500" data-aos-easing="ease-in-out">
            <div class="flex divide-x divide-gray-200">
                <!-- Tab: Apple -->
                <div class="flex-1 py-6 text-center text-lg md:text-xl font-medium bg-blue-500 text-white cursor-pointer transition-colors duration-300 ease-in-out hover:animate-pulse">
                    Apple
                </div>
                <!-- Tab: Google Calendar -->
                <div class="flex-1 py-6 text-center text-lg md:text-xl font-medium text-gray-600 hover:bg-blue-100 cursor-pointer transition-colors duration-300 ease-in-out hover:animate-pulse">
                    Google
                </div>
                <!-- Tab: Hotmail -->
                <div class="flex-1 py-6 text-center text-lg md:text-xl font-medium text-gray-600 hover:bg-blue-100 cursor-pointer transition-colors duration-300 ease-in-out hover:animate-pulse">
                    Hotmail
                </div>
                <!-- Tab: Anders -->
                <div class="flex-1 py-6 text-center text-lg md:text-xl font-medium text-gray-600 hover:bg-blue-100 cursor-pointer transition-colors duration-300 ease-in-out hover:animate-pulse">
                    Other
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
/* Add any additional styles here if needed */
</style>
