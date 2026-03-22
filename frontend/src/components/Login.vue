<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { useAuth } from "@/services/auth";

const router = useRouter();
// Destructuring custom auth service to get the login method and status states
const { login, loading, error, isAdmin } = useAuth()

const rules = {
    required: value => !!value || 'Required.',
    min: value => value.length >= 8 || 'Min 8 characters',
    emailMatch: () => `The email and password you entered don't match`
}

const email = ref(null)
const password = ref(null)
const visible = ref(false)
const form = ref(null); // Reference to the v-form component
const isOverlayActive = ref(true);

async function handlelogin() {
    // 1. Check if Vuetify validation rules pass
    const { valid } = await form.value.validate();
    if (!valid) return;

    loading.value = true;

    try {
        // 3. Call the service method
        await login({
            email: email.value,
            password: password.value,
        });

        // 4. Success? --> Redirect based on role
        if (isAdmin.value) {
            router.push('/admin');
        } else {
            router.push('/homepage');
        }
    } catch (err) {
        // Error is already reactive in the 'error' ref from useAuth
        // The 'error' ref from useAuth is now populated.
        // It will stay visible until the user types something new.
        console.error('Login attempt failed:', err)
    } finally {
        loading.value = false;
    }
}

// Function to clear error when user starts typing
function clearError() {
    if (error.value) error.value = "";
}
</script>

<template>
    <v-container fluid class="fill-height bg-white pa-0 overflow-hidden">
        <v-row no-gutters class="fill-height" style="min-height: 100vh;">
            <!-- Left Column: Visual Impact -->
            <v-col cols="12" md="5" class="d-none d-md-flex">
                <v-img src="/images/img6-landscape.jpg" :aspect-ratio="16 / 9" cover class="h-100 position-relative">
                    <v-overlay :model-value="isOverlayActive" persistent contained scrim="black" opacity="0.4"
                        class="d-flex align-center">
                    </v-overlay>
                </v-img>
            </v-col>

            <!-- Right Column: Login Form -->
            <v-col cols="12" md="7" class="d-flex align-center justify-center bg-white pa-4 pa-md-16">
                <v-card width="100%" max-width="480" min-height="600" border rounded="xl" elevation="0"
                    class="bg-white pa-8 pa-md-12 d-flex flex-column justify-center">
                    <v-btn variant="plain" color="blue-grey-darken-1" class="font-weight-black pa-0 mb-8 align-self-start text-uppercase"
                        @click="router.push('/')" :ripple="false" prepend-icon="mdi-chevron-left" rounded="0"
                        style="letter-spacing: 1px; font-size: 14px;">
                        BACK TO HOME
                    </v-btn>
                    <v-form ref="form" @submit.prevent="handlelogin">
                        <h1 class="text-h4 font-weight-black text-blue-grey-darken-4 mb-2 text-uppercase"
                            style="letter-spacing: -1px; font-family: 'Oswald', sans-serif !important;">
                            Log In
                        </h1>
                        <p class="text-body-1 text-blue-grey-darken-1 mb-10">
                            Enter your credentials to access your account
                        </p>
                        <!-- Input Section -->
                        <div class="mb-6">
                            <label
                                class="text-caption font-weight-black text-grey-darken-3 mb-1 d-block text-uppercase ls-1">Email
                                Address</label>
                            <v-text-field v-model="email" @input="clearError" density="compact" placeholder="name@example.com"
                                variant="outlined" color="black" base-color="grey" hide-details="auto" rounded="lg"
                                prepend-inner-icon="mdi-email-outline"></v-text-field>
                        </div>

                        <div class="mb-10">
                            <label
                                class="text-caption font-weight-black text-grey-darken-3 mb-1 d-block text-uppercase ls-1">Password</label>
                            <v-text-field v-model="password" @input="clearError" :rules="[rules.required, rules.min]"
                                :append-inner-icon="visible ? 'mdi-eye-off-outline' : 'mdi-eye-outline'"
                                :type="visible ? 'text' : 'password'" density="compact" placeholder="••••••••"
                                variant="outlined" color="black" base-color="grey"
                                @click:append-inner="visible = !visible" hide-details="auto" rounded="lg"
                                prepend-inner-icon="mdi-lock-outline"></v-text-field>
                        </div>

                        <!-- Action Button -->
                        <v-alert v-if="error" type="error" variant="tonal" class="mb-4" density="compact">
                            {{ error }}
                        </v-alert>
                        <v-btn variant="tonal"  width="100%" color="#ee6909" class="text-black font-weight-bold mb-6" type="submit"
                            height="45" :loading="loading" rounded="lg" elevation="0">
                            LOG IN
                        </v-btn>

                        <div class="mt-2 text-center">
                            <span class="text-grey-darken-1 text-body-2">Don't have an account? </span>
                            <v-btn variant="plain" color="black"
                                class="text-none font-weight-bold pa-0 text-decoration-underline"
                                @click="router.push('/signup')" :ripple="false">
                                Sign up
                            </v-btn>
                        </div>
                    </v-form>
                </v-card>
            </v-col>
        </v-row>
    </v-container>
</template>
