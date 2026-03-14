<script setup>
// SCRIPT SETUP: This is the modern Vue 3 way to handle logic.
// Variables and imports defined here are automatically available to the template.
import { RouterView } from 'vue-router'
import NavBar from '@/components/NavBar.vue' // Using @ for absolute pathing
</script>

<template>
    <v-app>
        <NavBar />
        <v-main>
            <RouterView />
        </v-main>
    </v-app>
</template>

<style>

</style>
