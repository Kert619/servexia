<template>
    <div class="flex items-center gap-3">
        <Checkbox
            :id="inputId"
            v-model="model"
            :class="checkboxClasses"
            :disabled="disabled"
        />
        <Label v-if="label || $slots.default" :for="inputId">
            <slot>{{ label }}</slot>
        </Label>
    </div>
</template>

<script setup lang="ts">
import { computed, HTMLAttributes, useId } from "vue";
import { Checkbox } from "@/components/ui/checkbox";
import { Label } from "@/components/ui/label";
import { cn } from "@/lib/utils";

const props = defineProps<{
    label?: string;
    id?: string;
    color?: "primary" | "secondary" | "success" | "destructive" | "warning";
    class?: HTMLAttributes["class"];
    disabled?: boolean;
}>();

const model = defineModel<boolean | "indeterminate">({ default: false });

const colorClasses: Record<NonNullable<typeof props.color>, string> = {
    primary:
        "data-[state=checked]:bg-primary data-[state=checked]:border-primary",
    secondary:
        "data-[state=checked]:bg-secondary data-[state=checked]:border-secondary data-[state=checked]:text-secondary-foreground",
    success:
        "data-[state=checked]:bg-success data-[state=checked]:border-success",
    destructive:
        "data-[state=checked]:bg-destructive data-[state=checked]:border-destructive",
    warning:
        "data-[state=checked]:bg-warning data-[state=checked]:border-warning",
};

// Auto-generate a stable id for the label/input pairing unless one's explicitly passed
const inputId = computed(() => props.id ?? useId());

const checkboxClasses = computed(() => {
    return cn(
        props.color
            ? colorClasses[props.color]
            : "data-[state=checked]:bg-primary",
        props.class,
    );
});
</script>
