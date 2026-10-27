<template>
    <Button
        :variant="variant"
        :size="size"
        :disabled="disabled || loading"
        :type="type"
        :class="buttonClasses"
    >
        <slot name="before">
            <component
                v-if="icon && iconPosition === 'left' && !loading"
                :is="icon"
            />
        </slot>

        <Spinner v-if="loading" />
        {{ label }}

        <slot name="after">
            <component
                v-if="icon && iconPosition === 'right' && !loading"
                :is="icon"
            />
        </slot>
    </Button>
</template>

<script setup lang="ts">
import { Button, ButtonVariants } from "@/components/ui/button";
import { LucideIcon } from "@lucide/vue";
import { Spinner } from "@/components/ui/spinner";
import { computed, HTMLAttributes } from "vue";
import { cn } from "@/lib/utils";

const props = withDefaults(
    defineProps<{
        type?: "button" | "submit";
        label?: string;
        icon?: LucideIcon;
        iconPosition?: "left" | "right";
        size?: ButtonVariants["size"];
        variant?: ButtonVariants["variant"];
        disabled?: boolean;
        loading?: boolean;
        class?: HTMLAttributes["class"];
    }>(),
    {
        size: "sm",
        variant: "default",
        iconPosition: "left",
        type: "button",
    },
);

const buttonClasses = computed(() => {
    return cn("cursor-pointer", props.class);
});
</script>
