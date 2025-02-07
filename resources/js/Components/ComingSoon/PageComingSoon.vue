<script setup>

defineProps({
    pageTitle: {
        type: String,
        required: true,
    },
});
</script>

<template>
    <section class="relative bg-gradient-to-br from-blue-500 to-purple-600 text-white py-12 md:py-24 px-6 h-96">
        <div class="max-w-6xl mx-auto flex flex-col md:flex-row items-center justify-start h-full">
            <!-- Text Content (2/3 width) -->
            <div
                class="w-full lg:w-2/3  lg:px-0 h-full flex flex-col justify-center space-y-6 text-center lg:text-left"
                data-aos="fade-up"
                data-aos-duration="1000"
            >
                <h1 class="text-2xl md:text-5xl font-extrabold leading-tight">
                   {{ pageTitle }}
                </h1>
                <span>
                    We are working on this page!
                </span>
            </div>
        </div>

        <!-- Static Decorative Shapes -->
        <div class="absolute top-0 left-0 w-24 h-24 bg-white/20 rounded-full blur-lg"></div>
    </section>
</template>
