<script setup>
import { computed, ref, watch } from "vue";
import BaseButton from "@/Components/Base/BaseButton.vue";
import BaseOutlineButton from "@/Components/Base/BaseOutlineButton.vue";

const props = defineProps({
    item: {
        type: Object,
        required: true,
    },
    isHost: {
        type: Boolean,
        default: false,
    },
    currentUserId: {
        type: Number,
        default: null,
    },
    showPromote: {
        type: Boolean,
        default: false,
    },
    showAddedBy: {
        type: Boolean,
        default: false,
    },
    showUpdatedInfo: {
        type: Boolean,
        default: true,
    },
});

const emit = defineEmits([
    'toggle-done',
    'assign-self',
    'unassign',
    'delete-item',
    'update-item',
    'promote-item',
]);

const isAssignedToMe = computed(() => props.item.assignedTo?.id === props.currentUserId);
const canEditNote = computed(() => {
    return props.isHost
        || props.item.createdBy?.id === props.currentUserId
        || props.item.assignedTo?.id === props.currentUserId;
});

const displayQuantity = computed(() => {
    if (!props.item.quantity && !props.item.unit) {
        return null;
    }

    if (props.item.quantity && props.item.unit) {
        return `${props.item.quantity} ${props.item.unit}`;
    }

    return props.item.quantity || props.item.unit;
});

const editing = ref(false);
const draft = ref({
    name: props.item.name,
    quantity: props.item.quantity,
    unit: props.item.unit,
    note: props.item.note ?? '',
});

watch(
    () => props.item,
    (item) => {
        draft.value = {
            name: item.name,
            quantity: item.quantity,
            unit: item.unit,
            note: item.note ?? '',
        };
    },
    { deep: true }
);

const toggleEdit = () => {
    editing.value = !editing.value;
};

const saveItem = () => {
    emit('update-item', {
        id: props.item.id,
        name: draft.value.name,
        quantity: draft.value.quantity,
        unit: draft.value.unit,
        note: draft.value.note,
    });
    editing.value = false;
};

const saveNote = () => {
    emit('update-item', {
        id: props.item.id,
        note: draft.value.note,
    });
};

const formattedUpdatedAt = computed(() => {
    if (!props.item.updatedAt) {
        return null;
    }

    return new Date(props.item.updatedAt).toLocaleString();
});
</script>

<template>
    <div class="flex flex-col gap-2 rounded-lg border border-slate-200 bg-white p-4 shadow-sm">
        <div class="flex items-start gap-3">
            <input
                type="checkbox"
                class="mt-1 h-4 w-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500"
                :checked="item.status === 'done'"
                @change="emit('toggle-done', item.id)"
            />
            <div class="flex-1">
                <div class="flex flex-wrap items-center justify-between gap-3">
                    <div class="text-base font-semibold text-slate-800" :class="{ 'line-through text-slate-400': item.status === 'done' }">
                        {{ item.name }}
                        <span v-if="displayQuantity" class="ml-2 text-sm text-slate-500">
                            ({{ displayQuantity }})
                        </span>
                    </div>
                    <div class="flex flex-wrap items-center gap-2">
                        <BaseOutlineButton
                            v-if="!item.assignedTo || !isAssignedToMe"
                            label="I'll take this"
                            @click="emit('assign-self', item.id)"
                            class="text-xs"
                        />
                        <BaseOutlineButton
                            v-if="item.assignedTo && (isAssignedToMe || isHost)"
                            label="Unassign"
                            @click="emit('unassign', item.id)"
                            class="text-xs"
                        />
                        <BaseOutlineButton
                            v-if="showPromote"
                            label="Move to main list"
                            @click="emit('promote-item', item.id)"
                            class="text-xs"
                        />
                        <BaseOutlineButton
                            v-if="isHost"
                            label="Delete"
                            variant="cancel"
                            @click="emit('delete-item', item.id)"
                            class="text-xs"
                        />
                    </div>
                </div>

                <div class="mt-2 text-sm text-slate-600">
                    <span class="font-semibold">Taking it:</span>
                    <span v-if="item.assignedTo">{{ item.assignedTo.name }}</span>
                    <span v-else>Unassigned</span>
                </div>

                <div v-if="showAddedBy && item.createdBy" class="mt-1 text-xs text-slate-500">
                    Suggested by {{ item.createdBy.name }}
                </div>

                <div class="mt-3">
                    <div v-if="editing" class="space-y-2">
                        <div class="grid gap-2 md:grid-cols-3">
                            <input
                                v-model="draft.name"
                                type="text"
                                class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm"
                                placeholder="Item name"
                            />
                            <input
                                v-model="draft.quantity"
                                type="number"
                                step="0.01"
                                min="0"
                                class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm"
                                placeholder="Quantity"
                            />
                            <input
                                v-model="draft.unit"
                                type="text"
                                class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm"
                                placeholder="Unit"
                            />
                        </div>
                        <textarea
                            v-model="draft.note"
                            rows="2"
                            class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm"
                            placeholder="Add a note"
                        />
                        <div class="flex gap-2">
                            <BaseButton label="Save" @click="saveItem" class="text-xs" />
                            <BaseOutlineButton label="Cancel" @click="toggleEdit" class="text-xs" />
                        </div>
                    </div>
                    <div v-else>
                        <div v-if="canEditNote" class="space-y-2">
                            <textarea
                                v-model="draft.note"
                                rows="2"
                                class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm"
                                placeholder="Add a note"
                            />
                            <div class="flex gap-2">
                                <BaseButton label="Save note" @click="saveNote" class="text-xs" />
                                <BaseOutlineButton
                                    v-if="isHost"
                                    label="Edit item"
                                    @click="toggleEdit"
                                    class="text-xs"
                                />
                            </div>
                        </div>
                        <p v-else class="text-sm text-slate-500">
                            {{ item.note || 'No note yet.' }}
                        </p>
                    </div>
                </div>

                <div v-if="showUpdatedInfo" class="mt-3 text-xs text-slate-500">
                    <span v-if="item.lastUpdatedBy">Updated by {{ item.lastUpdatedBy.name }}</span>
                    <span v-if="formattedUpdatedAt"> · {{ formattedUpdatedAt }}</span>
                </div>
            </div>
        </div>
    </div>
</template>
