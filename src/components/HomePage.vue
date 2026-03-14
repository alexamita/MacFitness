<script setup>


const userDetails = JSON.parse(localStorage.getItem('userSignUpData')) || {}
const userName = userDetails.name || "Member";

const getExpiryDays = (name) => {
    if (!name) return 0;
    const n = name.toLowerCase();
    if (n.includes('daily')) return 1;
    if (n.includes('one month')) return 30;
    if (n.includes('three months')) return 90;
    if (n.includes('six months')) return 180;
    if (n.includes('12 month')) return 365;
    return 30; // Default fallback
};

const expiryDays = getExpiryDays(userDetails.subscription?.name);
</script>

<template>
    <v-container fluid class="bg-grey-lighten-5 pa-0 fill-height overflow-y-auto">
        <v-container class="py-16 px-6 h-100 d-flex flex-column justify-center">
            <!-- 1. Unified Hero & Status -->
            <v-row align="center" justify="center">
                <v-col cols="12" md="11" lg="10">
                    <v-row align="center">
                        <v-col cols="12" md="7">
                            <h1 class="text-h2 font-weight-black text-black text-uppercase mb-2">
                                Karibu, <span style="color: #ee6909;">{{ userName }}</span>!
                            </h1>

                            <div class="d-flex align-center bg-white pa-6 rounded-xl border border-opacity-10 mb-6" elevation="0">
                                <v-avatar color="#ee6909" variant="tonal" size="56" class="mr-6">
                                    <v-icon color="#ee6909" size="28">mdi-wallet-membership</v-icon>
                                </v-avatar>
                                <div>
                                    <div class="text-caption text-grey font-weight-black text-uppercase">
                                        {{ userDetails.subscription ? userDetails.subscription.name : 'No Active Plan' }}
                                    </div>
                                    <div class="text-h6 font-weight-black text-black">
                                        {{ userDetails.subscription ? `Active • Expiring in ${expiryDays} days` : 'Explore our bundles' }}
                                    </div>
                                </div>
                                <v-spacer></v-spacer>
                                <v-btn color="#ee6909" variant="flat" rounded="lg" class="font-weight-black px-6" @click="$router.push('/bundles')">
                                    {{ userDetails.subscription ? 'Renew' : 'Explore' }}
                                </v-btn>
                            </div>

                            <!-- Preferred Gym Status -->
                            <div class="d-flex align-center bg-white pa-6 rounded-xl border border-opacity-10 mb-8" elevation="0">
                                <v-avatar color="black" variant="tonal" size="56" class="mr-6">
                                    <v-icon color="black" size="28">mdi-map-marker</v-icon>
                                </v-avatar>
                                <div>
                                    <div class="text-caption text-grey font-weight-black text-uppercase">
                                        Home Gym Location
                                    </div>
                                    <div class="text-h6 font-weight-black text-black">
                                        {{ userDetails.gymLocations || 'Not Selected' }}
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex gap-12">
                                <div>
                                    <div class="text-h3 font-weight-black text-black">5</div>
                                    <div class="text-caption text-grey font-weight-black text-uppercase">Day Streak</div>
                                </div>
                                <v-divider vertical class="mx-4"></v-divider>
                                <div>
                                    <div class="text-h3 font-weight-black text-black">1,450</div>
                                    <div class="text-caption text-grey font-weight-black text-uppercase">Loyalty Pts</div>
                                </div>
                            </div>
                        </v-col>

                        <v-col cols="12" md="5" class="d-flex justify-md-end">
                            <v-card class="pa-10 bg-black text-white text-center" elevation="12" rounded="xl" width="100%" max-width="440">
                                <v-img src="https://api.qrserver.com/v1/create-qr-code/?size=240x240&data=MACFIT-USER-123456"
                                    max-width="220" class="mx-auto bg-white pa-4 rounded-lg mb-6"></v-img>
                                <div class="text-subtitle-1 font-weight-black text-uppercase opacity-70 mb-1">Entry Pass</div>
                                <div class="text-h6 font-weight-black">#0824-MF</div>
                            </v-card>
                        </v-col>
                    </v-row>
                </v-col>
            </v-row>
        </v-container>
    </v-container>
</template>

<style scoped></style>
