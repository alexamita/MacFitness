<script setup>
</script>

<template>
    <!-- Hero Section -->
    <v-container fluid class="pa-0">
        <v-row no-gutters>
            <v-carousel height="100dvh" hide-delimiters :show-arrows="false" cycle interval="5000">
                <v-carousel-item src="/images/img1-landscape.jpg" cover></v-carousel-item>
                <v-carousel-item src="/images/img2-landscape.jpg" cover></v-carousel-item>
                <v-carousel-item src="/images/img6-landscape.jpg" cover></v-carousel-item>
            </v-carousel>
        </v-row>
    </v-container>

    <!-- About Us Section -->
    <v-sheet class="bg-grey-darken-4 py-16">
        <v-container>
            <v-row align="center">
                <v-col cols="12" md="7" class="text-center text-md-left px-6">
                    <v-chip color="#ee6909" variant="tonal" rounded="pill" class="font-weight-black mb-6 px-4 text-uppercase border-white border-opacity-10">
                        ABOUT US
                    </v-chip>
                    <h2 class="text-h3 mb-8 font-weight-black text-uppercase text-white" style="letter-spacing: 2px;">
                        Elite Training. <span class="text-grey-darken-1">Unlimited Access.</span>
                    </h2>
                    <p class="text-h6 mb-8 text-grey-lighten-1 font-weight-regular" style="line-height: 1.6; max-width: 800px;">
                        We believe fitness is the ultimate investment. Our mission is to provide a seamless fusion of
                        high-performance training and recovery across our growing network of locations.
                    </p>

                    <v-row class="text-left mb-10">
                        <v-col v-for="(item, i) in [
                            { title: 'Integrated Wellness', desc: 'Training, nutrition, and recovery under one roof.' },
                            { title: 'Bespoke Programming', desc: 'Workouts tailored to your specific biometrics.' },
                            { title: 'Unified Network', desc: 'One membership grants you elite access to all clubs.' }
                        ]" :key="i" cols="12" sm="6">
                            <div class="d-flex align-top">
                                <v-icon color="#ee6909" icon="mdi-checkbox-marked-circle" class="me-3 mt-1"></v-icon>
                                <div>
                                    <div class="text-subtitle-1 font-weight-bold text-white mb-1">{{ item.title }}</div>
                                    <div class="text-body-2 text-grey-lighten-2">{{ item.desc }}</div>
                                </div>
                            </div>
                        </v-col>
                    </v-row>

                    <v-btn variant="outlined" rounded="lg" class="px-12 font-weight-black text-uppercase border-white border-opacity-25 text-white" size="large" color="#ee6909">
                        LEARN MORE
                    </v-btn>
                </v-col>
                <v-col cols="12" md="5" class="px-6 mt-12 mt-md-0">
                    <v-img src="/images/aboutus-square.jpg" cover rounded="xl" elevation="12" class="border" style="border-color: rgba(255,255,255,0.1) !important;"></v-img>
                </v-col>
            </v-row>
        </v-container>
    </v-sheet>

    <!-- Latest News Section -->
    <v-sheet class="bg-black py-16">
        <v-container fluid class="px-md-16">
            <v-row justify="center" class="mb-10">
                <v-chip color="#ee6909" variant="tonal" rounded="pill" class="font-weight-black px-4 text-uppercase border-white border-opacity-10">
                    LATEST NEWS
                </v-chip>
            </v-row>

            <v-row>
                <v-col v-for="(news, i) in [
                    { title: 'The Karura Reset: Walking Culture', desc: 'Why Nairobians are returning to nature. Join our guided weekend trail walks designed for active recovery.', img: '/images/img1-landscape.jpg', action: 'Join the Next Walk' },
                    { title: 'Sukuma Wiki & Power: Local Fuel', desc: 'A deep dive into why Kenyas traditional greens and legumes are the ultimate recovery resource for strength training.', img: '/images/news-nutrition.jpg', action: 'Get the Diet Guide' },
                    { title: 'Nairobi Altitude IQ: Urban Prep', desc: 'How our trainers use Kenyas unique high-altitude climate to boost your cardiovascular performance in the CBD.', img: '/images/aboutus-square.jpg', action: 'Explore Training' }
                ]" :key="i" cols="12" md="4" class="d-flex">
                        <v-card theme="dark" rounded="xl"  hover block class="flex-grow-1 d-flex flex-column overflow-hidden border" style="border-color: rgba(255, 255, 255, 0.05) !important; background-color: rgba(255,255,255,0.02) !important; min-height: 540px;">
                            <v-img :src="news.img" cover height="320"></v-img>
                        <v-card-item class="pa-6">
                            <v-card-title class="text-h6 font-weight-black text-uppercase mb-3" style="line-height: 1.3; white-space: normal;">
                                {{ news.title }}
                            </v-card-title>
                            <v-card-text class="pa-0 text-grey-lighten-1 text-body-2" style="line-height: 1.6;">
                                {{ news.desc }}
                            </v-card-text>
                        </v-card-item>
                        <v-spacer></v-spacer>
                        <v-card-actions class="pa-6 pt-0 justify-center">
                            <v-btn variant="outlined" size="small" height="44" rounded="lg" class="font-weight-medium text-uppercase border-orange-lighten-1 border-opacity-25 text-white px-8" color="#ee6909">
                                {{ news.action }}
                            </v-btn>
                        </v-card-actions>
                    </v-card>
                </v-col>
            </v-row>
        </v-container>
    </v-sheet>

    <!-- Reviews Section -->
    <v-sheet class="bg-grey-darken-4 py-16">
        <v-container>
            <v-row justify="center" class="mb-12">
                <v-chip color="#ee6909" variant="tonal" rounded="pill" class="font-weight-black px-4 text-uppercase border-white border-opacity-10">
                    CLIENT REVIEWS
                </v-chip>
            </v-row>
            <v-row justify="center">
                <v-col v-for="(review, i) in [
                    { text: 'I finally found my sanctuary at ZenCore Wellness Studio. My mobility has never been better!', author: 'Maria W.', location: 'Lavington Member', rating: 4 },
                    { text: 'Iron Forge Fitness in Westlands is the place to be. Knowledgeable trainers and top-tier equipment.', author: 'David K.', location: 'Westlands Member', rating: 5 },
                    { text: 'The energy at Pulse Performance Center is unmatched. A perfect 2026 fitness hub!', author: 'Sheila A.', location: 'Kilimani Member', rating: 4.5 }
                ]" :key="i" cols="12" md="4" class="d-flex">
                    <v-card theme="dark" rounded="xl" block class="flex-grow-1 d-flex flex-column pa-6 border" style="border-color: rgba(255, 255, 255, 0.05) !important;">
                        <v-icon color="#ee6909" icon="mdi-format-quote-close" size="48" class="mb-4" style="opacity: 0.2;"></v-icon>
                        <v-card-text class="pa-0 text-body-1 text-grey-lighten-2 italic mb-6">
                            "{{ review.text }}"
                        </v-card-text>
                        <v-spacer></v-spacer>
                        <div>
                            <div class="text-subtitle-1 font-weight-black text-white">{{ review.author }}</div>
                            <div class="text-caption text-grey-lighten-1 mb-3">{{ review.location }}</div>
                            <v-rating :model-value="review.rating" readonly dense active-color="#ee6909" color="grey-darken-1" half-increments size="small"></v-rating>
                        </div>
                    </v-card>
                </v-col>
            </v-row>
        </v-container>
    </v-sheet>

    <!-- Footer -->
    <v-footer class="bg-black px-10 py-12 border">
        <v-container>
            <v-row justify="center">
                <v-col cols="12" md="8" class="text-center">
                    <div class="text-h5 font-weight-black text-uppercase mb-4" style="letter-spacing: 2px;">Get in touch</div>
                    <div class="text-body-1 mb-8 text-grey-lighten-1">0712 345 678 | macfitness@gmail.com</div>

                    <v-divider class="mb-8" style="opacity: 0.1;"></v-divider>

                    <div class="text-subtitle-1 font-weight-black text-uppercase mb-2">Opening Hours:</div>
                    <div class="text-body-2 mb-8 text-grey-lighten-2">Mon-Fri: 5.00am - 9.00pm | Sat: 5.30am - 6.00pm | Sun: 8.00am - 2.00pm </div>

                    <div class="text-caption text-grey-darken-1">&copy;2026 MacFitness. Designed by AA</div>
                </v-col>
            </v-row>
        </v-container>
    </v-footer>
</template>

<style scoped>
</style>
