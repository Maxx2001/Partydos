<script setup>
/**
 * @link https://fonts.google.com/icons?icon.set=Material+Symbols
 */

import {computed} from "vue";
import {cva} from 'class-variance-authority';

const props = defineProps({
    name: {
        type: String,
        required: true,
    },
    intent: {
        type: String,
        validator: value => ['filled', 'outlined'].includes(value),
        default: 'outlined'
    },
});

const iconClass = computed(() => {
    return cva(
        "text-current",
        {
            variants: {
                intent:{
                    filled: 'icon-filled material-symbols-sharp',
                    outlined: 'material-symbols-sharp'
                },
            }
        })({
        intent: props.intent,
    });
});
</script>

<template>
    <span :class="iconClass">
        {{name}}
    </span>
</template>
