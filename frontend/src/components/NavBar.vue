<script setup>
import { useRouter } from "vue-router";
import { useAuth } from "@/services/auth";

/**
 * INITIALIZATION
 * router: Instance for programmatic navigation (moving between pages).
 * auth: Destructuring reactive states and the logout function from our custom auth service.
 */
const router = useRouter();
const { isAuthenticated, isAdmin, logout } = useAuth();

/**
 * LOGOUT HANDLER
 * Wraps the auth service logout to also redirect the user to the landing page.
 */
function handleLogout() {
    logout(); // Clears state and localStorage
    router.push("/"); // Sends user back to landing page
}
</script>

<template>
    <v-app-bar flat class="px-md-16" color="black" border="b opacity-10">
        <!-- Logo -->
        <router-link to="/" class="text-white text-decoration-none d-flex align-center px-2">
            <img src="/logo_complete-removebg-preview-2.png" alt="MacFitness Logo"
                style="height: 200px; width: 180px; object-fit: contain;" class="mr-2" />
        </router-link>

        <v-spacer></v-spacer>

        <v-btn variant="text" @click="router.push('/homepage')" v-if="isAuthenticated" class="mx-1 text-caption font-weight-bold">HOME</v-btn>
        <v-btn variant="text" @click="router.push('/bundles')" class="mx-1 text-caption font-weight-bold">BUNDLES</v-btn>
        <v-btn variant="text" @click="router.push('/gymlocations')" class="mx-1 text-caption font-weight-bold">LOCATIONS</v-btn>
        <v-btn @click="router.push('/admin')" v-if="isAdmin" color="#ee6909" variant="flat" class="mx-1 text-caption font-weight-black text-white">ADMIN</v-btn>

        <v-divider vertical inset class="mx-4 border-opacity-25"></v-divider>

        <!-- Profile Menu - Only visible when logged in -->
        <div v-if="isAuthenticated" class="d-flex align-center">
            <v-btn icon="mdi-account" class="mx-2" variant="tonal" color="grey-lighten-2" size="small">
                <v-icon size="20">mdi-account</v-icon>
                <v-menu activator="parent">
                    <v-list width="160" rounded="lg">
                        <v-list-item prepend-icon="mdi-account-outline" @click="router.push('/profile')">PROFILE
                        </v-list-item>
                        <v-list-item prepend-icon="mdi-logout" color="error" @click="handleLogout()">LOGOUT
                        </v-list-item>
                    </v-list>
                </v-menu>
            </v-btn>
        </div>

        <!-- Login/Sign Up buttons - Only visible when NOT logged in -->
        <template v-else>
            <v-btn @click="router.push('/login')" variant="text" class="font-weight-bold text-caption">
                LOGIN
            </v-btn>
            <v-btn @click="router.push('/signup')" variant="flat" color="#ee6909" class="font-weight-bold text-black text-caption rounded-lg ml-2">
                SIGN UP
            </v-btn>
        </template>
    </v-app-bar>
</template>

<style scoped></style>
