<script setup>
import DefaultLayout from "@/Layouts/DefaultLayout.vue";

defineProps({
    event: {
        type: Object,
        required: true
    }
})
</script>

<template>
  <DefaultLayout>
      <div class="py-24 px-6 flex flex-col items-center justify-center bg-slate-100 rounded">
          <div class="w-full flex justify-center text-2xl font-semibold">
              <h1>
                  {{ event.title}}
              </h1>
          </div>

          <div class="flex flex-col items-center w-full ">
              {{ event.description }}
          </div>
          <div class="flex flex-col items-center w-full ">
              {{ event.location }}
          </div>
      </div>
  </DefaultLayout>
</template>
