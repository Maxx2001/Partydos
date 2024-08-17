<script setup lang="ts">
import {PlusCircleIcon} from "@heroicons/vue/20/solid";
import BaseButtonWithIcon from "../../../Components/Base/BaseButton.vue";
import {router} from "@inertiajs/vue3";
</script>

<template>
    <div class="py-32 px-6 border flex flex-col items-center justify-center bg-slate-100">
        <h1 class="text-3xl font-bold pb-4">
            Partydos
        </h1>
        <p class="text-xl pb-2">
            Plan moeiteloos je volgende evenement en nodig iedereen uit binnen enkele minuten.
        </p>
        <p>
            Onze tool maakt het eenvoudig om een datum te prikken, deelnemers uit te nodigen en alles bij te houden – allemaal op één plek.”
        </p>
        <div class="w-full md:w-2/3 xl:w-1/3">
            <BaseButtonWithIcon
                :icon="PlusCircleIcon"
                label="Create event"
                class="mt-6"
                @click="router.get(route('events.create'))"
            />
        </div>
    </div>
</template>
