<template>
    <!-- Background image, sits behind everything in this panel -->
    <img
        :src="heroImage"
        alt="Person and a van in the background"
        class="absolute inset-0 w-full h-full object-cover object-[center_60%]"
    />

    <!-- Background overlay to make the text visible -->
    <div class="absolute inset-0 bg-black/5"></div>

    <!-- Logo + Headline + features -->
    <div class="relative z-10 max-w-lg">
        <div class="relative z-10 flex items-center gap-2 mb-5">
            <AppLogo />
        </div>

        <AppSlogan />

        <div class="mt-8">
            <AppFeatures />
        </div>
    </div>
</template>

<script setup lang="ts">
import heroImage from "@/Assets/login_background.png";
import AppLogo from "@/components/common/AppLogo.vue";
import AppFeatures from "@/components/auth/AppFeatures.vue";
import AppSlogan from "@/components/auth/AppSlogan.vue";
</script>
