<template>
    <ul class="space-y-5">
        <li
            v-for="feature in features"
            :key="feature.title"
            class="flex items-start gap-3"
        >
            <div
                class="w-9 h-9 rounded-lg flex items-center justify-center shrink-0"
                :class="feature.background"
            >
                <component
                    :is="feature.icon"
                    class="w-5 h-5"
                    :class="feature.icon_color"
                />
            </div>
            <div>
                <p class="font-semibold text-sm">
                    {{ feature.title }}
                </p>
                <p class="text-sm">
                    {{ feature.desc }}
                </p>
            </div>
        </li>
    </ul>
</template>

<script setup lang="ts">
import {
    LucideIcon,
    Calendar,
    Users,
    NotepadText,
    ChartNoAxesCombined,
} from "@lucide/vue";

export type Feature = {
    title: string;
    desc: string;
    icon: LucideIcon;
    background: string;
    icon_color: string;
};

const features: Feature[] = [
    {
        title: "Manage Jobs & Bookings",
        desc: "Schedule, track, and complete jobs efficiently.",
        icon: Calendar,
        background: "bg-blue-100",
        icon_color: "text-blue-600",
    },
    {
        title: "Keep Customers Happy",
        desc: "Store customer details and service history in one place.",
        icon: Users,
        background: "bg-green-100",
        icon_color: "text-green-600",
    },
    {
        title: "Get Paid Faster",
        desc: "Create invoices and accept payments securely.",
        icon: NotepadText,
        background: "bg-purple-100",
        icon_color: "text-purple-600",
    },
    {
        title: "Grow Your Business",
        desc: "Gain insights and make better business decisions.",
        icon: ChartNoAxesCombined,
        background: "bg-orange-100",
        icon_color: "text-orange-600",
    },
];
</script>
