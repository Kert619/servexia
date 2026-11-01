<template>
    <SidebarProvider storage-key="sidebar">
        <AdminSideBar />

        <SidebarInset class="h-screen overflow-hidden">
            <!-- Header -->
            <header
                class="bg-primary text-primary-foreground flex h-10 shrink-0 items-center gap-2 border-b"
            >
                <div class="flex items-center gap-2 px-4">
                    <SidebarTrigger class="-ml-1" />
                    <span>Exia</span>
                </div>
            </header>

            <!-- ONLY THIS AREA SCROLLS -->
            <div class="flex-1 overflow-auto p-2">
                <slot />
            </div>
        </SidebarInset>
    </SidebarProvider>
</template>

<script setup lang="ts">
import {
    SidebarInset,
    SidebarProvider,
    SidebarTrigger,
} from "@/components/ui/sidebar";
import AdminSideBar from "@/components/admin/common/AdminSideBar.vue";
</script>
