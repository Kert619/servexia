<template>
    <button
        v-for="role in roles"
        :key="role.value"
        type="button"
        @click="selectedRole = role.value"
        class="relative border rounded-xl p-4 text-center transition-colors cursor-pointer"
        :class="
            selectedRole === role.value
                ? 'border-primary ring-1 ring-primary '
                : 'border-border hover:border-ring'
        "
    >
        <span
            v-if="selectedRole === role.value"
            class="absolute top-2 right-2 w-5 h-5 text-primary"
        >
            <CircleCheck />
        </span>

        <div
            class="w-8 h-8 mx-auto rounded-lg flex items-center justify-center mb-2"
            :class="role.background"
        >
            <component
                :is="role.icon"
                class="w-5 h-5"
                :class="role.icon_color"
            />
        </div>
        <p class="font-semibold text-sm">
            {{ role.title }}
        </p>
        <p class="text-xs text-muted-foreground mt-1 leading-snug">
            {{ role.description }}
        </p>
    </button>
</template>

<script setup lang="ts">
import { RoleType } from "@/types/role";
import { LucideIcon, Building, User, CircleCheck } from "@lucide/vue";

type Role = {
    value: RoleType;
    title: string;
    description: string;
    icon: LucideIcon;
    background: string;
    icon_color: string;
};

const selectedRole = defineModel<RoleType>({ default: "admin" });

const roles: Role[] = [
    {
        value: "admin",
        title: "Company / Admin",
        description: "For business owners and team members",
        icon: Building,
        background: "bg-blue-100",
        icon_color: "text-blue-600",
    },
    {
        value: "customer",
        title: "Customer",
        description: "Book services and track your requests",
        icon: User,
        background: "bg-green-100",
        icon_color: "text-green-600",
    },
];
</script>
