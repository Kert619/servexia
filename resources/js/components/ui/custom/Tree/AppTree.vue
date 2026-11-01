<template>
    <div role="tree" class="w-full">
        <template v-if="filteredNodes.length">
            <pre>{{ expanded }}</pre>
            <AppTreeNode
                v-for="node in filteredNodes"
                :key="node.id"
                :node="node"
                :expanded-ids="expandedSet"
                :level="0"
                :selected-id="selectedId"
                @select="emit('select', $event)"
                @toggle="setOpen"
            />
        </template>

        <span v-else class="text-sm">No results found...</span>
    </div>
</template>

<script setup lang="ts">
import AppTreeNode, {
    type TreeNodeData,
} from "@/components/ui/custom/Tree/AppTreeNode.vue";
import { computed } from "vue";

const props = defineProps<{
    data: TreeNodeData[];
    selectedId?: string;
    filter?: string;
}>();

const emit = defineEmits<{
    select: [node: TreeNodeData];
}>();

const trimmedQuery = computed(() => props.filter?.trim() ?? "");
const expanded = defineModel<string[]>("expanded", { default: () => [] });

const filteredNodes = computed(() =>
    trimmedQuery.value
        ? filterTree(props.data, trimmedQuery.value)
        : props.data,
);

const expandedSet = computed(() => new Set(expanded.value));

const labelMatches = (node: TreeNodeData, query: string) =>
    node.label.toLowerCase().includes(query.toLowerCase());

/**
 * Keeps nodes that match, plus their ancestors.
 * A matching node keeps its full subtree.
 */
const filterTree = (nodes: TreeNodeData[], query: string): TreeNodeData[] =>
    nodes.reduce<TreeNodeData[]>((acc, node) => {
        if (labelMatches(node, query)) {
            acc.push(node);
            return acc;
        }

        const children = filterTree(node.children ?? [], query);
        if (children.length) acc.push({ ...node, children });

        return acc;
    }, []);

const expandFilteredTree = (nodes: TreeNodeData[]) => {
    nodes.forEach((item) => {
        expanded.value.push(item.id);
        if (item.children?.length) {
            expandFilteredTree(item.children);
        }
    });
};

const setOpen = (node: TreeNodeData, open: boolean) => {
    const next = new Set(expanded.value);

    if (open) {
        next.add(node.id);
    } else {
        next.delete(node.id);
    }

    expanded.value = [...next];
};
</script>
