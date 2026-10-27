<script setup lang="ts">
import { ref } from "vue";

import Tree from "@/components/ui/custom/Tree/AppTree.vue";
import type { TreeNodeData } from "@/components/ui/custom/Tree/AppTreeNode.vue";

const selectedId = ref<string>();

const treeData: TreeNodeData[] = [
    {
        id: "src",
        label: "src",
        defaultExpanded: true,
        children: [
            {
                id: "components",
                label: "components",
                defaultExpanded: true,
                children: [
                    {
                        id: "button",
                        label: "Button.vue",
                    },
                    {
                        id: "input",
                        label: "Input.vue",
                    },
                    {
                        id: "tree",
                        label: "Tree.vue",
                    },
                ],
            },
            {
                id: "views",
                label: "views",
                children: [
                    {
                        id: "home",
                        label: "Home.vue",
                    },
                    {
                        id: "about",
                        label: "About.vue",
                    },
                ],
            },
            {
                id: "app",
                label: "App.vue",
            },
            {
                id: "main",
                label: "main.ts",
            },
        ],
    },
    {
        id: "public",
        label: "public",
        children: [
            {
                id: "favicon",
                label: "favicon.ico",
            },
            {
                id: "logo",
                label: "logo.svg",
            },
        ],
    },
    {
        id: "package",
        label: "package.json",
    },
];

function handleSelect(node: TreeNodeData) {
    selectedId.value = node.id;

    console.log("Selected node:", node);
}
</script>

<template>
    <div class="mx-auto max-w-md p-6">
        <h2 class="mb-4 text-lg font-semibold">Project Explorer</h2>

        <div class="rounded-lg border p-3">
            <Tree
                :data="treeData"
                :selected-id="selectedId"
                @select="handleSelect"
            />
        </div>

        <div v-if="selectedId" class="mt-4 rounded-md bg-muted p-3 text-sm">
            Selected node ID: {{ selectedId }}
        </div>
    </div>
</template>
