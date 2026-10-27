<template>
    <Head title="Login">
        <meta
            head-key="description"
            name="description"
            content="Log in to Servexia to manage jobs, customers, technicians, and payments — all in one place."
        />
    </Head>

    <div class="min-h-screen w-full flex">
        <!-- LEFT PANEL -->
        <div
            class="hidden lg:flex lg:w-1/2 relative overflow-hidden justify-center items-start px-5 py-8"
        >
            <AppHero />
        </div>

        <!-- RIGHT PANEL -->
        <div class="flex-1">
            <AppLoginForm v-if="isUserLogginIn" @sign-up="handleSignUp" />
            <AppRegisterForm v-else @login="handleLogin" />
        </div>
    </div>
</template>

<script setup lang="ts">
import { Head } from "@inertiajs/vue3";
import { ref } from "vue";
import AppHero from "@/components/auth/AppHero.vue";
import AppLoginForm from "@/components/auth/AppLoginForm.vue";
import AppRegisterForm from "@/components/auth/AppRegisterForm.vue";

defineOptions({ layout: null as any });

const isUserLogginIn = ref(true);

const handleSignUp = () => {
    isUserLogginIn.value = false;
};

const handleLogin = () => {
    isUserLogginIn.value = true;
};
</script>
