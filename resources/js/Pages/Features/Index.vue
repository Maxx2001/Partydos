<script setup>
import DefaultLayout from "../../Layouts/DefaultLayout.vue";
import PageComingSoon from "@/Components/ComingSoon/PageComingSoon.vue";
import {useTitle} from "@/Composables/useTitle.js";

useTitle('Features | Partydos');
</script>

<template>
    <DefaultLayout>
       <PageComingSoon page-title="Features"/>
    </DefaultLayout>
</template>
