<script setup>
import { useRouter } from "vue-router";
import { useAuth } from "@/services/auth";

/**
 * INITIALIZATION
 * router: Instance for programmatic navigation (moving between pages).
 * auth: Destructuring reactive states and the logout function from our custom auth service.
 */
const router = useRouter();
const { isAuthenticated, isAdmin, logout} = useAuth();

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
    <v-app-bar flat class="px-16" color="black">
        <!-- Logo -->
        <router-link to="/" class="text-white text-decoration-none d-flex align-center px-2">
            <img
                src="/logo_complete-removebg-preview-2.png"
                alt="MacFitness Logo"
                style="height: 200px; width: 180px; object-fit: contain;"
                class="mr-2"
            />
        </router-link>

        <v-spacer></v-spacer>

        <v-btn @click="router.push('/homepage')" v-if="isAuthenticated" class="mx-2">HOME</v-btn>
        <v-btn @click="router.push('/bundles')" class="mx-2">BUNDLES</v-btn>
        <v-btn @click="router.push('/gymlocations')" class="mx-2">LOCATIONS</v-btn>
        <v-btn @click="router.push('/admin')" v-if="isAdmin" class="mx-2">ADMIN</v-btn>

        <!-- Profile Menu - Only visible when logged in -->
        <div v-if="isAuthenticated" class="d-flex align-center">
            <v-btn icon="mdi-account" class="mx-2">
                <v-icon>mdi-account</v-icon>
                <v-menu activator="parent">
                    <v-list>
                        <v-list-item @click="router.push('/profile')">
                            <v-list-item-title>PROFILE</v-list-item-title>
                        </v-list-item>
                        <v-list-item @click="handleLogout()">
                            <v-list-item-title class="text-error">LOGOUT</v-list-item-title>
                        </v-list-item>
                    </v-list>
                </v-menu>
            </v-btn>
        </div>

        <!-- Login/Sign Up buttons - Only visible when NOT logged in -->
        <template v-else>
            <v-btn @click="router.push('/login')" variant="outlined" class="font-weight-bold mx-2">
                LOGIN
            </v-btn>
            <v-btn @click="router.push('/signup')" variant="flat" color="#ee6909" class="rounded-l text-black font-weight-bold mx-2">
                SIGN UP
            </v-btn>
        </template>
    </v-app-bar>
</template>

<style scoped>
</style>
