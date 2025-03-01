<script setup>
import { ref } from "vue";
import DefaultLayout from "@/Layouts/DefaultLayout.vue";
import PageHeader from "@/Components/Layout/PageHeader.vue";
import PageContainer from "@/Components/Layout/PageContainer.vue";
import FormAutoUpdate from "@/Components/Form/FormAutoUpdate.vue";
import TextInput from "@/Components/Form/TextInput.vue";
import EmailInput from "@/Components/Form/EmailInput.vue";
import ProfilePicture from "@/Components/Profile/ProfilePicture.vue";
import { useTitle } from "@/Composables/useTitle.js";

useTitle('Profile | Partydos');

const props = defineProps({
    user: {
        type: Object,
        required: true
    }
});

const form = ref({
    name: props.user.name,
    email: props.user.email,
    profile_photo: null
});

const handleProfileImageUpload = (file) => {
    form.value.profile_photo = file;
};
</script>

<template>
    <DefaultLayout>
        <PageHeader>
            Your profile
        </PageHeader>
        <PageContainer>
            <FormAutoUpdate :route="route('profile.update')" :data="form" class="py-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="col-span-1 md:col-span-2 flex justify-center">
                        <ProfilePicture
                            :image-url="user.profile_photo_url"
                            class="h-32 w-32"
                            :can-edit="true"
                            @fileUploaded="handleProfileImageUpload"
                        />
                    </div>
                    <TextInput
                        id="name"
                        label="Name"
                        :model-value="form.name"
                        v-model="form.name"
                    />
                    <EmailInput
                        id="email"
                        label="Email"
                        :model-value="form.email"
                        v-model="form.email"
                    />
                </div>
            </FormAutoUpdate>
        </PageContainer>
    </DefaultLayout>
</template>
