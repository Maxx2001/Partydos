<script setup>
import DefaultLayout from "@/Layouts/DefaultLayout.vue";
import Hero from "@/Pages/LandingsPage/Partials/Hero.vue";
import 'swiper/swiper-bundle.css';
import FeatureList from "@/Pages/LandingsPage/Partials/FeatureList.vue";
import AgendaTabs from "@/Pages/LandingsPage/Partials/AgendaTabs.vue";
import {useTitle} from "@/composables/useTitle.js";

useTitle('Partydos | Home');

</script>

<template>
    <DefaultLayout>
        <Hero />
        <FeatureList/>
        <AgendaTabs/>
    </DefaultLayout>
</template>
