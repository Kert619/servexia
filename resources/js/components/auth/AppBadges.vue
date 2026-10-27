<template>
    <div
        v-for="badge in badges"
        :key="badge.title"
        class="flex items-start gap-2"
    >
        <component
            :is="badge.icon"
            class="w-5 h-5 text-primary shrink-0 mt-0.5"
        />
        <div>
            <p class="text-sm font-semibold leading-tight">
                {{ badge.title }}
            </p>
            <p class="text-xs text-muted-foreground leading-tight">
                {{ badge.description }}
            </p>
        </div>
    </div>
</template>

<script setup lang="ts">
import {
    LucideIcon,
    ShieldCheck,
    LockKeyhole,
    Headset,
    Award,
} from "@lucide/vue";

export type Badge = {
    title: string;
    description: string;
    icon: LucideIcon;
};

const badges: Badge[] = [
    {
        title: "Secure & Reliable",
        description: "Your data is safe with us",
        icon: ShieldCheck,
    },
    {
        title: "Encrypted Data",
        description: "We protect your information",
        icon: LockKeyhole,
    },
    {
        title: "Local Support",
        description: "We're here to help you",
        icon: Headset,
    },
    {
        title: "Trusted by Businesses",
        description: "Across the Philippines",
        icon: Award,
    },
];
</script>
