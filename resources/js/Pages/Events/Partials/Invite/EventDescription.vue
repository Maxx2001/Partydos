<script setup>
defineProps({
    event: {
        type: Object,
        required: true,
    }
})
</script>

<template>
    <div class="flex items-center flex-col justify-center bg-slate-100 px-4 md:px-8">
        <div class="w-full max-w-6xl p-2 md:p-8">
<!--            <p class="text-2xl md:text-3xl text-indigo-700 font-semibold text-center mb-4">-->
<!--                Event Description-->
<!--            </p>-->
            <div class="border rounded-lg bg-blue-100 p-3 lg:p-8">
                <p class="text-gray-700 text-base md:text-lg leading-relaxed br">
                    <span v-if="event.description">
                        {{ event.description }}
                    </span>
                    <span v-else class="italic underline text-gray-500 text-sm lg:text-base">
                        No description set
                    </span>
                </p>
            </div>

        </div>
    </div>
</template>
