<script setup>
import { computed } from 'vue';
import { useAuth } from '@/services/auth';

const { user } = useAuth();

const userName = computed(() => user.value?.name || "Member");
const userId = computed(() => user.value?.id || '0824');
const subscription = computed(() => user.value?.subscription || null);

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

const expiryDays = computed(() => getExpiryDays(subscription.value?.name));
</script>

<template>
    <v-container fluid class="bg-grey-lighten-5 pa-0 fill-height overflow-y-auto">
        <v-container class="py-16 px-6 h-100 d-flex flex-column justify-center">
            <!-- 1. Unified Hero & Status -->
            <v-row align="center" justify="center">
                <v-col cols="12" md="11" lg="10">
                    <v-row align="center">
                        <v-col cols="12" md="7">
                            <h1 class="text-h5 font-weight-black text-black text-uppercase mb-2">
                                Karibu, <span style="color: #ee6909;">{{ userName }}</span>!
                            </h1>

                            <div class="d-flex align-center bg-white pa-6 rounded-xl border border-opacity-10 mb-6"
                                elevation="0">
                                <v-avatar color="#ee6909" variant="tonal" size="56" class="mr-6">
                                    <v-icon color="#ee6909" size="28">mdi-wallet-membership</v-icon>
                                </v-avatar>
                                <div>
                                    <div class="text-caption text-grey font-weight-black text-uppercase">
                                        {{ subscription ? subscription.name : 'No Active Plan' }}
                                    </div>
                                    <div class="text-h6 font-weight-black text-black">
                                        {{ subscription ? `Active • Expiring in ${expiryDays} days` : 'Explore our bundles' }}
                                    </div>
                                </div>
                                <v-spacer></v-spacer>
                                <v-btn color="#ee6909" variant="flat" rounded="lg" class="font-weight-black px-6"
                                    @click="$router.push('/bundles')">
                                    {{ subscription ? 'Renew' : 'Explore' }}
                                </v-btn>
                            </div>

                            <!-- Preferred Gym Status -->
                            <div class="d-flex align-center bg-white pa-6 rounded-xl border border-opacity-10 mb-8"
                                elevation="0">
                                <v-avatar color="black" variant="tonal" size="56" class="mr-6">
                                    <v-icon color="black" size="28">mdi-map-marker</v-icon>
                                </v-avatar>
                                <div>
                                    <div class="text-caption text-grey font-weight-black text-uppercase">
                                        Home Gym Location
                                    </div>
                                    <div class="text-h6 font-weight-black text-black">
                                        {{ user?.gym_id || 'Not Selected' }}
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex gap-12">
                                <div>
                                    <div class="text-h3 font-weight-black text-black">5</div>
                                    <div class="text-caption text-grey font-weight-black text-uppercase">Day Streak
                                    </div>
                                </div>
                                <v-divider vertical class="mx-4"></v-divider>
                                <div>
                                    <div class="text-h3 font-weight-black text-black">1,450</div>
                                    <div class="text-caption text-grey font-weight-black text-uppercase">Loyalty Pts
                                    </div>
                                </div>
                            </div>
                        </v-col>

                        <v-col cols="12" md="5" class="d-flex justify-md-end">
                            <v-card class="membership-card pa-10 text-white text-center d-flex flex-column align-center" 
                                elevation="24" rounded="xl" width="100%" max-width="440">
                                <!-- Card Glass Effect Overlay -->
                                <div class="card-glow"></div>
                                
                                <div class="d-flex justify-space-between w-100 mb-8 align-center">
                                    <div class="text-left">
                                        <div class="text-h6 font-weight-black leading-none mb-1">MacFit</div>
                                        <div class="text-caption font-weight-bold text-grey-lighten-1 uppercase ls-1">Elite Membership</div>
                                    </div>
                                    <v-chip color="success" size="small" variant="flat" class="font-weight-black">
                                        <v-icon start icon="mdi-check-decagram" size="14"></v-icon> VERIFIED
                                    </v-chip>
                                </div>

                                <div class="qr-wrapper mb-8">
                                    <div class="scanner-line"></div>
                                    <v-img
                                        :src="`https://api.qrserver.com/v1/create-qr-code/?size=240x240&data=MACFIT-USER-${userId}`"
                                        width="200" height="200" class="mx-auto bg-white pa-3 rounded-lg"></v-img>
                                </div>

                                <div class="text-overline font-weight-bold text-grey-lighten-1 mb-1 ls-2">Digital Access Key</div>
                                <div class="text-h5 font-weight-black letter-spacing-tight mb-2">#{{ userId }}-MF</div>
                                
                                <div class="mt-4 pt-4 border-t w-100 border-opacity-10 d-flex justify-center ga-4">
                                    <v-icon size="18" color="grey-lighten-2">mdi-nfc</v-icon>
                                    <span class="text-caption font-weight-bold text-grey-lighten-2 uppercase">Tap to scan</span>
                                </div>
                            </v-card>
                        </v-col>
                    </v-row>
                </v-col>
            </v-row>
        </v-container>
    </v-container>
</template>

<style scoped>
.membership-card {
    background: linear-gradient(135deg, #000000 0%, #1a1a1a 100%);
    position: relative;
    overflow: hidden;
    border: 1px solid rgba(255, 255, 255, 0.1);
    transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
}

.membership-card:hover {
    transform: translateY(-10px) scale(1.02);
    box-shadow: 0 40px 80px rgba(0, 0, 0, 0.5) !important;
}

.card-glow {
    position: absolute;
    top: -50%;
    left: -50%;
    width: 200%;
    height: 200%;
    background: radial-gradient(circle, rgba(238, 105, 9, 0.1) 0%, transparent 70%);
    pointer-events: none;
}

.qr-wrapper {
    position: relative;
    padding: 12px;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 16px;
    border: 1px solid rgba(255, 255, 255, 0.2);
}

.scanner-line {
    position: absolute;
    top: 12px;
    left: 12px;
    right: 12px;
    height: 2px;
    background: #ee6909;
    box-shadow: 0 0 15px #ee6909, 0 0 5px #ee6909;
    z-index: 2;
    animation: scan 3s ease-in-out infinite;
    border-radius: 2px;
}

@keyframes scan {
    0%, 100% { top: 12px; opacity: 0; }
    10%, 90% { opacity: 1; }
    50% { top: calc(100% - 14px); }
}

.ls-1 { letter-spacing: 1px; }
.ls-2 { letter-spacing: 2px; }
</style>
