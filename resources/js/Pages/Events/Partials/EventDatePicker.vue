<script setup>
import BaseButton from "@/Components/Base/BaseButton.vue";
import DateTimeInput from "@/Components/Inputs/DateTimeInput.vue";
import { ref } from "vue";
import CheckboxInput from "@/Components/Inputs/CheckboxInput.vue";
import DatePicker from "@/Pages/Events/Partials/DatePicker.vue";

const props = defineProps({
    form: {
        type: Object,
        required: true
    },
    showEndDateInput: {
        type: Boolean,
        default: false,
    }
});

const dateTimeError = ref('');
const emit = defineEmits(['submitEventDetails', 'returnToPreviousStep', 'update:modelValue']);
</script>

<template >
    <div class="w-full flex justify-center text-2xl font-semibold">
        <h1>
            Pick a date.
        </h1>
    </div>
    <div class="w-full flex flex-col justify-end mt-4">
        <DateTimeInput
            :modelValue="props.form.startDateTime"
            :required="true"
            @update:modelValue="val => form.startDateTime = val"
            name="date"
            input-title="Date and time"
            :error="dateTimeError"
        />
        <CheckboxInput
            @input="$emit('update:modelValue', $event.target.checked)"
            :modelValue="showEndDateInput"
            class="pt-1 pb-4"
            label="End date & time"
            labelText="Check this box if your event has an end date and time."
        />
        <transition
            name="slide-open"
            enter-active-class="transition-all duration-300 ease-in-out"
            leave-active-class="transition-all duration-300 ease-in-out"
            enter-from-class="max-h-0 opacity-0"
            enter-to-class="max-h-60 opacity-100"
            leave-from-class="max-h-60 opacity-100"
            leave-to-class="max-h-0 opacity-0"
        >
            <div v-show="showEndDateInput" class="overflow-hidden">
                <DateTimeInput
                    :modelValue="props.form.endDateTime"
                    :required="false"
                    @update:modelValue="val => form.endDateTime = val"
                    name="date"
                    input-title="End date and time"
                    :error="dateTimeError"
                />
            </div>
        </transition>
        <div class="w-full flex justify-end mt-4">
            <BaseButton
                label="Back to event details"
                @click="emit('returnToPreviousStep')"

            />
            <BaseButton
                @click="emit('submitEventDetails')"
                class="ml-5"
            />
        </div>
    </div>
</template>
