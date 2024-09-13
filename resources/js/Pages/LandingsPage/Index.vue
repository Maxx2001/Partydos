<script setup>
import DefaultLayout from "@/Layouts/DefaultLayout.vue";
import Hero from "@/Pages/LandingsPage/Partials/Hero.vue";
import 'swiper/swiper-bundle.css';
import FeatureList from "@/Pages/LandingsPage/Partials/FeatureList.vue";
import AgendaTabs from "@/Pages/LandingsPage/Partials/AgendaTabs.vue";

</script>

<template>
    <DefaultLayout>
        <Hero />
        <FeatureList/>
        <AgendaTabs/>
    </DefaultLayout>
</template>
