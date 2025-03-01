<script setup>
import {useForm} from "@inertiajs/vue3";

const props = defineProps({
    data: {
        type: Object,
        required: true,
    },
    route: {
        type: [Object, String],
        required: true
    },
});

const updateFormData = () => useForm(props.data).put(props.route);
// const updateFormData = () => props.data.put(props.route);

</script>

<template>
    <form @change="updateFormData" @keypress.enter="updateFormData">
        <slot/>
    </form>
</template>
