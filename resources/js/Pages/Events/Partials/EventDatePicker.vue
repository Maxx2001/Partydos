<script setup>
import DatePicker from "../Partials/DatePicker.vue";
import BaseButton from "@/Components/Base/BaseButton.vue";
import BaseOutlineButton from "@/Components/Base/BaseOutlineButton.vue";

const emits = defineEmits(['update', 'submitEventDetails', 'returnToPreviousStep']);

const emitSetDateObject = (dateObject) => {
    emits('update', dateObject);
}
</script>
<template>
    <div class="w-full flex justify-center text-2xl font-semibold">
        <h1 class="text-3xl">
            When will the event take place?
        </h1>
    </div>

    <DatePicker @update="emitSetDateObject($event)" class="mt-6"/>

    <div class="w-full flex justify-end mt-6 md:w-2/3 xl:w-1/3">
        <BaseOutlineButton label="Back to date picker" class="mr-4" @click="emits('returnToPreviousStep')"/>
        <BaseButton label="Submit date" @click="emits('submitEventDetails')"/>
    </div>
</template>
