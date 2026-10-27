<template>
    <div class="flex flex-col">
        <!-- Form container -->
        <div class="flex-1 flex flex-col items-center px-6">
            <div class="w-full max-w-lg py-8">
                <h2 class="text-3xl font-bold text-center text-primary">
                    Welcome!
                </h2>
                <p class="text-muted-foreground text-center mt-2">
                    Create your account on Servexia
                </p>

                <!-- Login as -->
                <div class="mt-8">
                    <div class="relative text-center mb-4">
                        <span
                            class="bg-background px-3 text-sm font-medium text-muted-foreground relative z-10"
                            >REGISTER AS</span
                        >
                        <div
                            class="absolute inset-x-0 top-1/2 border-t border-border"
                        ></div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <AppRoles />
                    </div>
                </div>

                <!-- Divider -->
                <div class="relative text-center my-6">
                    <span
                        class="bg-background px-3 text-sm text-muted-foreground relative z-10"
                        >USER INFORMATION</span
                    >
                    <div
                        class="absolute inset-x-0 top-1/2 border-t border-border"
                    ></div>
                </div>

                <!-- Form -->
                <form class="space-y-5 mt-8" @submit.prevent="handleSubmit">
                    <div class="flex gap-3">
                        <div class="flex-1">
                            <AppInput
                                v-model="form.first_name"
                                label="First Name"
                                label-position="top"
                                placeholder="John"
                            >
                                <template #leading>
                                    <UserRound />
                                </template>
                            </AppInput>
                        </div>

                        <div class="flex-1">
                            <AppInput
                                v-model="form.last_name"
                                label="Last Name"
                                label-position="top"
                                placeholder="Doe"
                            >
                                <template #leading>
                                    <UserRound />
                                </template>
                            </AppInput>
                        </div>
                    </div>

                    <AppInput
                        v-model="form.email"
                        label="Email"
                        label-position="top"
                        placeholder="johndoe@gmail.com"
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
                    </AppInput>

                    <AppCheckbox v-model="form.accept_terms">
                        I agree to the
                        <AppButton
                            label="Terms of Service"
                            variant="link"
                            class="p-0 m-0"
                        />
                        and
                        <AppButton
                            label="Privacy Policy"
                            variant="link"
                            class="p-0 m-0"
                        />
                    </AppCheckbox>

                    <AppButton label="Register" type="submit" class="w-full" />
                </form>

                <p class="text-center text-xs text-muted-foreground mt-6">
                    Already have an account?
                    <AppButton
                        label="Login here"
                        variant="link"
                        size="xs"
                        class="p-0"
                        @click="handleLogin"
                    />
                </p>
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
import { Mail, LockKeyhole, UserRound } from "@lucide/vue";
import { reactive } from "vue";
import AppRoles from "@/components/auth/AppRoles.vue";
import AppBadges from "@/components/auth/AppBadges.vue";
import AppButton from "@/components/ui/custom/AppButton.vue";
import AppCheckbox from "@/components/ui/custom/AppCheckbox.vue";

const emit = defineEmits<{
    login: [];
}>();

const form = reactive({
    first_name: "",
    last_name: "",
    email: "",
    password: "",
    accept_terms: false,
});

const handleSubmit = () => {
    console.log(form);
    // Wire this up to your auth endpoint (e.g. Laravel Fortify's /login route)
    // console.log("Logging in as", selectedRole.value, form);
};

const handleLogin = () => {
    emit("login");
};
</script>
