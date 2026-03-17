<script setup>
import { useRouter } from 'vue-router'

const router = useRouter();
// Featured Article Data
const newsItems = [
    {
        category: 'PERFORMANCE',
        title: 'Neural Drive: The Science of Peak Force',
        desc: 'Unlock the connection between your CNS and muscle recruitment. Learn why heavy lifting is as much mental as it is physical.',
        img: '/images/square-img8.jpg',
        action: 'Read the Research'
    },
    {
        category: 'NUTRITION',
        title: 'The Macronutrient Map: Fueling in Nairobi',
        desc: 'Operating at 1,795m elevation changes your metabolic needs. Discover the local superfoods fueling our elite members.',
        img: '/images/news-nutrition.jpg',
        action: 'View the Guide'
    },
    {
        category: 'RECOVERY',
        title: 'Chasing Zero: The Art of Total Recovery',
        desc: 'Training is only half the battle. Explore our infrared and mobility protocols designed for the high-stress professional.',
        img: '/images/recovery-square10.jpg',
        action: 'Explore Protocols'
    }
];

</script>

<template>
    <!-- Hero Section -->
    <v-container fluid class="pa-0">
        <v-row no-gutters>
            <v-carousel height="90dvh" hide-delimiters :show-arrows="false" cycle interval="6000">
                <v-carousel-item
                    v-for="(img, i) in ['/images/img1-landscape.jpg', '/images/img2-landscape.jpg', '/images/img6-landscape.jpg']"
                    :key="i" :src="img" cover>
                </v-carousel-item>
            </v-carousel>
        </v-row>
    </v-container>

    <!-- About Us Section -->
    <v-sheet class="bg-grey-darken-4 py-16">
        <v-container>
            <v-row align="center">
                <v-col cols="12" md="7" class="text-center text-md-left px-6">
                    <v-chip color="#ee6909" variant="tonal" rounded="pill"
                        class="font-weight-black mb-6 px-4 text-uppercase border-white border-opacity-10">
                        ABOUT US
                    </v-chip>
                    <h2 class="text-h5 mb-8 font-weight-black text-uppercase text-white">
                        Elite Training. <span class="text-primary">Unlimited Access.</span>
                    </h2>
                    <p class="text-h6 mb-8 text-grey-lighten-1 font-weight-regular"
                        style="line-height: 1.6; max-width: 800px;">
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

                    <v-btn variant="outlined" rounded="lg" size="large" color="#ee6909"
                        @click="router.push('/gymlocations')">EXPLORE LOCATIONS</v-btn>
                </v-col>
                <v-col cols="12" md="5" class="px-6 mt-12 mt-md-0">
                    <v-img src="/images/aboutus-square.jpg" cover rounded="xl" elevation="12"
                        class="border border-white border-opacity-10"></v-img>
                </v-col>
            </v-row>
        </v-container>
    </v-sheet>

    <!-- Latest News Section -->
    <v-sheet class="bg-black py-8">
        <v-container fluid class="px-md-8">
            <v-row justify="space-between" class="mb-4 px-4">
                <v-col cols="12">
                    <v-chip color="#ee6909" variant="tonal" class="font-weight-black mb-4">LATEST NEWS</v-chip>
                    <h3 class="text-h6 font-weight-black text-white">INSIGHTS</h3>
                </v-col>
            </v-row>

            <v-row>
                <v-col v-for="(news, i) in newsItems" :key="i" cols="12" md="4" class="d-flex">
                    <v-card theme="dark" rounded="lg" hover
                        class="flex-grow-1 d-flex flex-column border border-white border-opacity-10 bg-grey-darken-4">
                        <v-img :src="news.img" cover height="350">
                            <v-chip size="small" color="#ee6909" class="ma-4 font-weight-black" variant="flat">{{
                                news.category }}</v-chip>
                        </v-img>
                        <v-card-item class="pa-6">
                            <v-card-title class="text-h6 font-weight-black text-uppercase mb-3"
                                style="height: 4.5rem; overflow: hidden;">
                                {{ news.title }}
                            </v-card-title>

                            <v-card-text class="pa-0 text-grey-lighten-1 text-body-2">
                                {{ news.desc }}
                            </v-card-text>
                        </v-card-item>
                        <v-spacer></v-spacer>
                        <v-card-actions class="pa-6 pt-0">
                            <v-btn variant="outlined"  rounded="lg" color="#ee6909">{{
                                news.action }}</v-btn>
                        </v-card-actions>
                    </v-card>
                </v-col>
            </v-row>
        </v-container>
    </v-sheet>

    <!-- Reviews Section -->
    <v-sheet class="bg-black py-16">
        <v-container fluid class="px-md-16">
            <v-row justify="center" class="mb-10 text-center">
                <v-chip color="#ee6909" variant="tonal" rounded="pill"
                    class="font-weight-black mb-4 px-4 text-uppercase border-white border-opacity-10">
                    CLIENT REVIEWS
                </v-chip>

            </v-row>
            <v-row justify="center">
                <v-col v-for="(review, i) in [
                    { text: 'I finally found my sanctuary at ZenCore Wellness Studio. My mobility has never been better!', author: 'Maria W.', location: 'Lavington Member', rating: 4 },
                    { text: 'Iron Forge Fitness in Westlands is the place to be. Knowledgeable trainers and top-tier equipment.', author: 'David K.', location: 'Westlands Member', rating: 5 },
                    { text: 'The energy at Pulse Performance Center is unmatched. A perfect 2026 fitness hub!', author: 'Sheila A.', location: 'Kilimani Member', rating: 4.5 }
                ]" :key="i" cols="12" md="4" class="d-flex">
                    <v-card theme="dark" rounded="xl" block class="flex-grow-1 d-flex flex-column pa-6 border"
                        style="border-color: rgba(255, 255, 255, 0.05) !important;">
                        <v-icon color="#ee6909" icon="mdi-format-quote-close" size="48" class="mb-4"
                            style="opacity: 0.2;"></v-icon>
                        <v-card-text class="pa-0 text-body-1 text-grey-lighten-2 font-italic mb-6">
                            "{{ review.text }}"
                        </v-card-text>
                        <v-spacer></v-spacer>
                        <div>
                            <div class="text-subtitle-1 font-weight-black text-white">{{ review.author }}</div>
                            <div class="text-caption text-grey-lighten-1 mb-3">{{ review.location }}</div>
                            <v-rating :model-value="review.rating" readonly dense active-color="#ee6909"
                                color="grey-darken-1" half-increments size="small"></v-rating>
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
                    <div class="text-h6 font-weight-black text-uppercase mb-4">Get in touch
                    </div>
                    <div class="text-body-1 mb-8 text-grey-lighten-1">0712 345 678 | macfitness@gmail.com</div>

                    <v-divider class="mb-8" style="opacity: 0.1;"></v-divider>

                    <div class="text-subtitle-1 font-weight-black text-uppercase mb-2">Opening Hours:</div>
                    <div class="text-body-2 mb-8 text-grey-lighten-2">Mon-Fri: 5.00am - 9.00pm | Sat: 5.30am - 6.00pm |
                        Sun: 8.00am - 2.00pm </div>

                    <div class="text-caption text-grey-darken-1">&copy;2026 MacFitness. Designed by AA</div>
                </v-col>
            </v-row>
        </v-container>
    </v-footer>
</template>

<style scoped>
.text-clamp-2 {
    display: -webkit-box;
    -webkit-box-orient: vertical;
    -webkit-line-clamp: 2;
    line-clamp: 2;
    /* Standard property */
    overflow: hidden;
    white-space: normal;
}

.text-clamp-3 {
    display: -webkit-box;
    -webkit-box-orient: vertical;
    -webkit-line-clamp: 3;
    line-clamp: 3;
    /* Standard property */
    overflow: hidden;
    white-space: normal;
}

/* Optional: Combine with min-height for perfect alignment */
.title-height {
    min-height: 3.5rem;
    line-height: 1.2;
}
</style>
