<script setup>
import DefaultLayout from "@/Layouts/DefaultLayout.vue";
import PageHeader from "@/Components/Layout/PageHeader.vue";
import PageContainer from "@/Components/Layout/PageContainer.vue";
import FormAutoUpdate from "@/Components/Form/FormAutoUpdate.vue";
import TextInput from "@/Components/Form/TextInput.vue";
import EmailInput from "@/Components/Form/EmailInput.vue";

const props = defineProps({
    user: {
        type: Object,
        required: true
    }
});
</script>

<template>
    <DefaultLayout>
        <PageHeader>
            Your <span class="text-yellow-500">profile</span>
        </PageHeader>
        <PageContainer>
            <FormAutoUpdate :route="route('profile.update')" :data="user">
                <TextInput
                    id="name"
                    label="Name"
                    :model-value="user.name"
                    v-model="user.name"
                />
                <EmailInput
                    id="email"
                    label="Email"
                    :model-value="user.email"
                    v-model="user.email"
                    class="pt-4"
                />
            </FormAutoUpdate>
        </PageContainer>
    </DefaultLayout>
</template>
