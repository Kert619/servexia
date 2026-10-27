<template>
    <div
        :class="
            cn(
                'flex',
                labelPosition === 'before'
                    ? 'flex-row items-center gap-3'
                    : 'flex-col gap-1.5',
            )
        "
    >
        <!-- Label rendered inline, to the left of the input -->
        <label
            v-if="labelPosition === 'before' && label"
            :for="inputId"
            :class="
                cn('text-sm font-medium text-foreground shrink-0', labelWidth)
            "
        >
            {{ label }}
        </label>

        <div class="flex flex-col gap-1.5 flex-1">
            <!-- TOP SLOTS -->
            <div
                v-if="
                    $slots['top-right'] ||
                    $slots['top-left'] ||
                    (labelPosition === 'top' && label)
                "
                class="flex justify-between"
            >
                <slot name="top-left">
                    <label
                        v-if="labelPosition === 'top'"
                        :for="inputId"
                        class="text-sm font-medium text-foreground"
                    >
                        <slot name="label">{{ label }}</slot>
                    </label>
                </slot>
                <slot name="top-right"> </slot>
            </div>
            <!-- TOP SLOTS -->

            <!-- INPUT COMPONENT -->
            <div class="relative flex items-center flex-1">
                <div
                    v-if="$slots.leading"
                    class="pointer-events-none absolute left-3 flex items-center text-ring [&>svg]:size-4"
                >
                    <slot name="leading" />
                </div>
                <Input
                    :id="inputId"
                    :model-value="localValue"
                    @update:model-value="handleInputChange"
                    :type="type"
                    :placeholder="placeholder"
                    :spellcheck="false"
                    :autocomplete="autocomplete"
                    :class="
                        cn(
                            $slots.leading && 'pl-8',
                            $slots.trailing && 'pr-8',
                            props.class,
                        )
                    "
                />

                <div
                    v-if="$slots.trailing"
                    class="absolute right-3 flex items-center [&>svg]:size-4"
                >
                    <slot name="trailing" />
                </div>
            </div>
            <!-- INPUT COMPONENT -->

            <!-- BOTTOM SLOTS -->
            <div
                v-if="$slots['bottom-right'] || $slots['bottom-left']"
                class="flex justify-between"
            >
                <slot name="bottom-left"></slot>
                <slot name="bottom-right"></slot>
            </div>
            <!-- BOTTOM SLOTS -->
        </div>
    </div>
</template>

<script setup lang="ts">
import type { HTMLAttributes } from "vue";
import { computed, onBeforeUnmount, ref, useId, watch } from "vue";
import { Input } from "@/components/ui/input";
import { cn } from "@/lib/utils";

const props = withDefaults(
    defineProps<{
        type?: string;
        placeholder?: string;
        class?: HTMLAttributes["class"];
        label?: string;
        labelPosition?: "top" | "before";
        labelWidth?: string;
        id?: string;
        debounce?: number;
        autocomplete?: "on" | "off";
    }>(),
    {
        labelPosition: "top",
        labelWidth: "w-24",
        debounce: 0,
        autocomplete: "off",
    },
);

const model = defineModel<string | number>({ default: "" });

// Auto-generate a stable id for the label/input pairing unless one's explicitly passed
const inputId = computed(() => props.id ?? useId());

// --- debounce plumbing --------------------------------------------------
// The Input itself always binds to `localValue` so typing feels instant.
// When `debounce` is set, propagation to the parent's v-model is delayed;
// otherwise it mirrors `model` synchronously (original behavior).
const localValue = ref(model.value);
let debounceTimer: ReturnType<typeof setTimeout> | undefined;

const handleInputChange = (value: string | number) => {
    localValue.value = value;

    if (!props.debounce) {
        model.value = value;
        return;
    }

    clearTimeout(debounceTimer);
    debounceTimer = setTimeout(() => {
        model.value = value;
    }, props.debounce);
};

// Reflect external/programmatic changes to the model into the local value.
watch(model, (newValue) => {
    if (newValue !== localValue.value) {
        localValue.value = newValue;
    }
});

onBeforeUnmount(() => clearTimeout(debounceTimer));
</script>
