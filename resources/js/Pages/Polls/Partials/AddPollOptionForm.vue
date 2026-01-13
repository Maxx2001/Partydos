<script setup>
import { useForm } from "@inertiajs/vue3";

const props = defineProps({
    eventId: {
        type: [Number, String],
        required: true,
    },
    pollId: {
        type: [Number, String],
        required: true,
    },
});

const form = useForm({
    text: "",
});

const submit = () => {
    form.post(route("events.polls.options.store", { event: props.eventId, poll: props.pollId }), {
        preserveScroll: true,
        onSuccess: () => form.reset("text"),
    });
};
</script>

<template>
    <form @submit.prevent="submit" class="flex flex-col gap-3 sm:flex-row sm:items-end">
        <div class="flex-1">
            <label class="block text-sm font-semibold text-slate-700">Add an option</label>
            <input
                v-model="form.text"
                type="text"
                class="mt-2 w-full rounded-md border-slate-300 focus:border-blue-500 focus:ring-blue-500"
                maxlength="140"
                required
            />
            <p v-if="form.errors.text" class="text-sm text-red-600 mt-1">{{ form.errors.text }}</p>
        </div>
        <button
            type="submit"
            class="inline-flex items-center justify-center px-4 py-2 rounded-md bg-slate-900 text-white font-semibold hover:bg-slate-800 transition"
            :disabled="form.processing"
        >
            Add option
        </button>
    </form>
</template>
