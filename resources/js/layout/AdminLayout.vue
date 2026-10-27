<template>
    <SidebarProvider storage-key="sidebar">
        <Sidebar collapsible="icon">
            <SidebarHeader>
                <SidebarMenu>
                    <SidebarMenuItem>
                        <SidebarMenuButton size="lg">
                            <div
                                class="flex aspect-square size-8 items-center justify-center rounded-lg bg-sidebar-primary text-sidebar-primary-foreground"
                            >
                                <GalleryVerticalEnd class="size-4" />
                            </div>
                            <div
                                class="grid flex-1 text-left text-sm leading-tight"
                            >
                                <span class="truncate font-semibold"
                                    >Acme Inc</span
                                >
                                <span class="truncate text-xs">Enterprise</span>
                            </div>
                        </SidebarMenuButton>
                    </SidebarMenuItem>
                </SidebarMenu>
            </SidebarHeader>

            <SidebarContent>
                <SidebarGroup>
                    <SidebarGroupLabel>Data Entry</SidebarGroupLabel>
                    <SidebarGroupContent>
                        <SidebarMenu>
                            <SidebarMenuItem>
                                <SidebarMenuButton
                                    as-child
                                    :is-active="isActive('/admin/services')"
                                    class="data-[active=true]:bg-primary data-[active=true]:text-primary-foreground"
                                >
                                    <Link href="/admin/services">
                                        <Unplug />
                                        Services</Link
                                    >
                                </SidebarMenuButton>
                            </SidebarMenuItem>

                            <SidebarMenuItem>
                                <SidebarMenuButton
                                    as-child
                                    :is-active="isActive('/admin/categories')"
                                    class="data-[active=true]:bg-primary data-[active=true]:text-primary-foreground"
                                >
                                    <Link href="/admin/categories">
                                        <LayoutDashboard />
                                        Categories</Link
                                    >
                                </SidebarMenuButton>
                            </SidebarMenuItem>
                        </SidebarMenu>
                    </SidebarGroupContent>
                </SidebarGroup>
            </SidebarContent>
        </Sidebar>

        <SidebarInset class="h-screen overflow-hidden">
            <!-- Header -->
            <header
                class="bg-background flex h-10 shrink-0 items-center gap-2 border-b"
            >
                <div class="flex items-center gap-2 px-4">
                    <SidebarTrigger class="-ml-1" />
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
    Sidebar,
    SidebarContent,
    SidebarGroup,
    SidebarGroupContent,
    SidebarGroupLabel,
    SidebarHeader,
    SidebarInset,
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
    SidebarProvider,
    SidebarTrigger,
} from "@/components/ui/sidebar";
import { GalleryVerticalEnd, Unplug, LayoutDashboard } from "@lucide/vue";
import { Link, usePage } from "@inertiajs/vue3";

const page = usePage();

const isActive = (path: string) => {
    return page.url.startsWith(path);
};
</script>
