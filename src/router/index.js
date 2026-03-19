import { createRouter, createWebHistory } from "vue-router";
import LandingPage from "@/components/LandingPage.vue";
import Login from "@/components/Login.vue";
import SignUp from "@/components/SignUp.vue";
import HomePage from "@/components/HomePage.vue";
import Profile from "@/components/Profile.vue";
import Bundles from "@/components/Bundles.vue";
import GymLocations from "@/components/GymLocations.vue";
import Admin from "@/components/Admin.vue";
import { useAuth } from "@/services/auth";

const router = createRouter({
    history: createWebHistory(import.meta.env.BASE_URL),
    routes: [
        {
            path:'/',
            name: 'landingpage',
            component: LandingPage,
        },
        {
            path:'/login',
            name: 'login',
            component: Login,
        },
        {
            path:'/signup',
            name: 'signup',
            component: SignUp,
        },
        {
            path:'/homepage',
            name: 'homepage',
            component: HomePage,
        },
        {
            path:'/profile',
            name: 'profile',
            component: Profile,
        },
        {
            path:'/bundles',
            name: 'bundles',
            component: Bundles,
        },
        {
            path:'/gymlocations',
            name: 'gymlocations',
            component: GymLocations,
        },
        {
            path:'/admin',
            name: 'admin',
            component: Admin,
        },
        // Catch-all route to redirect any typos back to Landing
        {
            path: '/:pathMatch(.*)*',
            redirect: '/'
        }
    ],
});

router.beforeEach((to, from, next) => {
    const { isAdmin, isAuthenticated } = useAuth();

    // If Admin is logged in and tries to go to standard Member Homepage, redirect to Admin Console
    if (to.name === 'homepage' && isAuthenticated.value && isAdmin.value) {
        next({ name: 'admin' });
    } else {
        next();
    }
});

export default router;
