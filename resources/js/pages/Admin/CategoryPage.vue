<template>
    <div class="flex h-full">
        <div class="w-80 p-3 space-y-1 border overflow-y-auto">
            <AppInput
                v-model="filter"
                placeholder="Search..."
                :debounce="500"
            />

            <Tree
                :data="tree"
                :filter="filter"
                :selected-id="selectedId"
                @select="handleSelected"
            />
        </div>
        <div class="flex-1 overflow-y-auto">
            <pre>Selected category: {{ selectedId }}</pre>
        </div>
    </div>
</template>

<script setup lang="ts">
import { computed, ref } from "vue";
import Tree from "@/components/ui/custom/Tree/AppTree.vue";
import type { TreeNodeData } from "@/components/ui/custom/Tree/AppTreeNode.vue";
import { Category } from "@/types/category";
import AppInput from "@/components/ui/custom/AppInput.vue";

const props = defineProps<{
    categories: Category[];
}>();

const filter = ref("");
const selectedId = ref<string>();

const tree = computed(() =>
    buildTree(props.categories, (c) => c.parent_category_id),
);

const buildTree = (
    categories: Category[],
    getParentId: (category: Category) => number | null,
) => {
    const byParent = new Map<number | null, Category[]>();

    for (const category of categories) {
        const key = getParentId(category) ?? null;
        const group = byParent.get(key);

        if (group) {
            group.push(category);
        } else {
            byParent.set(key, [category]);
        }
    }

    const build = (parentId: number | null): TreeNodeData[] => {
        return (byParent.get(parentId) ?? []).map((category) => {
            const tree = {
                id: category.category_id.toString(),
                label: category.category_name,
                ...category,
            } as TreeNodeData;

            return {
                ...tree,
                children: build(category.category_id),
            } as TreeNodeData;
        });
    };

    return build(null);
};

const handleSelected = (category: TreeNodeData) => {
    selectedId.value = category.id;
    console.log(category);
};
</script>
