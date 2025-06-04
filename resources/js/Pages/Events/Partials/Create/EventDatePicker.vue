<script setup>
import DatePicker from "./DatePicker.vue";
import BaseButton from "@/Components/Base/BaseButton.vue";
import BaseOutlineButton from "@/Components/Base/BaseOutlineButton.vue";
import {ref} from 'vue';

const emits = defineEmits(['update', 'submitEventDetails', 'returnToPreviousStep']);

const dateObjects = ref([{}]);

const emitUpdates = () => {
    emits('update', dateObjects.value);
};

const updateDateObject = (index, obj) => {
    dateObjects.value[index] = obj;
    emitUpdates();
};

const addOption = () => {
    dateObjects.value.push({});
};

const removeOption = (index) => {
    dateObjects.value.splice(index,1);
    emitUpdates();
};
</script>
<template>
    <div class="w-full flex justify-center text-2xl font-semibold">
        <h1 class="text-2xl md:text-4xl text-center">
            When will this event take place?
        </h1>
    </div>

    <div v-for="(obj, index) in dateObjects" :key="index" class="mt-6 space-y-2">
        <DatePicker @update="(d) => updateDateObject(index, d)"/>
        <BaseOutlineButton v-if="dateObjects.length > 1" label="Remove" @click="removeOption(index)" class="mt-2"/>
    </div>
    <BaseButton label="Add date option" @click="addOption" class="mt-4"/>

    <div class="w-full flex justify-end mt-6 md:w-2/3 xl:w-1/3">
        <BaseOutlineButton label="Back to date picker" class="mr-4" @click="emits('returnToPreviousStep')"/>
        <BaseButton label="Submit dates" @click="emits('submitEventDetails')"/>
    </div>
</template>
