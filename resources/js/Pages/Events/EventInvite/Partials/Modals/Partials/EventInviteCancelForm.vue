<script setup>
import { ref, defineExpose, defineEmits, onMounted} from 'vue';
import {router, usePage} from '@inertiajs/vue3';

const props = defineProps({
    event: {
        type: Object,
        required: true,
    },
});

const emit = defineEmits(['registerSuccess']);

const userIsLoggedIn = ref(false);

onMounted(() => {
    userIsLoggedIn.value = usePage().props.auth.user !== null;
});

const submitCancelForm = () => {
    return router.delete(
        route('events.cancel-invite', props.event.uniqueIdentifier),
        {
            onSuccess: () => {
                emit('registerSuccess');
            },
        },
    );
};

defineExpose({submitCancelForm});
</script>

<template>
    <div class="w-full bg-white">
        <form
            @submit.prevent="submitCancelForm"
        >
            <div>
                <p class="text-center text-lg text-gray-600" v-if="userIsLoggedIn">
                    Are u sure you want to cancel your invite to this event?
                </p>
            </div>
        </form>
    </div>
</template>
