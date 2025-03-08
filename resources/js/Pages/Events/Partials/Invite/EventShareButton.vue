<script setup>
import BaseShareButton from "@/Components/Base/BaseShareButton.vue";
import { computed } from "vue";

const props = defineProps({
    event: {
        type: Object,
        required: true
    },
});

const eventDescription = computed(() => {
    let description = `Je bent uitgenodigd voor ${props.event.title}`;

    if (props.event.description) {
        description = `${description}\n${props.event.description}`;
    }

    if (props.event?.address?.address) {
        description = `${description}\n${props.event.address.address}`;
    }

    return description;
});
</script>

<template>
    <BaseShareButton
        class="md:hidden"
        :share-text="eventDescription"
    >
        Share Event
    </BaseShareButton>
</template>
