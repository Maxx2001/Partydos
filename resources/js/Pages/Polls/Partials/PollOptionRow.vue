<script setup>
import { computed } from "vue";

const props = defineProps({
    option: {
        type: Object,
        required: true,
    },
    voteMode: {
        type: String,
        required: true,
    },
    modelValue: {
        type: [Number, Array],
        default: null,
    },
    disabled: {
        type: Boolean,
        default: false,
    },
});

const emit = defineEmits(["update:modelValue"]);

const isChecked = computed(() => {
    if (props.voteMode === "multiple") {
        return Array.isArray(props.modelValue) && props.modelValue.includes(props.option.id);
    }

    return props.modelValue === props.option.id;
});

const toggle = () => {
    if (props.disabled) {
        return;
    }

    if (props.voteMode === "multiple") {
        const current = Array.isArray(props.modelValue) ? [...props.modelValue] : [];
        if (current.includes(props.option.id)) {
            emit("update:modelValue", current.filter((id) => id !== props.option.id));
            return;
        }
        emit("update:modelValue", [...current, props.option.id]);
        return;
    }

    emit("update:modelValue", props.option.id);
};
</script>

<template>
    <label
        class="flex items-start gap-3 p-3 rounded-xl border border-slate-200 hover:border-slate-300 transition cursor-pointer"
        :class="{ 'bg-slate-50': isChecked, 'opacity-60 cursor-not-allowed': disabled }"
    >
        <input
            :type="voteMode === 'multiple' ? 'checkbox' : 'radio'"
            class="mt-1 text-blue-600"
            :checked="isChecked"
            :disabled="disabled"
            @change="toggle"
        />
        <div>
            <p class="text-sm font-medium text-slate-900">{{ option.text }}</p>
            <p v-if="option.createdBy" class="text-xs text-slate-500 mt-1">
                Added by {{ option.createdBy.name || option.createdBy.email }}
            </p>
        </div>
    </label>
</template>
