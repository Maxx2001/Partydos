<script setup>
import TextInput from "@/Components/Inputs/TextInput.vue";
import TextAreaInput from "@/Components/Inputs/TextAreaInput.vue";
import BaseButton from "@/Components/Base/BaseButton.vue";
import { ref } from "vue";
import {router} from "@inertiajs/vue3";
import BaseOutlineButton from "@/Components/Base/BaseOutlineButton.vue";
import {
    CalendarIcon, EnvelopeIcon,
    GlobeAltIcon,
    LightBulbIcon,
    ShoppingCartIcon,
    SparklesIcon, StarIcon
} from "@heroicons/vue/20/solid/index.js";
import FeatureBox from "@/Components/Base/FeatureBox.vue";

const props = defineProps({
    form: {
        type: Object,
        required: true
    },
});

const emit = defineEmits(['submitEventDetails']);
const titleErrorBag = ref('');

const submitEventDetails = () => {
    if ( !props.form.title ) {
        titleErrorBag.value = 'Title is required.';
        return;
    }

    emit('submitEventDetails');
}

const features = [
    {
        title: 'Shopping List',
        description: 'Create a list of items that people need to bring to the event.',
        icon: ShoppingCartIcon,
        comingSoon: true,
    },
    {
        title: 'Date Picker',
        description: 'Easily choose a date and time for your event when it is not fixed.',
        icon: CalendarIcon,
        comingSoon: true,
    },
    {
        title: 'Activity Randomizer',
        description: 'Use AI integration to create a fun and engaging schedule for the event.',
        icon: SparklesIcon,
        comingSoon: true,
    },
    {
        title: 'Custom Event Invite Template',
        description: 'Design and send personalized event invitations with ease.',
        icon: StarIcon,
        comingSoon: true,
    },
    {
        title: 'Widget for Your Website',
        description: 'Integrate an event widget into your website for better engagement.',
        icon: GlobeAltIcon,
        comingSoon: true,
    },
    {
        title: 'Interactive Polls',
        description: 'Create interactive polls for attendees to vote on event activities or options.',
        icon: LightBulbIcon,
        comingSoon: true,
    },
];
</script>

<template>
    <div class="w-full flex justify-center text-2xl font-semibold">
        <h1 class="text-2xl md:text-4xl">
            What type of event is it?
        </h1>
    </div>
    <div class="flex flex-col items-center w-full pt-6">
        <div class="w-full md:w-2/3 xl:w-1/3 flex flex-col items-center gap-4">
            <TextInput
                :model-value="form.title"
                :required="true"
                @update:modelValue="val => form.title = val"
                name="title"
                placeholder="Fill in the title of the event"
                class="mx-2 w-full"
                :error="titleErrorBag"
            />

            <TextInput
                :model-value="form.location"
                @update:modelValue="val => form.location = val"
                name="location"
                placeholder="Where is it?"
                class="mx-2 w-full"
            />

            <TextAreaInput
                :model-value="form.description"
                @update:modelValue="val => form.description = val"
                name="description"
                placeholder="Describe the event"
                class="mx-2 w-full"
            />

            <div class="w-full flex justify-end mt-4">
                <BaseOutlineButton
                    label="Cancel"
                    class="mr-4"
                    @click="router.get(route('home'))"
                />
                <BaseButton
                    label="Pick a date"
                    @click="submitEventDetails"
                />
            </div>
        </div>

        <div class="pt-12">
            <h1 class="text-2xl font-bold pb-8 text-gray-900 text-center">
                What widgets do you want to use in your invite?
            </h1>
            <div class="grid grid-cols-1 xl:grid-cols-2 2xl:grid-cols-3 auto-cols-max gap-3 md:gap-8 md:px-16 xl:px-20 2xl:px-32 w-full">
                <FeatureBox
                    v-for="(feature, index) in features"
                    :key="index"
                    :icon="feature.icon"
                    :title="feature.title"
                    :description="feature.description"
                    :coming-soon="feature.comingSoon"
                />
            </div>
        </div>
    </div>
</template>
