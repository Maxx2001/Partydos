<script setup>
import { ref } from "vue";

const props = defineProps({
    event: {
        type: Object,
        required: true
    }
});
import { Link } from '@inertiajs/vue3'

const uniqueIdentifier = ref(props.event.uniqueIdentifier);
</script>


<template>
    <li class="rounded bg-blue-50 shadow">
        <Link :href="route('events.show-invite', {event: uniqueIdentifier})">
            <span class="p-2 flex justify-center">
                {{ event.title }} at {{ event.location }}
            </span>
        </Link>
    </li>
</template>
