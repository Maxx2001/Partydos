<script setup>
import DefaultLayout from "@/Layouts/DefaultLayout.vue";
import { computed } from "vue";
import { router, useForm, usePage } from "@inertiajs/vue3";

const props = defineProps({
    googleAccount: {
        type: Object,
        default: null,
    },
    calendars: {
        type: Array,
        default: () => [],
    },
});

const flash = computed(() => usePage().props.flash || {});

const form = useForm({
    calendar_id: props.googleAccount?.calendarId || "primary",
});

const connect = () => {
    router.get(route("integrations.google.connect"));
};

const disconnect = () => {
    router.post(route("integrations.google.disconnect"));
};

const updateCalendar = () => {
    form.post(route("integrations.google.calendar"));
};
</script>

<template>
    <DefaultLayout>
        <div class="bg-slate-100 py-8 md:py-16">
            <div class="mx-auto w-full max-w-4xl px-6">
                <div class="rounded-2xl bg-white p-6 shadow-sm">
                    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                        <div>
                            <h1 class="text-2xl font-semibold text-slate-900">Google Calendar</h1>
                            <p class="mt-1 text-sm text-slate-500">
                                We only write events to your calendar (one-way sync).
                            </p>
                        </div>
                        <div>
                            <span
                                class="rounded-full px-3 py-1 text-xs font-semibold"
                                :class="googleAccount ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-200 text-slate-600'"
                            >
                                {{ googleAccount ? "Connected" : "Not connected" }}
                            </span>
                        </div>
                    </div>

                    <div v-if="flash.success" class="mt-4 rounded-lg bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
                        {{ flash.success }}
                    </div>
                    <div v-if="flash.error" class="mt-4 rounded-lg bg-rose-50 px-4 py-3 text-sm text-rose-700">
                        {{ flash.error }}
                    </div>

                    <div class="mt-6">
                        <button
                            v-if="!googleAccount"
                            type="button"
                            class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white shadow hover:bg-blue-700"
                            @click="connect"
                        >
                            Connect Google Account
                        </button>

                        <div v-else class="flex flex-col gap-4">
                            <div>
                                <p class="text-sm font-medium text-slate-600">Connected as</p>
                                <p class="text-base font-semibold text-slate-900">{{ googleAccount.email }}</p>
                            </div>

                            <div>
                                <label class="text-sm font-medium text-slate-600">Default calendar</label>
                                <div class="mt-2 flex flex-col gap-3 md:flex-row md:items-center">
                                    <select
                                        v-model="form.calendar_id"
                                        class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm text-slate-700 shadow-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 md:w-72"
                                    >
                                        <option value="primary">Primary</option>
                                        <option
                                            v-for="calendar in calendars"
                                            :key="calendar.id"
                                            :value="calendar.id"
                                        >
                                            {{ calendar.summary }}
                                        </option>
                                    </select>
                                    <button
                                        type="button"
                                        class="rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white shadow hover:bg-slate-800"
                                        @click="updateCalendar"
                                    >
                                        Save
                                    </button>
                                </div>
                            </div>

                            <button
                                type="button"
                                class="w-fit rounded-lg border border-rose-300 px-4 py-2 text-sm font-semibold text-rose-600 hover:bg-rose-50"
                                @click="disconnect"
                            >
                                Disconnect
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </DefaultLayout>
</template>
