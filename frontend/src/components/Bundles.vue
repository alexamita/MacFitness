<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { useAuth } from '../services/auth'

const router = useRouter();
const { isAuthenticated} = useAuth();

const showBundleDialog = ref(false)
const selectedBundle = ref(null)
const selectedPrice = ref(null)


function showBundle(name, price) {
    if (isAuthenticated.value) {
        selectedBundle.value = name
        selectedPrice.value = price
        showBundleDialog.value = true
    } else {
        router.push('/login')
    }
}

function subscribe() {
    try {
        // Try to get existing data or initialize a clean object
        const rawData = localStorage.getItem('userSignUpData');
        const userDetails = rawData ? JSON.parse(rawData) : { email: user.value?.email };

        userDetails.subscription = {
            name: selectedBundle.value,
            price: selectedPrice.value,
            date: new Date().toISOString()
        }

        // Save updated data
        localStorage.setItem('userSignUpData', JSON.stringify(userDetails))

        // UI Feedback
        showBundleDialog.value = false

        // Optional: Navigate to a "Success" or "Payment" page
        // router.push('/checkout');

    } catch (err) {
        console.error("Subscription storage failed", err);
    }
}
</script>

<template>
    <!-- Pricing Section -->
    <v-sheet class="bg-grey-lighten-5 py-16">
        <v-container>
            <v-row justify="center" class="mb-4">
                <v-chip color="#ee6909" variant="tonal" rounded="pill" class="font-weight-black px-6 text-uppercase" size="small">
                    Bundles & Pricing
                </v-chip>
            </v-row>

            <v-row justify="center" class="mb-12">
                <h1 class="text-h4 font-weight-black text-black text-center">Ready to <span style="color: #ee6909">start?</span></h1>
            </v-row>

            <v-row justify="center" align="stretch">
                <v-col cols="12" sm="6" md="3" v-for="(bundle, i) in [
                    { title: 'Daily Pass', price: '500', icon: 'mdi-hours-12' },
                    { title: 'One Month Pass', price: '5500', icon: 'mdi-numeric-1-box' },
                    { title: 'Three Months Pass', price: '15000', icon: 'mdi-numeric-3-box' },
                    { title: 'Six Months Pass', price: '25000', icon: 'mdi-numeric-6-box' }
                ]" :key="i" class="d-flex">
                    <v-card
                        rounded="xl"
                        elevation="2"
                        hover
                        border="thin opacity-25"
                        class="text-center flex-grow-1 d-flex flex-column align-center justify-center py-10 px-6 bg-white"
                        style="border-color: rgba(238, 105, 9, 0.15) !important;"
                        @click="showBundle(bundle.title, bundle.price)">
                        <v-avatar color="#ee6909" variant="tonal" size="64" class="mb-6">
                            <v-icon color="#ee6909" :icon="bundle.icon" size="32"></v-icon>
                        </v-avatar>
                        <div class="text-subtitle-1 font-weight-black text-uppercase text-black mb-2">{{ bundle.title }}</div>
                        <div class="text-h4 font-weight-black text-black"><span class="text-grey-darken-1 font-weight-bold text-caption me-1">KES</span><span style="color: #ee6909">{{ bundle.price }}</span></div>
                    </v-card>
                </v-col>
            </v-row>

            <v-row class="mt-12" justify="center">
                <v-col cols="12">
                    <v-card
                        rounded="xl"
                        elevation="6"
                        hover
                        class="text-center d-flex flex-column align-center justify-center py-16 bg-black text-white"
                        style="border: 2px solid #ee6909 !important;"
                        @click="showBundle('12 Month Elite Pass', '45000')">
                        <v-chip color="#ee6909" variant="tonal" rounded="pill" class="font-weight-black mb-6 px-8 text-uppercase">
                            MOST POPULAR
                        </v-chip>
                        <v-avatar color="#ee6909" variant="tonal" size="80" class="mb-6">
                            <v-icon color="#ee6909" icon="mdi-calendar-star" size="48"></v-icon>
                        </v-avatar>
                        <div class="text-h4 text-white font-weight-black text-uppercase mb-2">12 Month Elite Pass</div>
                        <div class="text-h2 font-weight-black text-white"><span class="text-grey-lighten-1 font-weight-medium text-h5 me-3">KES</span><span style="color: #ee6909">45,000</span></div>
                        <div class="text-body-1 mt-6 text-grey font-weight-medium text-uppercase">Billed Annually • Unlimited Access</div>
                    </v-card>
                </v-col>
            </v-row>
        </v-container>
    </v-sheet>

    <v-divider></v-divider>

    <!-- What is included -->
    <v-sheet class="bg-white py-16">
        <v-container fluid style="max-width: 1400px;">
            <v-row justify="center" class="mb-10">
                <v-chip color="#ee6909" variant="tonal" rounded="pill" class="font-weight-black px-6 text-uppercase">
                    What is included?
                </v-chip>
            </v-row>

            <v-row justify="center" align="stretch">
                <v-col v-for="(item, i) in [
                    { title: 'Multiple Locations', desc: 'Access any MacFitness gym across our premium network.', icon: 'mdi-map-marker-multiple-outline' },
                    { title: 'Premium Equipment', desc: 'State-of-the-art equipment maintained to elite standards.', icon: 'mdi-dumbbell' },
                    { title: '24/7 Gym Access', desc: 'Schedules that work for you, protected by elite security.', icon: 'mdi-clock-check-outline' },
                    { title: 'Free Parking & Lockers', desc: 'Complimentary monitored parking and private storage.', icon: 'mdi-shield-car' },
                    { title: 'Steam & Showers', desc: 'Luxury steam rooms and high-pressure recovery showers.', icon: 'mdi-weather-fog' },
                    { title: 'Dedicated Zones', desc: 'Private, purpose-built zones for focused performance.', icon: 'mdi-account-group-outline' }
                ]" :key="i" cols="12" sm="6" md="3" class="mb-6 d-flex">
                    <v-card
                        rounded="xl"
                        elevation="0"
                        border="thin opacity-25"
                        class="text-center flex-grow-1 d-flex flex-column align-center justify-center py-8 px-6 bg-grey-lighten-5"
                        style="border-color: rgba(238, 105, 9, 0.12) !important;">
                        <v-avatar color="#ee6909" variant="tonal" size="48" class="mb-4">
                            <v-icon color="#ee6909" :icon="item.icon" size="24"></v-icon>
                        </v-avatar>
                        <div class="text-subtitle-1 font-weight-black text-uppercase text-black mb-2">{{ item.title }}</div>
                        <div class="text-body-2 text-grey-darken-2" style="line-height: 1.5;">{{ item.desc }}</div>
                    </v-card>
                </v-col>
            </v-row>
        </v-container>
    </v-sheet>

    <v-divider></v-divider>

    <!-- How to Join Section -->
    <v-sheet class="bg-grey-lighten-5 py-20">
        <v-container>
            <v-row justify="center" class="mt-12 mb-12">
                <v-chip color="#ee6909" variant="tonal" rounded="pill" class="font-weight-black px-6 text-uppercase">
                    HOW TO JOIN
                </v-chip>
            </v-row>
            <v-row justify="center">
                <v-col v-for="(step, i) in [
                    { id: '01', title: 'Select Your Tier', desc: 'Explore our range of elite membership bundles and choose the plan that aligns with your lifestyle.' },
                    { id: '02', title: 'Submit Your Profile', desc: 'Fill out our streamlined digital registration form or visit any of our locations for consultation.' },
                    { id: '03', title: 'Activate Your Access', desc: 'Finalize your membership with a secure, hassle-free payment at the front desk or via our portal.' },
                    { id: '04', title: 'Elevate Your Performance', desc: 'Receive your digital member ID and begin training immediately. Your journey starts now.' }
                ]" :key="i" cols="12" sm="6" md="3" class="text-center mb-8 px-8">
                    <div class="text-h6 font-weight-black mb-6" style="color: #ee6909; opacity: 0.2;">{{ step.id }}</div>
                    <div class="text-h6 font-weight-black text-black mb-3 text-uppercase" style="min-height: 64px;">{{ step.title }}</div>
                    <p class="text-grey-darken-2 text-body-1" style="line-height: 1.2;">{{ step.desc }}</p>
                </v-col>
            </v-row>
        </v-container>
    </v-sheet>

    <!-- Dialog -->
    <v-dialog v-model="showBundleDialog" max-width="480">
        <v-card rounded="xl" border hover class="bg-white overflow-hidden" style="border-color: rgba(238, 105, 9, 0.15) !important;">
            <v-card-item class="bg-grey-lighten-5 pa-6 border-b">
                <template v-slot:prepend>
                    <v-avatar color="#ee6909" variant="tonal" size="48" class="me-4">
                        <v-icon color="#ee6909" size="24">mdi-shield-check-outline</v-icon>
                    </v-avatar>
                </template>
                <div>
                    <div class="text-overline font-weight-black text-grey-darken-1 mb-n1" style="letter-spacing: 2px;">SECURE CHECKOUT</div>
                    <v-card-title class="text-h6 font-weight-black text-black pa-0">CONFIRM SUBSCRIPTION</v-card-title>
                </div>
            </v-card-item>

            <v-card-text class="pa-8">
                <div class="mb-8">
                    <div class="text-subtitle-2 font-weight-black text-grey-darken-1 text-uppercase mb-2" style="letter-spacing: 1px;">Selected Plan</div>
                    <div class="text-h5 font-weight-black text-black mb-1">{{ selectedBundle }}</div>
                    <div class="text-h4 font-weight-black" style="color: #ee6909">
                        <span class="text-subtitle-1 me-1 font-weight-bold">KES</span>{{ selectedPrice }}
                    </div>
                </div>

                <v-divider class="mb-6" style="opacity: 0.1;"></v-divider>

                <div class="text-subtitle-2 font-weight-black text-grey-darken-1 text-uppercase mb-4" style="letter-spacing: 1px;">Included With This Pass</div>
                <v-list bg-transparent density="compact" class="pa-0">
                    <v-list-item v-for="(benefit, i) in [
                        'Unlimited Gym Access',
                        'Premium Equipment Access',
                        'Complimentary Lockers',
                        'High-speed Guest WiFi'
                    ]" :key="i" class="px-0 py-1" min-height="auto">
                        <template v-slot:prepend>
                            <v-icon color="#ee6909" size="18" class="me-3">mdi-check-circle-outline</v-icon>
                        </template>
                        <v-list-item-title class="text-body-2 text-grey-darken-3 font-weight-medium">{{ benefit }}</v-list-item-title>
                    </v-list-item>
                </v-list>
            </v-card-text>

            <v-card-actions class="px-8 pb-8 pt-0">
                <v-btn
                    variant="text"
                    color="grey-darken-1"
                    @click="showBundleDialog = false"
                    class="font-weight-black"
                    size="large">
                    BACK
                </v-btn>
                <v-spacer></v-spacer>
                <v-btn
                    color="#ee6909"
                    variant="flat"
                    @click="subscribe()"
                    class="px-10 font-weight-black text-black"
                    height="54"
                    rounded="lg"
                    size="large">
                    SUBSCRIBE
                </v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>

<style scoped>
</style>
