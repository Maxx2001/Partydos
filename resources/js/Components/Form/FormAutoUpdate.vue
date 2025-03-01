<script setup>
import { watch, toRefs } from "vue";
import { useForm } from "@inertiajs/vue3";

const props = defineProps({
    data: {
        type: Object,
        required: true
    },
    route: {
        type: [Object, String],
        required: true
    }
});

const { data } = toRefs(props);
const form = useForm({ ...data.value });
watch(data, (newData) => {
    Object.assign(form, newData);
}, { deep: true });

const updateFormData = () => {
    const formData = new FormData();

    for (const key in form) {
        if (form[key] instanceof File) {
            formData.append(key, form[key]);
        } else {
            formData.append(key, form[key]);
        }
    }

    form.post(props.route, {
        preserveScroll: true,
        forceFormData: true
    });
};
</script>

<template>
    <form @change="updateFormData" @keypress.enter="updateFormData" enctype="multipart/form-data">
        <slot/>
    </form>
</template>
