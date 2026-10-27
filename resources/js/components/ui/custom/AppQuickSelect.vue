<template>
    <div class="flex items-center gap-2">
        <label v-if="label" class="hidden sm:block text-sm whitespace-nowrap">
            {{ label }}
        </label>
        <Select v-model="model" :disabled="loading">
            <SelectTrigger>
                <SelectValue />
            </SelectTrigger>

            <SelectContent>
                <SelectItem
                    v-for="option in options"
                    :key="option.value"
                    :value="option.value"
                    >{{ option.label }}</SelectItem
                >
            </SelectContent>
        </Select>
    </div>
</template>

<script setup lang="ts" generic="TValue extends string | number">
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from "@/components/ui/select";
import { Option } from "@/types/option";

const props = defineProps<{
    label?: string;
    options: Option<TValue>[];
    loading?: boolean;
}>();

const model = defineModel<TValue | null>();
</script>
