<template>
    <div role="tree" class="w-full space-y-0.5">
        <AppInput v-model="query" placeholder="Search..." :debounce="500" />
        <AppTreeNode
            v-for="node in filteredNodes"
            :key="node.id"
            :node="node"
            :expand="!!trimmedQuery"
            :level="0"
            :selected-id="selectedId"
            @select="emit('select', $event)"
        />
    </div>
</template>

<script setup lang="ts">
import AppTreeNode, {
    type TreeNodeData,
} from "@/components/ui/custom/Tree/AppTreeNode.vue";
import { computed, ref } from "vue";
import AppInput from "@/components/ui/custom/AppInput.vue";

const props = defineProps<{
    data: TreeNodeData[];
    selectedId?: string;
}>();

const emit = defineEmits<{
    select: [node: TreeNodeData];
}>();

const query = ref("");
const trimmedQuery = computed(() => query.value.trim());

const filteredNodes = computed(() =>
    trimmedQuery.value
        ? filterTree(props.data, trimmedQuery.value)
        : props.data,
);

const labelMatches = (node: TreeNodeData, query: string) =>
    node.label.toLowerCase().includes(query.toLowerCase());

/** True if any descendant (not the node itself) matches. */
const hasMatchingDescendant = (node: TreeNodeData, query: string): boolean =>
    (node.children ?? []).some(
        (child) =>
            labelMatches(child, query) || hasMatchingDescendant(child, query),
    );

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
</script>
