<script setup>
defineProps({
    event: {
        type: Object,
        required: true
    }
})


</script>

<template>
    <div class="w-full lg:w-1/2 border bg-white mx-4">
        <div class="flex flex-col justify-center w-full items-center bg-white py-16">
                <span class="text-xl pb-2">
                    You are invited to:
                </span>
            <span class="text-2xl my-2 w-10/12 flex justify-center items-center">
                    {{ event.title }}
                </span>
            <span class="w-10/12">
                    Event description:
                </span>
            <div
                class="bg-slate-100 p-2 w-10/12 rounded pt-1 mb-4"
                :class="event.description ?? 'italic text-gray-800' "
            >
                {{ event.description ?? 'No event description set.'}}
            </div>
            <span class="w-10/12">
                    Event location:
                </span>
            <div
                class="bg-slate-100 p-2 w-10/12 rounded mb-4"
                :class="event.location ?? 'italic text-gray-800' "
            >
                {{ event.location ?? 'No event location set.'}}
            </div>
            <span class="w-10/12">
                    Date and time:
                </span>
            <div
                class="bg-slate-100 p-2 w-10/12 rounded mb-4"
                :class="event.startDateTime ?? 'italic text-gray-800' "
            >
                {{ event.startDateTime ?? 'No start date set.'}}
            </div>
            <div
                class="bg-slate-100 p-2 w-10/12 rounded mb-4"
                :class="event.endDateTime ?? 'italic text-gray-800' "
            >
                {{ event.endDateTime ?? 'No end date set.'}}
            </div>
        </div>
    </div>
</template>
