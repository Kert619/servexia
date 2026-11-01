<template>
    <Sidebar collapsible="offcanvas">
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
                            <span class="truncate font-semibold">Acme Inc</span>
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
                                @click="setOpenMobile(false)"
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
                                @click="setOpenMobile(false)"
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
</template>

<script setup lang="ts">
import {
    Sidebar,
    SidebarContent,
    SidebarGroup,
    SidebarGroupContent,
    SidebarGroupLabel,
    SidebarHeader,
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
    useSidebar,
} from "@/components/ui/sidebar";
import { GalleryVerticalEnd, Unplug, LayoutDashboard } from "@lucide/vue";
import { Link, usePage } from "@inertiajs/vue3";

const page = usePage();

const { setOpenMobile } = useSidebar();

const isActive = (path: string) => {
    return page.url.startsWith(path);
};
</script>
