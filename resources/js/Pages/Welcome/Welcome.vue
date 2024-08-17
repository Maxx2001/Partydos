<script setup>

import DefaultLayout from "@/Layouts/DefaultLayout.vue";
import Hero from "@/Pages/Welcome/Partials/Hero.vue";
</script>

<template>
    <DefaultLayout>
        <Hero/>
        <div class="py-32 px-6 border flex flex-col items-center justify-center">
            <h1 class="text-3xl font-bold pb-4">
                Functies
            </h1>
            <p class="text-xl pb-2">
                Functies regel 2
            </p>
            <p>
                Functies regel 3
            </p>
        </div>
        <div class="py-32 px-6 border flex flex-col items-center justify-center bg-slate-100">
            <h1 class="text-3xl font-bold pb-4">
                Hoe het werkt
            </h1>
            <p class="text-xl pb-2">
                Hoe het werkt regel 2
            </p>
            <p>
                Hoe het werkt regel 3
            </p>
        </div>
    </DefaultLayout>
</template>
