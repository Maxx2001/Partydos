<script setup>
import BaseShareButton from "@/Components/Base/BaseShareButton.vue";
import {computed} from "vue";

const props = defineProps({
    event: {
        type: Object,
        required: true
    },
});

const eventTitle = computed(() => {
    return `Je bent uitgenodigd voor ${props.event.title}!`;
});

const eventDescription = computed(() => {
    let description = '';

    if (props.event.description) {
        description = props.event.description;
    }

    if (props.event?.address?.address) {
        return `${description} - ${props.event.address.address}`;
    }

    return description;
});
</script>

<template>
    <BaseShareButton
        class="md:hidden"
        :share-title="eventTitle"
        :share-text="eventDescription"
    >
        Share Event
    </BaseShareButton>
</template>
