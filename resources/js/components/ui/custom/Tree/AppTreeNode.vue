<template>
    <div class="select-none">
        <Collapsible
            v-if="hasChildren"
            :open="isOpen"
            @update:open="(open) => emit('toggle', node, open)"
        >
            <div
                class="group flex min-h-8 items-center gap-1 rounded-md pr-2 transition-colors hover:bg-accent"
                :class="{
                    'bg-accent text-accent-foreground': isSelected,
                    'opacity-50': node.disabled,
                }"
                :style="{ paddingLeft: `${(level ?? 0) * 16}px` }"
            >
                <CollapsibleTrigger as-child>
                    <Button
                        variant="ghost"
                        size="icon"
                        class="size-6 shrink-0"
                        :aria-label="
                            isOpen
                                ? 'Collapse ' + node.label
                                : 'Expand ' + node.label
                        "
                        :disabled="node.disabled"
                    >
                        <ChevronRight
                            class="size-4 transition-transform duration-200"
                            :class="{ 'rotate-90': isOpen }"
                        />
                    </Button>
                </CollapsibleTrigger>

                <Button
                    variant="ghost"
                    class="h-auto min-w-0 flex-1 justify-start gap-2 px-0 py-1 text-left text-sm font-normal hover:bg-transparent"
                    :disabled="node.disabled"
                    @click="selectNode"
                >
                    <component
                        :is="isOpen ? FolderOpen : Folder"
                        class="size-4 shrink-0 text-muted-foreground"
                    />

                    <span class="truncate">{{ node.label }}</span>
                </Button>
            </div>

            <CollapsibleContent>
                <div class="relative">
                    <AppTreeNode
                        v-for="child in node.children"
                        :key="child.id"
                        :node="child"
                        :expanded-ids="expandedIds"
                        :level="(level ?? 0) + 1"
                        :selected-id="selectedId"
                        @select="emit('select', $event)"
                        @toggle="(id, open) => emit('toggle', id, open)"
                    />
                </div>
            </CollapsibleContent>
        </Collapsible>

        <Button
            v-else
            variant="ghost"
            class="h-auto min-h-8 w-full justify-start gap-2 rounded-md py-1 pr-2 text-left text-sm font-normal"
            :class="{
                'bg-accent text-accent-foreground': isSelected,
                'opacity-50': node.disabled,
            }"
            :style="{
                paddingLeft: `${(level ?? 0) * 16 + 28}px`,
            }"
            :disabled="node.disabled"
            @click="selectNode"
        >
            <File class="size-4 shrink-0 text-muted-foreground" />

            <span class="truncate">{{ node.label }}</span>
        </Button>
    </div>
</template>

<script setup lang="ts">
import { computed } from "vue";
import { ChevronRight, File, Folder, FolderOpen } from "@lucide/vue";

import {
    Collapsible,
    CollapsibleContent,
    CollapsibleTrigger,
} from "@/components/ui/collapsible";
import AppTreeNode from "@/components/ui/custom/Tree/AppTreeNode.vue";
import { Button } from "@/components/ui/button";

export type TreeNodeData = {
    id: string;
    label: string;
    icon?: string;
    children?: TreeNodeData[];
    disabled?: boolean;
};

const props = defineProps<{
    node: TreeNodeData;
    level?: number;
    selectedId?: string;
    expandedIds: Set<string>;
}>();

const emit = defineEmits<{
    select: [node: TreeNodeData];
    toggle: [node: TreeNodeData, open: boolean];
}>();

const hasChildren = computed(() => (props.node.children?.length ?? 0) > 0);
const isOpen = computed(() => props.expandedIds.has(props.node.id));
const isSelected = computed(() => props.selectedId === props.node.id);

const selectNode = () => {
    if (props.node.disabled) return;
    emit("select", props.node);
};
</script>
