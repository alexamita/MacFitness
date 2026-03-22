<script setup>
import { computed } from 'vue';
import { useAuth } from '@/services/auth';
import { useRouter } from 'vue-router';

const { user, isAdmin } = useAuth();
const router = useRouter();

// Determine Role Display
const roleInfo = computed(() => {
    if (!user.value) return { label: 'GUEST', color: 'grey' };
    
    const roleId = user.value.role_id;
    // 1: Super Admin, 2: Admin -> Administrator
    if (roleId === 1 || roleId === 2) return { label: 'ADMINISTRATOR', color: 'red-darken-1' };
    // 3: Trainer/Staff -> Gym Staff
    if (roleId === 3) return { label: 'GYM STAFF', color: 'blue-darken-1' };
    // Default: Member
    return { label: 'GYM MEMBER', color: '#ee6909' };
});

const isStaffOrAdmin = computed(() => user.value?.role_id >= 1 && user.value?.role_id <= 3);
</script>

<template>
    <v-container fluid class="fill-height bg-grey-lighten-4 pa-0">
        <v-row justify="center" align="center" no-gutters class="fill-height">
            <v-col cols="12" sm="8" md="6" lg="4" class="pa-4">
                <v-card v-if="user" class="bg-white pa-8 mx-auto position-relative overflow-hidden" elevation="1" rounded="xl" border="thin opacity-10" max-width="480">
                    <!-- Subtle Role Indicator -->
                    <div class="position-absolute top-0 right-0 ma-4">
                        <v-chip :color="roleInfo.color" variant="tonal" size="x-small" class="font-weight-black px-3">
                            {{ roleInfo.label }}
                        </v-chip>
                    </div>

                    <!-- Profile Header -->
                    <div class="text-center mb-8 pt-4">
                        <v-avatar size="100" color="grey-lighten-4" class="mb-4 border">
                            <span class="text-h3 font-weight-black text-grey-darken-3">
                                {{ user.name ? user.name.charAt(0).toUpperCase() : 'U' }}
                            </span>
                        </v-avatar>
                        <h1 class="text-h5 font-weight-black text-black text-uppercase tracking-tighter">{{ user.name }}</h1>
                        <p class="text-caption text-grey-darken-1 font-weight-bold">{{ user.email }}</p>
                    </div>

                    <v-divider class="mb-8"></v-divider>

                    <!-- User Details -->
                    <v-list class="bg-transparent pa-0">
                        <!-- Contact Info -->
                        <v-list-item v-if="user.phoneNumber || user.phone" density="compact" class="px-0 py-2">
                            <template v-slot:prepend>
                                <v-icon size="20" color="grey-darken-1" class="mr-4">mdi-phone-outline</v-icon>
                            </template>
                            <v-list-item-title class="text-caption text-grey-darken-1 font-weight-bold text-uppercase mb-1">Contact</v-list-item-title>
                            <v-list-item-subtitle class="text-body-2 text-black font-weight-medium">{{ user.phone || user.phoneNumber }}</v-list-item-subtitle>
                        </v-list-item>

                        <!-- Member Hub (Only for Members) -->
                        <v-list-item v-if="!isStaffOrAdmin" density="compact" class="px-0 py-2">
                            <template v-slot:prepend>
                                <v-icon size="20" color="grey-darken-1" class="mr-4">mdi-map-marker-outline</v-icon>
                            </template>
                            <v-list-item-title class="text-caption text-grey-darken-1 font-weight-bold text-uppercase mb-1">Preferred Hub</v-list-item-title>
                            <v-list-item-subtitle class="text-body-2 text-black font-weight-medium">
                                {{ user.gym_id || 'Not Assigned' }}
                            </v-list-item-subtitle>
                        </v-list-item>
                    </v-list>

                    <!-- Membership Footer (Only for Members) -->
                    <div v-if="!isStaffOrAdmin" class="mt-8 pa-5 bg-grey-lighten-5 rounded-lg border border-dashed">
                        <div class="d-flex justify-space-between align-center">
                            <div>
                                <div class="text-caption text-grey-darken-1 font-weight-black text-uppercase leading-none mb-1">Membership Plan</div>
                                <div class="text-subtitle-2 font-weight-black text-black">
                                    {{ user.subscription?.name || 'Standard Tier' }}
                                </div>
                            </div>
                            <v-btn size="small" color="#ee6909" variant="flat" rounded="lg" class="font-weight-black px-4" @click="router.push('/bundles')">
                                {{ user.subscription ? 'MANAGE' : 'UPGRADE' }}
                            </v-btn>
                        </div>
                    </div>

                    <!-- Staff/Admin Footer -->
                    <div v-else class="mt-8">
                        <v-btn block color="black" variant="flat" rounded="lg" class="font-weight-black py-6 elevation-2" @click="router.push('/admin')">
                            ACCESS ADMIN DASHBOARD
                        </v-btn>
                    </div>
                </v-card>

                <!-- Fallback for no user -->
                <v-card v-else border class="bg-white pa-12 text-center" rounded="xl" elevation="1">
                    <v-icon size="64" color="grey-lighten-2" class="mb-4">mdi-account-lock-outline</v-icon>
                    <div class="text-h6 font-weight-black text-grey-darken-1">Access Restrictred</div>
                    <p class="text-caption text-grey-darken-1 mb-6">Please sign in to view your profile details.</p>
                    <v-btn color="#ee6909" variant="flat" rounded="lg" class="font-weight-black px-8" @click="router.push('/login')">LOG IN</v-btn>
                </v-card>
            </v-col>
        </v-row>
    </v-container>
</template>

<style scoped>
.tracking-tighter {
    letter-spacing: -0.05em;
}
.leading-none {
    line-height: 1;
}
</style>
