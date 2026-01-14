<script setup>
import { computed, ref, watch } from "vue";
import { router, usePage } from "@inertiajs/vue3";
import axios from "axios";
import BaseButton from "@/Components/Base/BaseButton.vue";
import ShoppingListItemRow from "@/Pages/Events/Partials/Invite/ShoppingList/ShoppingListItemRow.vue";
import GuestSuggestionsList from "@/Pages/Events/Partials/Invite/ShoppingList/GuestSuggestionsList.vue";

const props = defineProps({
    event: {
        type: Object,
        required: true,
    },
    shoppingList: {
        type: Object,
        default: null,
    },
    shoppingListItemsMain: {
        type: Array,
        default: () => [],
    },
    shoppingListItemsGuest: {
        type: Array,
        default: () => [],
    },
});

const page = usePage();
const currentUserId = computed(() => page.props.auth?.user?.id ?? null);
const isHost = computed(() => props.event.canEdit);

const list = ref(props.shoppingList);
const itemsMain = ref([...props.shoppingListItemsMain]);
const itemsGuest = ref([...props.shoppingListItemsGuest]);

watch(
    () => [props.shoppingList, props.shoppingListItemsMain, props.shoppingListItemsGuest],
    ([nextList, nextMain, nextGuest]) => {
        list.value = nextList;
        itemsMain.value = [...nextMain];
        itemsGuest.value = [...nextGuest];
    }
);

const form = ref({
    name: "",
    quantity: "",
    unit: "",
    note: "",
});

const canAddItems = computed(() => {
    if (!list.value) {
        return false;
    }

    if (isHost.value) {
        return true;
    }

    return list.value.guestCanAdd === true;
});

const canShowGuestSuggestions = computed(() => {
    if (!list.value) {
        return itemsGuest.value.length > 0;
    }

    return list.value.guestCanAdd || itemsGuest.value.length > 0;
});

const refreshList = async () => {
    const response = await axios.get(route('events.shopping-list.show', { event: props.event.id }));
    list.value = response.data.list;
    itemsMain.value = response.data.items_main;
    itemsGuest.value = response.data.items_guest;
};

const createList = () => {
    router.post(route('events.shopping-list.store', { event: props.event.id }), {}, {
        onSuccess: refreshList,
    });
};

const toggleGuestCanAdd = () => {
    if (!list.value) {
        return;
    }

    router.patch(
        route('events.shopping-list.update', { event: props.event.id }),
        { guest_can_add: !list.value.guestCanAdd },
        { onSuccess: refreshList }
    );
};

const addItem = () => {
    if (!form.value.name) {
        return;
    }

    router.post(
        route('shopping-list-items.store', { event: props.event.id }),
        {
            name: form.value.name,
            quantity: form.value.quantity || null,
            unit: form.value.unit || null,
            note: form.value.note || null,
        },
        {
            onSuccess: () => {
                form.value = { name: "", quantity: "", unit: "", note: "" };
                refreshList();
            },
        }
    );
};

const updateItem = (payload) => {
    const { id, ...data } = payload;

    router.patch(route('shopping-list-items.update', { item: id }), data, {
        onSuccess: refreshList,
    });
};

const toggleDone = (id) => {
    router.post(route('shopping-list-items.toggle-done', { item: id }), {}, {
        onSuccess: refreshList,
    });
};

const assignSelf = (id) => {
    router.post(route('shopping-list-items.assign-self', { item: id }), {}, {
        onSuccess: refreshList,
    });
};

const unassign = (id) => {
    router.post(route('shopping-list-items.unassign', { item: id }), {}, {
        onSuccess: refreshList,
    });
};

const promoteItem = (id) => {
    router.post(route('shopping-list-items.promote', { item: id }), {}, {
        onSuccess: refreshList,
    });
};

const deleteItem = (id) => {
    router.delete(route('shopping-list-items.destroy', { item: id }), {
        onSuccess: refreshList,
    });
};
</script>

<template>
    <section class="w-full bg-slate-100 py-10">
        <div class="mx-auto flex w-full max-w-6xl flex-col gap-6 px-6">
            <div class="flex flex-wrap items-center justify-between gap-4">
                <div>
                    <h2 class="text-2xl font-semibold text-indigo-700">Shopping List</h2>
                    <p class="text-sm text-slate-600">
                        Keep track of what to bring and let guests share suggestions.
                    </p>
                </div>
                <BaseButton
                    v-if="!list && isHost"
                    label="Create shopping list"
                    @click="createList"
                    class="text-sm"
                />
            </div>

            <div v-if="list" class="flex flex-col gap-4">
                <div class="flex flex-wrap items-center justify-between gap-3 rounded-lg border border-slate-200 bg-white px-4 py-3">
                    <div>
                        <p class="text-sm font-semibold text-slate-700">Allow guests to add items</p>
                        <p class="text-xs text-slate-500">Guest additions appear as suggestions until you promote them.</p>
                    </div>
                    <label v-if="isHost" class="flex items-center gap-2 text-sm text-slate-600">
                        <input
                            type="checkbox"
                            class="h-4 w-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500"
                            :checked="list.guestCanAdd"
                            @change="toggleGuestCanAdd"
                        />
                        Guest can add
                    </label>
                </div>

                <div class="grid gap-6 lg:grid-cols-2">
                    <div class="space-y-4">
                        <div class="rounded-lg border border-slate-200 bg-white p-4 shadow-sm">
                            <h3 class="text-lg font-semibold text-slate-800">Main list</h3>
                            <p class="text-xs text-slate-500">Items confirmed for the event.</p>

                            <div v-if="canAddItems" class="mt-4 space-y-3">
                                <div class="grid gap-2 md:grid-cols-3">
                                    <input
                                        v-model="form.name"
                                        type="text"
                                        placeholder="Item name"
                                        class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm"
                                    />
                                    <input
                                        v-model="form.quantity"
                                        type="number"
                                        min="0"
                                        step="0.01"
                                        placeholder="Quantity"
                                        class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm"
                                    />
                                    <input
                                        v-model="form.unit"
                                        type="text"
                                        placeholder="Unit"
                                        class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm"
                                    />
                                </div>
                                <textarea
                                    v-model="form.note"
                                    rows="2"
                                    placeholder="Add a note (optional)"
                                    class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm"
                                />
                                <div class="flex justify-end">
                                    <BaseButton label="Add item" @click="addItem" class="text-sm" />
                                </div>
                            </div>

                            <p v-else class="mt-4 text-sm text-slate-500">
                                Ask the host to enable guest suggestions to add new items.
                            </p>
                        </div>

                        <div class="space-y-3">
                            <ShoppingListItemRow
                                v-for="item in itemsMain"
                                :key="item.id"
                                :item="item"
                                :is-host="isHost"
                                :current-user-id="currentUserId"
                                @toggle-done="toggleDone"
                                @assign-self="assignSelf"
                                @unassign="unassign"
                                @delete-item="deleteItem"
                                @update-item="updateItem"
                            />
                            <p v-if="itemsMain.length === 0" class="text-sm text-slate-500">
                                No items in the main list yet.
                            </p>
                        </div>
                    </div>

                    <div v-if="canShowGuestSuggestions" class="space-y-4">
                        <div class="rounded-lg border border-slate-200 bg-white p-4 shadow-sm">
                            <h3 class="text-lg font-semibold text-slate-800">Guest suggestions</h3>
                            <p class="text-xs text-slate-500">
                                Suggestions from guests. Promote items to the main list when ready.
                            </p>
                        </div>

                        <GuestSuggestionsList
                            :items="itemsGuest"
                            :is-host="isHost"
                            :current-user-id="currentUserId"
                            @toggle-done="toggleDone"
                            @assign-self="assignSelf"
                            @unassign="unassign"
                            @delete-item="deleteItem"
                            @update-item="updateItem"
                            @promote-item="promoteItem"
                        />
                    </div>
                </div>
            </div>

            <div v-else class="rounded-lg border border-dashed border-slate-300 bg-white p-6 text-center text-sm text-slate-500">
                <p v-if="isHost">Create a shopping list to start planning what to bring.</p>
                <p v-else>The host has not created a shopping list yet.</p>
            </div>
        </div>
    </section>
</template>
