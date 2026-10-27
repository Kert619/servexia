<script setup lang="ts">
import { MoreHorizontal } from "@lucide/vue";

import { Button } from "@/components/ui/button";

import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuTrigger,
    DropdownMenuLabel,
    DropdownMenuItem,
} from "@/components/ui/dropdown-menu";
import { Trash, SquarePen } from "@lucide/vue";

const props = defineProps<{
    onEdit?: () => void;
    onDelete?: () => void;
}>();
</script>

<template>
    <DropdownMenu>
        <DropdownMenuTrigger as-child>
            <Button variant="ghost" class="w-8 h-8 p-0">
                <span class="sr-only">Open menu</span>
                <MoreHorizontal class="w-4 h-4" />
            </Button>
        </DropdownMenuTrigger>

        <DropdownMenuContent align="end">
            <DropdownMenuLabel>Actions</DropdownMenuLabel>

            <DropdownMenuItem v-if="onEdit" @click="props.onEdit">
                <SquarePen />
                Edit
            </DropdownMenuItem>

            <DropdownMenuItem v-if="onDelete" @click="props.onDelete">
                <Trash />
                Delete
            </DropdownMenuItem>

            <slot name="extend" />
        </DropdownMenuContent>
    </DropdownMenu>
</template>
