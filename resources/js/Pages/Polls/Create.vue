<script setup>
import DefaultLayout from "@/Layouts/DefaultLayout.vue";
import { Link, useForm } from "@inertiajs/vue3";
import { useTitle } from "@/Composables/useTitle.js";

const props = defineProps({
    event: {
        type: Object,
        required: true,
    },
});

useTitle(`Create Poll | ${props.event.title}`);

const form = useForm({
    question: "",
    description: "",
    vote_mode: "single",
    guests_can_add_options: false,
    options: ["", ""],
});

const addOption = () => {
    if (form.options.length >= 20) {
        return;
    }
    form.options.push("");
};

const removeOption = (index) => {
    if (form.options.length <= 2) {
        return;
    }
    form.options.splice(index, 1);
};

const submit = () => {
    form.post(route("events.polls.store", props.event.id));
};
</script>

<template>
    <DefaultLayout>
        <div class="bg-slate-100 min-h-screen py-10 px-6">
            <div class="max-w-3xl mx-auto">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <p class="text-sm uppercase tracking-wide text-slate-500">Create poll</p>
                        <h1 class="text-3xl font-bold text-slate-900">{{ event.title }}</h1>
                    </div>
                    <Link
                        :href="route('events.polls.index', event.id)"
                        class="inline-flex items-center justify-center px-4 py-2 rounded-md border border-slate-300 text-slate-700 font-semibold hover:bg-white transition"
                    >
                        Back to polls
                    </Link>
                </div>

                <form @submit.prevent="submit" class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 space-y-6">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700">Question</label>
                        <input
                            v-model="form.question"
                            type="text"
                            class="mt-2 w-full rounded-md border-slate-300 focus:border-blue-500 focus:ring-blue-500"
                            maxlength="160"
                            required
                        />
                        <p v-if="form.errors.question" class="text-sm text-red-600 mt-1">{{ form.errors.question }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700">Description (optional)</label>
                        <textarea
                            v-model="form.description"
                            class="mt-2 w-full rounded-md border-slate-300 focus:border-blue-500 focus:ring-blue-500"
                            rows="3"
                            maxlength="240"
                        ></textarea>
                        <p v-if="form.errors.description" class="text-sm text-red-600 mt-1">{{ form.errors.description }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-700">Vote mode</label>
                        <div class="mt-3 space-y-2">
                            <label class="flex items-center gap-2 text-sm text-slate-600">
                                <input v-model="form.vote_mode" type="radio" value="single" class="text-blue-600" />
                                Single choice (one vote)
                            </label>
                            <label class="flex items-center gap-2 text-sm text-slate-600">
                                <input v-model="form.vote_mode" type="radio" value="multiple" class="text-blue-600" />
                                Multiple choice (select several)
                            </label>
                        </div>
                        <p v-if="form.errors.vote_mode" class="text-sm text-red-600 mt-1">{{ form.errors.vote_mode }}</p>
                    </div>

                    <div class="flex items-center gap-3">
                        <input
                            id="guests_can_add_options"
                            v-model="form.guests_can_add_options"
                            type="checkbox"
                            class="rounded border-slate-300 text-blue-600 focus:ring-blue-500"
                        />
                        <label for="guests_can_add_options" class="text-sm text-slate-600">
                            Allow guests to add options
                        </label>
                    </div>

                    <div>
                        <div class="flex items-center justify-between">
                            <label class="block text-sm font-semibold text-slate-700">Options</label>
                            <button
                                type="button"
                                @click="addOption"
                                class="text-sm font-semibold text-blue-700 hover:text-blue-800"
                            >
                                + Add option
                            </button>
                        </div>
                        <div class="mt-3 space-y-3">
                            <div
                                v-for="(option, index) in form.options"
                                :key="index"
                                class="flex items-center gap-3"
                            >
                                <input
                                    v-model="form.options[index]"
                                    type="text"
                                    class="w-full rounded-md border-slate-300 focus:border-blue-500 focus:ring-blue-500"
                                    maxlength="140"
                                    required
                                />
                                <button
                                    type="button"
                                    @click="removeOption(index)"
                                    class="text-sm font-semibold text-slate-500 hover:text-slate-700"
                                    :disabled="form.options.length <= 2"
                                >
                                    Remove
                                </button>
                            </div>
                        </div>
                        <p v-if="form.errors.options" class="text-sm text-red-600 mt-2">{{ form.errors.options }}</p>
                    </div>

                    <div class="flex justify-end gap-3">
                        <Link
                            :href="route('events.polls.index', event.id)"
                            class="inline-flex items-center justify-center px-4 py-2 rounded-md border border-slate-300 text-slate-700 font-semibold hover:bg-white transition"
                        >
                            Cancel
                        </Link>
                        <button
                            type="submit"
                            class="inline-flex items-center justify-center px-5 py-2 rounded-md bg-blue-700 text-white font-semibold hover:bg-blue-800 transition"
                            :disabled="form.processing"
                        >
                            Create poll
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </DefaultLayout>
</template>
