<script setup>
import "leaflet/dist/leaflet.css"
import * as L from 'leaflet';
import { ref, onMounted } from 'vue';

const locations = [
    {
        id: 'map-iron-forge',
        name: 'Iron Forge Fitness',
        address: 'Westlands, Nairobi',
        phone: '+254 712 345 678',
        coords: [-1.2667, 36.7917],
        description: 'Elite strength and conditioning facility featuring professional-grade plates and specialized racks.'
    },
    {
        id: 'map-pulse',
        name: 'Pulse Performance Center',
        address: 'Kilimani, Nairobi',
        phone: '+254 723 456 789',
        coords: [-1.2893, 36.7885],
        description: 'High-intensity hub specialized in CrossFit, athletic conditioning, and heart-rate monitored HIIT sessions.'
    },
    {
        id: 'map-zencore',
        name: 'ZenCore Wellness Studio',
        address: 'Lavington, Nairobi',
        phone: '+254 734 567 890',
        coords: [-1.2725, 36.7725],
        description: 'Sanctuary for somatic yoga, advanced pilates, and premium rehabilitation services.'
    },
    {
        id: 'map-metrofit',
        name: 'MetroFit Downtown',
        address: 'CBD, Nairobi',
        phone: '+254 745 678 901',
        coords: [-1.2833, 36.8167],
        description: '24/7 central hub for urban athletes, offering full-range equipment and expert coaching.'
    },
    {
        id: 'map-elite-sports',
        name: 'Elite Sports Hub',
        address: 'Karen, Nairobi',
        phone: '+254 756 789 012',
        coords: [-1.3200, 36.7000],
        description: 'World-class sports performance center for elite athletes and performance enthusiasts.'
    }
];

onMounted(() => {
    locations.forEach(loc => {
        const mapElement = document.getElementById(loc.id);
        if (mapElement) {
            const map = L.map(loc.id, {
                zoomControl: true,
                attributionControl: false
            }).setView(loc.coords, 14);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19
            }).addTo(map);

            L.marker(loc.coords).addTo(map);
        }
    });
});
</script>

<template>
    <v-container fluid class="bg-black pt-16 pb-12" style="min-height: 100vh;">
        <v-container>
            <!-- Header Section -->
            <v-row class="mb-12">
                <v-col cols="12">
                    <v-chip color="#ee6909" variant="tonal" rounded="pill" class="font-weight-black mb-4 px-4 text-uppercase border-orange-lighten-1 border-opacity-10">LOCATIONS</v-chip>
                    <h2 class="text-h3 font-weight-black text-white">DISCOVER YOUR <span style="color: #ee6909;">POWER HUB</span></h2>
                    <p class="text-grey-lighten-1 mt-4 text-h6 font-weight-regular" style="max-width: 600px; line-height: 1.6;">
                        One membership. Multiple elite locations. Access the best training floors in Nairobi.
                    </p>
                </v-col>
            </v-row>

            <!-- Locations List -->
            <v-row>
                <v-col v-for="loc in locations" :key="loc.id" cols="12" class="mb-10">
                    <v-card theme="dark" rounded="xl" elevation="12" border class="overflow-hidden" style="border-color: rgba(238, 105, 9, 0.1) !important;">
                        <v-row no-gutters>
                            <!-- Info Column -->
                            <v-col cols="12" md="5" class="pa-8 pa-md-10 d-flex flex-column">
                                <div class="d-flex align-center mb-6">
                                    <v-icon color="#ee6909" size="36" class="me-4">mdi-map-marker-radius</v-icon>
                                    <h3 class="text-h4 font-weight-black text-uppercase" style="letter-spacing: 1px;">{{ loc.name }}</h3>
                                </div>

                                <v-divider class="mb-6" style="opacity: 0.2;"></v-divider>

                                <v-list bg-transparent density="comfortable" class="pa-0 mb-6 font-weight-medium">
                                    <v-list-item class="px-0">
                                        <template v-slot:prepend>
                                            <v-icon color="grey-lighten-1" class="me-3">mdi-map-outline</v-icon>
                                        </template>
                                        <v-list-item-title class="text-grey-lighten-2 text-wrap">{{ loc.address }}</v-list-item-title>
                                    </v-list-item>

                                    <v-list-item class="px-0">
                                        <template v-slot:prepend>
                                            <v-icon color="grey-lighten-1" class="me-3">mdi-phone-outline</v-icon>
                                        </template>
                                        <v-list-item-title class="text-grey-lighten-2">{{ loc.phone }}</v-list-item-title>
                                    </v-list-item>
                                </v-list>

                                <p class="text-body-1 text-grey-lighten-1 italic" style="line-height: 1.6; opacity: 0.8;">
                                    "{{ loc.description }}"
                                </p>
                            </v-col>

                            <!-- Map Column -->
                            <v-col cols="12" md="7" class="bg-grey-darken-4">
                                <!-- Leaflet map container -->
                                <div :id="loc.id" style="height: 100%; min-height: 400px; width: 100%;"></div>
                            </v-col>
                        </v-row>
                    </v-card>
                </v-col>
            </v-row>
        </v-container>
    </v-container>
</template>

<style scoped>
</style>
