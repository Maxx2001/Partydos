<script setup>
import DefaultLayout from "@/Layouts/DefaultLayout.vue";

defineProps({
    event: {
        type: Object,
        required: true
    }
});
</script>

<template>
    <DefaultLayout>
        <div class="py-16 md:py-24 flex flex-col items-center justify-center bg-slate-100 rounded">
            <div class="w-full lg:w-1/2 border bg-white">
                <div class="flex flex-col justify-center w-full items-center bg-white py-16">
                    <span class="text-xl pb-2">
                        You are invited to:
                    </span>
                    <span class="text-2xl my-2">
                        {{ event.title }}
                    </span>
                    <span class="w-10/12">
                        Event description:
                    </span>
                    <div
                        class="bg-slate-100 p-2 w-10/12 rounded pt-1 mb-4"
                        :class="event.description ?? 'italic text-gray-800' "
                    >
                        {{ event.description ?? 'No event description available.'}}
                    </div>
                    <span class="w-10/12">
                        Event location:
                    </span>
                    <div
                        class="bg-slate-100 p-2 w-10/12 rounded"
                        :class="event.location ?? 'italic text-gray-800' "
                    >
                        {{ event.location ?? 'No event location available.'}}
                    </div>
                </div>
            </div>
        </div>
    </DefaultLayout>
</template>
