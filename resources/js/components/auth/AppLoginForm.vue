<template>
    <div class="flex flex-col">
        <!-- Form container -->
        <div class="flex-1 flex flex-col items-center px-6">
            <div class="w-full max-w-lg py-8">
                <h2 class="text-3xl font-bold text-center text-primary">
                    Welcome Back!
                </h2>
                <p class="text-muted-foreground text-center mt-2">
                    Login to access your account
                </p>

                <!-- Login as -->
                <div class="mt-8">
                    <div class="relative text-center mb-4">
                        <span
                            class="bg-background px-3 text-sm font-medium text-muted-foreground relative z-10"
                            >LOGIN AS</span
                        >
                        <div
                            class="absolute inset-x-0 top-1/2 border-t border-border"
                        ></div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <AppRoles />
                    </div>
                </div>

                <!-- Form -->
                <form class="space-y-5 mt-8" @submit.prevent="handleSubmit">
                    <AppInput
                        v-model="form.email"
                        label="Email"
                        label-position="top"
                        placeholder="Enter your email"
                    >
                        <template #leading>
                            <Mail />
                        </template>
                    </AppInput>

                    <AppInput
                        v-model="form.password"
                        type="password"
                        label="Password"
                        label-position="top"
                        placeholder="Enter your password"
                    >
                        <template #leading>
                            <LockKeyhole />
                        </template>

                        <template #top-right>
                            <AppButton
                                label="Forgot password?"
                                variant="link"
                                size="xs"
                                class="p-0"
                                tabindex="-1"
                            />
                        </template>
                    </AppInput>

                    <AppCheckbox v-model="form.remember" label="Remember Me" />

                    <AppButton label="Log In" type="submit" class="w-full" />
                </form>

                <p class="text-center text-xs text-muted-foreground mt-6">
                    Don't have an account?
                    <AppButton
                        label="Sign up here"
                        variant="link"
                        size="xs"
                        class="p-0"
                        @click="handleSignUp"
                    />
                </p>

                <!-- Divider -->
                <div class="relative text-center my-6">
                    <span
                        class="bg-background px-3 text-sm text-muted-foreground relative z-10"
                        >OR CONTINUE WITH
                    </span>
                    <div
                        class="absolute inset-x-0 top-1/2 border-t border-border"
                    ></div>
                </div>

                <AppButton
                    label="Continue with Google"
                    variant="outline"
                    class="w-full"
                >
                    <template #before>
                        <GoogleLogo />
                    </template>
                </AppButton>
            </div>

            <!-- Trust badges -->
            <div class="border-t border-border px-6 lg:px-12 py-6">
                <div
                    class="max-w-3xl mx-auto grid grid-cols-2 sm:grid-cols-4 gap-6"
                >
                    <AppBadges />
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import AppInput from "@/components/ui/custom/AppInput.vue";
import { Mail, LockKeyhole } from "@lucide/vue";
import { reactive } from "vue";
import AppRoles from "@/components/auth/AppRoles.vue";
import AppBadges from "@/components/auth/AppBadges.vue";
import AppCheckbox from "@/components/ui/custom/AppCheckbox.vue";
import AppButton from "@/components/ui/custom/AppButton.vue";
import GoogleLogo from "@/components/common/GoogleLogo.vue";

const emit = defineEmits<{
    signUp: [];
}>();

const form = reactive({
    email: "",
    password: "",
    remember: true,
});

const handleSubmit = () => {
    console.log(form);
    // Wire this up to your auth endpoint (e.g. Laravel Fortify's /login route)
    // console.log("Logging in as", selectedRole.value, form);
};

const handleSignUp = () => {
    emit("signUp");
};
</script>
