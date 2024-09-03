<script setup lang="ts">
import BaseButtonWithIcon from "../Components/Base/BaseButton.vue";
import { PlusCircleIcon, UserIcon, CalendarDaysIcon } from '@heroicons/vue/20/solid'
import { router } from '@inertiajs/vue3';

</script>

<template>
    <div>
        <header>
            <nav class="flex justify-between mx-6 2xl:mx-24 my-8">
                <div class="text-gray-500 italic">
                    // Logo
                </div>
                <div class="flex items-center w-full justify-end">
                    <BaseButtonWithIcon
                        :icon="PlusCircleIcon"
                        label="Create event"
                        class="mr-6"
                        @click="router.get(route('events.create'))"
                    />
                    <BaseButtonWithIcon
                        v-if="!$page.props.auth.user"
                        :icon="UserIcon"
                        @click="router.get(route('login'))"
                        label="Login"
                    />
                    <BaseButtonWithIcon
                        v-else
                        :icon="CalendarDaysIcon"
                        @click="router.get(route('events.index'))"
                        label="Events"
                    />
                </div>
            </nav>
        </header>
        <main>
            <slot />
        </main>
        <footer>
            <p>
                Footer stuff
            </p>
        </footer>
    </div>
</template>
