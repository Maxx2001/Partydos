<script setup>
import ShoppingListItemRow from "@/Pages/Events/Partials/Invite/ShoppingList/ShoppingListItemRow.vue";

const props = defineProps({
    items: {
        type: Array,
        default: () => [],
    },
    isHost: {
        type: Boolean,
        default: false,
    },
    currentUserId: {
        type: Number,
        default: null,
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
</script>

<template>
    <div class="space-y-3">
        <ShoppingListItemRow
            v-for="item in items"
            :key="item.id"
            :item="item"
            :is-host="isHost"
            :current-user-id="currentUserId"
            :show-promote="isHost"
            :show-added-by="true"
            :show-updated-info="false"
            @toggle-done="emit('toggle-done', $event)"
            @assign-self="emit('assign-self', $event)"
            @unassign="emit('unassign', $event)"
            @delete-item="emit('delete-item', $event)"
            @update-item="emit('update-item', $event)"
            @promote-item="emit('promote-item', $event)"
        />
        <p v-if="items.length === 0" class="text-sm text-slate-500">
            No guest suggestions yet.
        </p>
    </div>
</template>
