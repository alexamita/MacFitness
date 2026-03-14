<script setup>
const userDetails = JSON.parse(localStorage.getItem('userSignUpData')) || {}
</script>

<template>
    <v-container fluid class="fill-height bg-grey-lighten-4 pa-0">
        <v-row justify="center" align="center" no-gutters class="fill-height">
            <v-col cols="12" sm="10" md="8" lg="6" class="pa-4 pa-md-12">
                <v-card class="bg-white pa-8 pa-md-12 mx-auto" elevation="0" rounded="xl" border="thin opacity-10" max-width="600">
                    <div class="text-center mb-10">
                        <v-avatar size="120" color="black" class="mb-6 elevation-4">
                            <span class="text-h3 font-weight-black text-white">
                                {{ userDetails.name ? userDetails.name.charAt(0).toUpperCase() : 'M' }}
                            </span>
                        </v-avatar>
                        <h1 class="text-h4 font-weight-black text-black text-uppercase mb-2" style="letter-spacing: -1px;">
                            {{ userDetails.name || 'Member Profile' }}
                        </h1>
                        <v-chip v-if="userDetails.subscription" color="#ee6909" variant="tonal" border size="small" class="font-weight-black text-uppercase px-4">
                            {{ userDetails.subscription.name }}
                        </v-chip>
                    </div>

                    <v-divider class="mb-8"></v-divider>

                    <v-list class="bg-transparent pa-0">
                        <v-list-item class="px-0 py-3">
                            <template v-slot:prepend>
                                <v-icon color="#ee6909" class="mr-4">mdi-email-outline</v-icon>
                            </template>
                            <v-list-item-title class="text-caption text-grey-darken-1 font-weight-black text-uppercase mb-1">Email Address</v-list-item-title>
                            <v-list-item-subtitle class="text-body-1 text-black font-weight-medium">{{ userDetails.email }}</v-list-item-subtitle>
                        </v-list-item>

                        <v-list-item v-if="userDetails.phoneNumber || userDetails.phone" class="px-0 py-3">
                            <template v-slot:prepend>
                                <v-icon color="#ee6909" class="mr-4">mdi-phone-outline</v-icon>
                            </template>
                            <v-list-item-title class="text-caption text-grey-darken-1 font-weight-black text-uppercase mb-1">Phone Number</v-list-item-title>
                            <v-list-item-subtitle class="text-body-1 text-black font-weight-medium">{{ userDetails.phone || userDetails.phoneNumber }}</v-list-item-subtitle>
                        </v-list-item>

                        <v-list-item class="px-0 py-3">
                            <template v-slot:prepend>
                                <v-icon color="#ee6909" class="mr-4">mdi-map-marker-outline</v-icon>
                            </template>
                            <v-list-item-title class="text-caption text-grey-darken-1 font-weight-black text-uppercase mb-1">Preferred Gym</v-list-item-title>
                            <v-list-item-subtitle class="text-body-1 text-black font-weight-medium">{{ userDetails.gymLocations || userDetails.selectedGym || 'Not specified' }}</v-list-item-subtitle>
                        </v-list-item>
                    </v-list>

                    <div v-if="userDetails.subscription" class="mt-8 pa-6 bg-grey-lighten-4 rounded-lg border">
                        <div class="d-flex justify-space-between align-center mb-2">
                            <span class="text-subtitle-2 font-weight-black text-black text-uppercase">Active Subscription</span>
                            <span class="text-h6 font-weight-black text-black">{{ userDetails.subscription.price }}</span>
                        </div>
                        <p class="text-body-2 text-grey-darken-1 mb-0">Plan: {{ userDetails.subscription.name }}</p>
                    </div>

                    <v-row class="mt-6" justify="center">
                        <v-col cols="auto">
                            <v-btn v-if="!userDetails.subscription" color="#ee6909" variant="flat" rounded="lg" class="font-weight-black px-8" @click="$router.push('/bundles')">
                                UPGRADE PLAN
                            </v-btn>
                            <v-btn v-else color="#ee6909" variant="outlined" rounded="lg" class="font-weight-black px-8" @click="$router.push('/bundles')">
                                CHANGE PLAN
                            </v-btn>
                        </v-col>
                    </v-row>
                </v-card>
            </v-col>
        </v-row>
    </v-container>
</template>

<style scoped></style>
