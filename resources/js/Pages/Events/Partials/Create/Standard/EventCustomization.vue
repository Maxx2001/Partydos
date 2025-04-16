<script setup>
import FileUpload from "@/Components/Form/FileUpload.vue";
import BaseOutlineButton from "@/Components/Base/BaseOutlineButton.vue";
import BaseButton from "@/Components/Base/BaseButton.vue";

const emits = defineEmits(['update']);

</script>

<template>
    <div class="w-full flex justify-center text-2xl font-semibold">
        <h1 class="text-2xl md:text-4xl text-center">
            Customize your event invite!
        </h1>
    </div>
    <div class="flex flex-col items-center w-full mt-6">
        <div class="w-full md:w-2/3 xl:w-1/3 flex flex-col items-center gap-4">
            <FileUpload
                class="md:mt-12"
                @fileUploaded="emits('update', $event)"
            />
        </div>
    </div>
    <div class="w-full flex justify-end mt-6 md:w-2/3 xl:w-1/3">
        <BaseOutlineButton label="Back to date picker" class="mr-4" @click="emits('returnToPreviousStep')"/>
        <BaseButton label="Create event" @click="emits('submitEventDetails')"/>
    </div>
</template>
