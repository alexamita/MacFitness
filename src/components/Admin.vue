<script setup>
import { ref, onMounted, computed, watch } from 'vue'
import api from '../services/api';

/**
 * GLOBAL UI STATE
 * Control the "feel" and visibility of the interface.
 */
const loading = ref(false)
const error = ref('')
const tab = ref(0)

/**
 * DATA RECEPTACLES
 * These constants hold data coming from the DB.
 * Initialized as empty arrays [] to prevent ".length" or ".map" errors before data arrives.
 */
const users = ref([])      // Array of User objects
const roles = ref([])      // Array of Role objects:
const equipment = ref([])  // Array of Equipment
const gyms = ref([])       // Array of Gym locations

// Search and Filtering
const searchQuery = ref('');

// Pagination for Equipment
const currentPage = ref(1);
const itemsPerPage = ref(10);
const itemsPerPageOptions = [5, 10, 20, 50];

const filteredUsers = computed(() => {
    if (!searchQuery.value) return users.value;
    const q = searchQuery.value.toLowerCase();
    return users.value.filter(u =>
        (u.name && u.name.toLowerCase().includes(q)) ||
        (u.email && u.email.toLowerCase().includes(q)) ||
        (u.phoneNumber && u.phoneNumber.includes(q))
    );
});

const filteredRoles = computed(() => {
    if (!searchQuery.value) return roles.value;
    const q = searchQuery.value.toLowerCase();
    return roles.value.filter(r =>
        (r.name && r.name.toLowerCase().includes(q)) ||
        (r.description && r.description.toLowerCase().includes(q))
    );
});

const filteredEquipment = computed(() => {
    if (!searchQuery.value) return equipment.value;
    const q = searchQuery.value.toLowerCase();
    return equipment.value.filter(e =>
        (e.name && e.name.toLowerCase().includes(q)) ||
        (e.assetCode && e.assetCode.toLowerCase().includes(q)) ||
        (e.serial_no && e.serial_no.toLowerCase().includes(q))
    );
});

const filteredGyms = computed(() => {
    if (!searchQuery.value) return gyms.value;
    const q = searchQuery.value.toLowerCase();
    return gyms.value.filter(g =>
        (g.name && g.name.toLowerCase().includes(q)) ||
        (g.location && g.location.toLowerCase().includes(q))
    );
});

const paginatedEquipment = computed(() => {
    const start = (currentPage.value - 1) * itemsPerPage.value;
    const end = start + itemsPerPage.value;
    return filteredEquipment.value.slice(start, end);
});

const pageCount = computed(() => {
    return Math.ceil(filteredEquipment.value.length / itemsPerPage.value);
});

// Overview Stats
const totalUsersCount = computed(() => users.value.length);
const totalGymsCount = computed(() => gyms.value.length);
const totalEquipmentCount = computed(() => equipment.value.length);

watch(searchQuery, () => {
    currentPage.value = 1;
});

/**
 * DIALOG CONTROLS (Modals)
 * Boolean triggers for showing/hiding pop-up forms.
 * Usage: <v-dialog v-model="showAddUserDialog">
 */
const showAddUserDialog = ref(false)
const showAddRoleDialog = ref(false)
const showAddEquipmentDialog = ref(false)
const showAddGymDialog = ref(false)
const showDeleteDialog = ref(false);
const deleteConfig = ref({
    title: '',
    message: '',
    confirmText: 'DELETE',
    color: 'error',
    action: null
});

/**
 * MODE TRACKING
 * Crucial for distinguishing between "Creating New" and "Updating Existing".
 */
const isEditing = ref(false) // isEditing: When true, your "Save" button will trigger a PUT/UPDATE instead of a POST.
const currentId = ref(null) // currentId: Stores the ID of the record being edited so the API knows which row to update.

/**
 * FORM MODEL REFS
 * These are linked via 'v-model' to your input fields.
 * They capture what the user types before it is sent to the backend.
 */

// User Form Fields
const firstName = ref(null);
const lastName = ref(null);
const email = ref(null);
const phoneNumber = ref(null);
const dateOfBirth = ref(null);
const gender = ref(null);
const selectedGym = ref(null); // Stores the ID of the selected gym from a dropdown
const userRole = ref(null);    // Stores the ID of the selected role

// Equipment Form Fields
const equipmentName = ref(null);
const usage = ref(null);
const serialNumber = ref(null);
const assetCode = ref(null);
const equipmentValue = ref(null);
const status = ref(null);
const category_id = ref(null);

// Role Form Fields
const roleName = ref(null);
const roleDescription = ref(null);

// Gym Form Fields
const gymName = ref(null);
const gymlocation = ref(null);
const primaryContact = ref(null);
const gymDescription = ref(null);

/**
 * AUTHENTICATION HELPER
 * Encapsulates the Bearer Token logic.
 * We call this inside every function to ensure we have the latest token from storage.
 */
const getAuthHeader = () => {
    const token = localStorage.getItem('authToken');
    return { headers: { 'Authorization': `Bearer ${token}` } };
};

/**
 * GLOBAL HELPERS
 */

// Reset all form fields to null and close all modals
function close() {
    showAddEquipmentDialog.value = false
    showAddUserDialog.value = false
    showAddRoleDialog.value = false
    showAddGymDialog.value = false
    isEditing.value = false
    currentId.value = null
    error.value = ''

    // Resetting refs efficiently
    const refsToReset = [
        firstName, lastName, email, phoneNumber, dateOfBirth, gender,
        selectedGym, userRole, roleName, roleDescription, equipmentName,
        usage, serialNumber, assetCode, equipmentValue, status, gymName,
        gymlocation, primaryContact, gymDescription
    ];
    refsToReset.forEach(r => r.value = null);
}

/**
 * FEATURE LOGIC (Users, Equipment, Roles, Gyms)
 * Each section handles its own Fetching and Saving.
 */

// 1. USER LOGIC
async function fetchUsers() {
    try {
        const response = await api.get('users', getAuthHeader());
        // Optional chaining and default empty array prevent "map" errors in UI
        users.value = response.data?.data || response.data || [];
    } catch (err) {
        error.value = 'Failed to fetch users.';
    }
}

async function addUser() {
    loading.value = true;
    const payload = {
        name: `${firstName.value} ${lastName.value}`,
        email: email.value,
        phoneNumber: phoneNumber.value,
        date_of_birth: dateOfBirth.value,
        gender: gender.value,
        gym_id: selectedGym.value,
        role_id: Number(userRole.value)
    };

    try {
        if (isEditing.value) {
            // EDIT MODE: Using PUT with the ID in the URL
            await api.put(`users/${currentId.value}`, payload, getAuthHeader());
        } else {
            // ADD MODE: Using POST to the base endpoint
            await api.post('users', payload, getAuthHeader());
        }
        await fetchUsers(); // Refresh list after success
        close();
    } catch (err) {
        // Check if the backend sent a specific validation message
        if (err.response && err.response.status === 422) {
            // This will set error.value to "The email has already been taken."
            error.value = err.response.data.message;
        } else {
            error.value = 'An unexpected error occurred. Please try again.';
        }
    } finally {
        loading.value = false;
    }
}

function editUser(data) {
    isEditing.value = true;
    currentId.value = data.id;
    const nameParts = (data.name || '').split(' ');
    firstName.value = nameParts[0];
    lastName.value = nameParts.slice(1).join(' ');
    email.value = data.email;
    phoneNumber.value = data.phoneNumber;
    dateOfBirth.value = data.date_of_birth;
    gender.value = data.gender;
    selectedGym.value = data.gym_id;
    userRole.value = String(data.role_id);
    showAddUserDialog.value = true;
}

// Generic Delete/Action Confirmation
function confirmAction(config) {
    deleteConfig.value = {
        title: config.title || 'Confirm Action',
        message: config.message || 'Are you sure you want to proceed?',
        confirmText: config.confirmText || 'CONFIRM',
        color: config.color || 'error',
        action: config.action
    };
    showDeleteDialog.value = true;
}

async function handleActionExec() {
    if (deleteConfig.value.action) {
        loading.value = true;
        try {
            await deleteConfig.value.action();
            showDeleteDialog.value = false;
        } catch (err) {
            error.value = err.response?.data?.message || 'Action failed.';
        } finally {
            loading.value = false;
        }
    }
}

async function deactivateUser(user) {
    confirmAction({
        title: 'DEACTIVATE USER',
        message: `Are you sure you want to deactivate ${user.name}? They will no longer be able to log in.`,
        confirmText: 'DEACTIVATE',
        color: 'error',
        action: async () => {
            await api.delete(`users/${user.id}`, getAuthHeader());
            await fetchUsers();
        }
    });
}

async function activateUser(user) {
    confirmAction({
        title: 'ACTIVATE USER',
        message: `Restore access for ${user.name}?`,
        confirmText: 'ACTIVATE',
        color: 'success',
        action: async () => {
            await api.post(`users/${user.id}/restore`, {}, getAuthHeader());
            await fetchUsers();
        }
    });
}

async function deleteRole(role) {
    confirmAction({
        title: 'DELETE ROLE',
        message: `Permanently delete the "${role.name}" role? This cannot be undone if users are still assigned.`,
        confirmText: 'DELETE',
        color: 'error',
        action: async () => {
            await api.delete(`deleteRole/${role.id}`, getAuthHeader());
            await fetchRoles();
        }
    });
}

async function deleteEquipment(item) {
    confirmAction({
        title: 'DELETE EQUIPMENT',
        message: `Remove "${item.name}" (${item.assetCode}) from inventory?`,
        confirmText: 'DELETE',
        color: 'error',
        action: async () => {
            await api.delete(`deleteEquipment/${item.id}`, getAuthHeader());
            await fetchEquipment();
        }
    });
}

async function deleteGym(gym) {
    confirmAction({
        title: 'DELETE GYM LOCATION',
        message: `Remove "${gym.name}"? This will affect all associated users and equipment.`,
        confirmText: 'DELETE',
        color: 'error',
        action: async () => {
            await api.delete(`deleteGym/${gym.id}`, getAuthHeader());
            await fetchGyms();
        }
    });
}
// 2. ROLES LOGIC
async function fetchRoles() {
    try {
        const response = await api.get('getRoles', getAuthHeader());
        roles.value = response.data || [];
    } catch (err) {
        error.value = 'Retrieving roles failed';
    }
}

async function addRole() {
    loading.value = true
    const payload = { name: roleName.value, description: roleDescription.value }
    try {
        isEditing.value
            ? await api.post(`updateRole/${currentId.value}`, payload, getAuthHeader)
            : await api.post('saveRole', payload, getAuthHeader);
        close();
        fetchRoles();
    } catch (err) {
        error.value = 'Saving role failed'
    } finally {
        loading.value = false
    }
}

// 3. EQUIPMENT LOGIC
async function fetchEquipment() {
    try {
        const response = await api.get('getEquipment', getAuthHeader());
        equipment.value = response.data || [];
    } catch (err) {
        error.value = 'Retrieving equipment failed';
    }
}

async function addEquipment() {
    loading.value = true;
    const payload = {
        name: equipmentName.value,
        usage: usage.value,
        serial_no: serialNumber.value,
        assetCode: assetCode.value,
        value: Number(equipmentValue.value),
        status: status.value,
    };
    try {
        isEditing.value
            ? await api.put(`saveEquipment/${currentId.value}`, payload, getAuthHeader())
            : await api.post('saveEquipment', payload, getAuthHeader());
        await fetchEquipment();
        close();
    } catch (err) {
        error.value = 'Saving equipment failed';
    } finally {
        loading.value = false;
    }
}

function editEquipment(item) {
    isEditing.value = true;
    currentId.value = item.id;
    equipmentName.value = item.name;
    usage.value = item.usage;
    serialNumber.value = item.serial_no;
    assetCode.value = item.assetCode;
    equipmentValue.value = item.value;
    status.value = item.status;
    showAddEquipmentDialog.value = true;
}

// 4. GYMS LOGIC
async function fetchGyms() {
    try {
        const response = await api.get('getGyms', getAuthHeader());
        gyms.value = response.data || [];
    } catch (err) {
        error.value = 'Retrieving gyms failed';
    }
}

async function addGym() {
    loading.value = true
    const payload = { name: gymName.value, location: gymlocation.value, phone_number: primaryContact.value, description: gymDescription.value }
    try {
        isEditing.value
            ? await api.post(`updateGym/${currentId.value}`, payload, getAuthHeader)
            : await api.post('saveGym', payload, getAuthHeader);
        close();
        fetchGyms();
    } catch (err) {
        error.value = 'Saving gym failed'
    } finally {
        loading.value = false
    }
}

function editGym(gym) {
    isEditing.value = true;
    currentId.value = gym.id;
    gymName.value = gym.name;
    gymlocation.value = gym.location;
    primaryContact.value = gym.phone_number;
    gymDescription.value = gym.description;
    showAddGymDialog.value = true;
}

/**
 * INITIALIZATION
 * Runs when the component is mounted.
 */
onMounted(() => {
    fetchUsers();
    fetchEquipment();
    fetchRoles();
    fetchGyms();
});
</script>

<template>
    <v-container fluid class="bg-blue-grey-lighten-5 fill-height pa-0">
        <v-row no-gutters class="fill-height">
            <v-col cols="12">
                <v-card flat tile class="bg-white border-b px-6 py-4">
                    <v-row no-gutters align="center" justify="space-between" class="mb-4">
                        <v-col cols="auto">
                            <h1 class="text-h5 font-weight-black text-grey-darken-3 mb-1 text-uppercase">
                                ADMIN <span class="text-grey-lighten-1">DASHBOARD</span>
                            </h1>
                            <p class="text-caption text-grey-darken-1">Manage users, roles, equipment, and gym locations.</p>
                        </v-col>
                    </v-row>
                    <v-row no-gutters align="center" class="mt-4 ga-4">
                        <v-col cols="auto">
                            <div class="d-inline-flex pa-0 rounded-lg bg-grey-lighten-4 border overflow-hidden" style="height: 40px;">
                                <v-tabs v-model="tab" color="#ee6909" density="compact" hide-slider
                                    class="enhanced-tabs" height="40">
                                    <v-tab :value="0" class="text-caption font-weight-black px-6 transition-swing">OVERVIEW</v-tab>
                                    <v-tab :value="1" class="text-caption font-weight-black px-6 transition-swing">USERS</v-tab>
                                    <v-tab :value="2" class="text-caption font-weight-black px-6 transition-swing">ROLES</v-tab>
                                    <v-tab :value="3" class="text-caption font-weight-black px-6 transition-swing">EQUIPMENT</v-tab>
                                    <v-tab :value="4" class="text-caption font-weight-black px-6 transition-swing">GYMS</v-tab>
                                </v-tabs>
                            </div>
                        </v-col>

                        <v-spacer></v-spacer>

                        <v-col cols="auto" style="width: 250px;">
                            <v-text-field v-model="searchQuery" prepend-inner-icon="mdi-magnify" label="Search..."
                                variant="outlined" density="compact" hide-details rounded="lg" color="#ee6909"
                                class="bg-grey-lighten-5" style="max-height: 40px;"></v-text-field>
                        </v-col>

                        <v-col cols="auto">
                            <v-btn v-if="tab === 1" color="#ee6909" prepend-icon="mdi-account-plus"
                                class="font-weight-black text-white px-6" rounded="lg" height="40"
                                @click="showAddUserDialog = true">
                                ADD USER
                            </v-btn>
                            <v-btn v-if="tab === 3" color="#ee6909" prepend-icon="mdi-plus"
                                class="font-weight-black text-white px-6" rounded="lg" height="40"
                                @click="showAddEquipmentDialog = true">
                                ADD EQUIPMENT
                            </v-btn>
                            <v-btn v-if="tab === 4" color="#ee6909" prepend-icon="mdi-plus"
                                class="font-weight-black text-white px-6" rounded="lg" height="40"
                                @click="showAddGymDialog = true">
                                ADD GYM
                            </v-btn>
                        </v-col>
                    </v-row>
                </v-card>

                <v-tabs-window v-model="tab" class="bg-transparent px-6 py-4">
                    <!-- OVERVIEW TAB -->
                    <v-tabs-window-item :value="0">
                        <v-row class="mb-6">
                            <v-col cols="12" md="4">
                                <v-card flat border rounded="lg" class="pa-4 bg-white d-flex align-center">
                                    <v-avatar color="blue-lighten-5" rounded="lg" class="mr-4">
                                        <v-icon color="blue" icon="mdi-account-group"></v-icon>
                                    </v-avatar>
                                    <div>
                                        <div class="text-caption text-grey-darken-1 font-weight-bold uppercase">Total Users</div>
                                        <div class="text-h5 font-weight-black text-blue-darken-4">{{ totalUsersCount }}</div>
                                    </div>
                                </v-card>
                            </v-col>
                            <v-col cols="12" md="4">
                                <v-card flat border rounded="lg" class="pa-4 bg-white d-flex align-center">
                                    <v-avatar color="orange-lighten-5" rounded="lg" class="mr-4">
                                        <v-icon color="orange" icon="mdi-office-building-marker"></v-icon>
                                    </v-avatar>
                                    <div>
                                        <div class="text-caption text-grey-darken-1 font-weight-bold uppercase">Gym Locations</div>
                                        <div class="text-h5 font-weight-black text-orange-darken-4">{{ totalGymsCount }}</div>
                                    </div>
                                </v-card>
                            </v-col>
                            <v-col cols="12" md="4">
                                <v-card flat border rounded="lg" class="pa-4 bg-white d-flex align-center">
                                    <v-avatar color="green-lighten-5" rounded="lg" class="mr-4">
                                        <v-icon color="green" icon="mdi-weight-lifter"></v-icon>
                                    </v-avatar>
                                    <div>
                                        <div class="text-caption text-grey-darken-1 font-weight-bold uppercase">Total Equipment</div>
                                        <div class="text-h5 font-weight-black text-green-darken-4">{{ totalEquipmentCount }}</div>
                                    </div>
                                </v-card>
                            </v-col>
                        </v-row>
                    </v-tabs-window-item>

                    <!-- USERS TABLE -->
                    <v-tabs-window-item :value="1">
                        <v-card border flat class="bg-white rounded-lg overflow-hidden">
                            <v-sheet v-if="!filteredUsers.length" class="pa-12 text-center bg-transparent">
                                <v-icon size="64" color="grey-darken-2" class="mb-4">mdi-account-off-outline</v-icon>
                                <v-sheet class="text-h6 text-grey-darken-1 bg-transparent">No members found.</v-sheet>
                                <v-btn color="#ee6909" variant="text" class="mt-4" @click="showAddUserDialog = true">Add
                                    Users</v-btn>
                            </v-sheet>
                            <v-table v-else theme="light" density="compact">
                                <thead>
                                    <tr>
                                        <th
                                            class="condensed-header text-overline text-left font-weight-black text-grey-darken-2 py-4 px-4">
                                            NAME</th>
                                        <th
                                            class="condensed-header text-overline text-left font-weight-black text-grey-darken-2 py-4 px-4">
                                            EMAIL</th>
                                        <th
                                            class="condensed-header text-overline text-left font-weight-black text-grey-darken-2 py-4 px-4">
                                            PHONE</th>
                                        <th
                                            class="condensed-header text-overline text-left font-weight-black text-grey-darken-2 py-4 px-4">
                                            D.O.B</th>
                                        <th
                                            class="condensed-header text-overline text-left font-weight-black text-grey-darken-2 py-4 px-4">
                                            GENDER</th>
                                        <th
                                            class="condensed-header text-overline text-left font-weight-black text-grey-darken-2 py-4 px-4">
                                            GYM</th>
                                        <th
                                            class="condensed-header text-overline text-left font-weight-black text-grey-darken-2 py-4 px-4">
                                            ROLE</th>
                                        <th class="condensed-header text-overline text-center font-weight-black text-grey-darken-2 py-4 px-4">
                                            ACTIONS</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="user in filteredUsers" :key="user.id">
                                        <td class="font-weight-bold py-0 px-4 text-uppercase">{{ user.name }}</td>
                                        <td class="text-grey-darken-1 py-0 px-4 text-caption">{{ user.email }}</td>
                                        <td class="text-grey-darken-1 py-0 px-4 text-caption">{{ user.phoneNumber }}</td>
                                        <td class="text-grey-darken-1 py-0 px-4 text-caption">{{ new Date(user.date_of_birth).toLocaleDateString() }}</td>
                                        <td class="text-grey-darken-1 py-0 px-4 text-uppercase text-caption">{{ user.gender }}</td>
                                        <td class="text-grey-darken-1 py-0 px-4 text-caption">{{ user.gym?.name || 'N/A' }}</td>
                                        <td class="text-grey-darken-1 py-0 px-4 text-caption">{{ user.role?.name }}</td>
                                        <td class="py-0 px-4 text-right">
                                            <div class="d-flex justify-center ga-2">
                                                <v-btn v-if="user?.deleted_at == null" size="small" color="primary" variant="tonal" rounded="pill" class="px-3" @click="editUser(user)" title="Edit User">
                                                    <v-icon start icon="mdi-pencil" size="12"></v-icon> EDIT
                                                </v-btn>
                                                <v-btn v-if="user?.deleted_at == null" size="small" color="error" variant="tonal" rounded="pill" class="px-3" title="Deactivate" @click="deactivateUser(user)">
                                                    <v-icon start icon="mdi-account-cancel" size="12"></v-icon> DEACTIVATE
                                                </v-btn>
                                                <v-btn v-if="user?.deleted_at !== null" size="x-small" color="success" variant="tonal" rounded="pill" class="px-3" title="Activate" @click="activateUser(user)">
                                                    <v-icon start icon="mdi-account-check" size="12"></v-icon> ACTIVATE
                                                </v-btn>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </v-table>
                        </v-card>
                    </v-tabs-window-item>

                    <!-- ROLES TABLE -->
                    <v-tabs-window-item :value="2">
                        <v-card border flat class="bg-white rounded-lg overflow-hidden">
                            <v-sheet v-if="!filteredRoles.length" class="pa-12 text-center bg-transparent">
                                <v-icon size="64" color="grey-darken-2" class="mb-4">mdi-account-off-outline</v-icon>
                                <v-sheet class="text-h6 text-grey-darken-1 bg-transparent">No roles found.</v-sheet>
                                <v-btn color="#ee6909" variant="text" class="mt-4" @click="showAddUserDialog = true">Add
                                    roles</v-btn>
                            </v-sheet>
                            <v-table v-else theme="light" density="compact">
                                <thead>
                                    <tr>
                                        <th
                                            class="condensed-header text-overline text-left font-weight-black text-grey-darken-2 py-4 px-4">
                                            ROLE ID</th>
                                        <th
                                            class="condensed-header text-overline text-left font-weight-black text-grey-darken-2 py-4 px-4">
                                            ROLE NAME</th>
                                        <th
                                            class="condensed-header text-overline text-left font-weight-black text-grey-darken-2 py-4 px-4">
                                            DESCRIPTION
                                        </th>
                                        <th
                                            class="condensed-header text-overline text-left font-weight-black text-grey-darken-2 py-4 px-4">
                                            USERS
                                        </th>
                                        <th class="condensed-header text-overline text-center font-weight-black text-grey-darken-2 py-4 px-4">
                                            ACTIONS</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="role in filteredRoles" :key="role.id">
                                        <td class="font-weight-bold text-uppercase py-4 px-4">{{ role.id }}</td>
                                        <td class="font-weight-bold text-uppercase py-4 px-4">
                                            <v-chip size="x-small" color="#ee6909" variant="tonal" class="font-weight-black">{{ role.name }}</v-chip>
                                        </td>
                                        <td class="text-grey-darken-1 text-caption py-4 px-4">{{ role.description }}</td>
                                        <td class="font-weight-bold text-uppercase py-4 px-4 text-caption">{{ role.noOfUsers }}</td>
                                        <td class="text-right py-4 px-4">
                                            <div class="d-flex justify-end ga-2">
                                                <v-btn size="x-small" color="success" variant="tonal" rounded="pill" class="px-3" @click="editRole(role)">
                                                    <v-icon start icon="mdi-pencil" size="12"></v-icon> EDIT
                                                </v-btn>
                                                <v-btn size="x-small" color="error" variant="tonal" rounded="pill" class="px-3" @click="deleteRole(role)">
                                                    <v-icon start icon="mdi-delete" size="12"></v-icon> DELETE
                                                </v-btn>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </v-table>
                        </v-card>
                    </v-tabs-window-item>

                    <!-- EQUIPMENT TABLE -->
                    <v-tabs-window-item :value="3">
                        <v-card border flat class="bg-white rounded-lg overflow-hidden">
                            <v-sheet v-if="!filteredEquipment.length" class="pa-12 text-center bg-transparent">
                                <v-icon size="64" color="grey-darken-2" class="mb-4">mdi-account-off-outline</v-icon>
                                <v-sheet class="text-h6 text-grey-darken-1 bg-transparent">No equipment
                                    found.</v-sheet>
                                <v-btn color="#ee6909" variant="text" class="mt-4"
                                    @click="showAddEquipmentDialog = true">Add
                                    equipment</v-btn>
                            </v-sheet>
                            <v-table v-else theme="light" density="compact">
                                <thead>
                                    <tr>
                                        <th
                                            class="condensed-header text-overline text-left font-weight-black text-grey-darken-2 py-4 px-4">
                                            NAME
                                        </th>
                                        <th
                                            class="condensed-header text-overline text-left font-weight-black text-grey-darken-2 py-4 px-4">
                                            STATUS
                                        </th>
                                        <th
                                            class="condensed-header text-overline text-left font-weight-black text-grey-darken-2 py-4 px-4">
                                            USAGE NOTES
                                        </th>
                                        <th
                                            class="condensed-header text-overline text-left font-weight-black text-grey-darken-2 py-4 px-4">
                                            SERIAL NO.
                                        </th>
                                        <th
                                            class="condensed-header text-overline text-left font-weight-black text-grey-darken-2 py-4 px-4">
                                            ASSET CODE
                                        </th>
                                        <th
                                            class="condensed-header text-overline text-left font-weight-black text-grey-darken-2 py-4 px-4">
                                            VALUE
                                        </th>
                                        <th
                                            class="condensed-header text-overline text-left font-weight-black text-grey-darken-2 py-4 px-4">
                                            CATEGORY
                                        </th>
                                        <th class="condensed-header text-overline text-center font-weight-black text-grey-darken-2 py-4 px-4">
                                            ACTIONS</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="item in paginatedEquipment" :key="item.id">
                                        <td class="font-weight-bold py-4 px-4 text-uppercase">{{ item.name }}</td>
                                        <td class="py-4 px-4">
                                            <v-chip size="x-small" :color="item.status === 'active' ? 'success' : 'grey'" variant="tonal" class="font-weight-bold text-uppercase">{{ item.status }}</v-chip>
                                        </td>
                                        <td class="py-4 px-4 text-caption text-grey-darken-1">{{ item.usage }}</td>
                                        <td class="py-4 px-4 text-caption">{{ item.serial_no || item.serialNumber }}</td>
                                        <td class="py-4 px-4 text-caption">{{ item.assetCode }}</td>
                                        <td class="py-4 px-4 text-caption font-weight-bold">{{ item.value }}</td>
                                        <td class="py-4 px-4 text-caption">{{ item.category_id }}</td>
                                        <td class="py-4 px-4 text-right">
                                            <div class="d-flex justify-end ga-2">
                                                <v-btn size="x-small" color="primary" variant="tonal" rounded="pill" class="px-3" @click="editEquipment(item)">
                                                    <v-icon start icon="mdi-pencil" size="12"></v-icon> EDIT
                                                </v-btn>
                                                <v-btn size="x-small" color="error" variant="tonal" rounded="pill" class="px-3" @click="deleteEquipment(item)">
                                                    <v-icon start icon="mdi-delete" size="12"></v-icon> DELETE
                                                </v-btn>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </v-table>
                            <v-divider></v-divider>
                            <v-card-actions class="pa-4 bg-grey-lighten-5">
                                <v-row no-gutters align="center" justify="space-between">
                                    <v-col cols="auto" class="d-flex align-center">
                                        <span class="text-caption text-grey-darken-1 mr-4">Items per page:</span>
                                        <v-select v-model="itemsPerPage" :items="itemsPerPageOptions" density="compact" hide-details variant="outlined" style="width: 80px;"></v-select>
                                    </v-col>
                                    <v-col cols="auto">
                                        <v-pagination v-model="currentPage" :length="pageCount" density="compact" total-visible="5" color="#ee6909"></v-pagination>
                                    </v-col>
                                </v-row>
                            </v-card-actions>
                        </v-card>
                    </v-tabs-window-item>

                    <!-- GYMS TABLE -->
                    <v-tabs-window-item :value="4">
                        <v-card border flat class="bg-white rounded-lg overflow-hidden">
                            <v-sheet v-if="!filteredGyms.length" class="pa-12 text-center bg-transparent">
                                <v-icon size="64" color="grey-darken-2" class="mb-4">mdi-account-off-outline</v-icon>
                                <v-sheet class="text-h6 text-grey-darken-1 bg-transparent">No gyms found.</v-sheet>
                                <v-btn color="#ee6909" variant="text" class="mt-4" @click="showAddGymDialog = true">Add
                                    gym</v-btn>
                            </v-sheet>
                            <v-table v-else theme="light" density="compact">
                                <thead>
                                    <tr>
                                        <th
                                            class="condensed-header text-overline text-left font-weight-black text-grey-darken-2 py-4 px-4">
                                            GYM NAME</th>
                                        <th
                                            class="condensed-header text-overline text-left font-weight-black text-grey-darken-2 py-4 px-4">
                                            LOCATION</th>
                                        <th
                                            class="condensed-header text-overline text-left font-weight-black text-grey-darken-2 py-4 px-4">
                                            CONTACT
                                        </th>
                                        <th
                                            class="condensed-header text-overline text-left font-weight-black text-grey-darken-2 py-4 px-4">
                                            DESCRIPTION
                                        </th>
                                        <th class="condensed-header text-overline text-center font-weight-black text-grey-darken-2 py-4 px-4">
                                            ACTIONS</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="gym in gyms" :key="gym.id">
                                        <td class="font-weight-bold text-uppercase py-4 px-4">{{ gym.name }}</td>
                                        <td class="py-4 px-4 text-caption">{{ gym.location }}</td>
                                        <td class="text-grey-darken-1 text-caption py-4 px-4">{{ gym.phone_number }}</td>
                                        <td class="py-4 px-4 text-caption text-truncate" style="max-width: 200px;">{{ gym.description }}</td>
                                        <td class="text-right py-4 px-4">
                                            <div class="d-flex justify-end ga-2">
                                                <v-btn size="x-small" color="success" variant="tonal" rounded="pill" class="px-3" @click="editGym(gym)">
                                                    <v-icon start icon="mdi-pencil" size="12"></v-icon> EDIT
                                                </v-btn>
                                                <v-btn size="x-small" color="error" variant="tonal" rounded="pill" class="px-3" @click="deleteGym(gym)">
                                                    <v-icon start icon="mdi-delete" size="12"></v-icon> DELETE
                                                </v-btn>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </v-table>
                        </v-card>
                    </v-tabs-window-item>
                </v-tabs-window>
            </v-col>
        </v-row>
    </v-container>

    <v-dialog v-model="showAddUserDialog" max-width="500" persistent>
        <v-card theme="dark" class="bg-grey-darken-4 border border-white border-opacity-10 rounded-xl pa-4">
            <v-card-title class="d-flex align-center justify-space-between pb-6">
                <span class="text-h6 font-weight-black text-uppercase">{{ isEditing ? 'EDIT' : 'ADD' }} USER</span>
                <v-btn icon="mdi-close" variant="text" size="small" @click="close()"></v-btn>
            </v-card-title>
            <v-card-text class="px-2">
                <v-row row dense>
                    <v-col cols="6"><v-text-field label="First Name" v-model="firstName" variant="outlined"
                            density="compact" hide-details class="mb-4"></v-text-field></v-col>
                    <v-col cols="6"><v-text-field label="Last Name" v-model="lastName" variant="outlined"
                            density="compact" hide-details class="mb-4"></v-text-field></v-col>
                    <v-col cols="12"><v-text-field label="Email" v-model="email" variant="outlined" density="compact"
                            hide-details class="mb-4" :error-messages="error.includes('email') ? 'This email is already taken by another user' : ''" @input="error = ''"></v-text-field></v-col>
                    <v-col cols="12"><v-text-field label="Phone Number" v-model="phoneNumber" variant="outlined"
                            density="compact" hide-details class="mb-4"></v-text-field></v-col>
                    <v-col cols="6">
                        <v-date-input v-model="dateOfBirth" label="Select D.O.B" variant="outlined" density="compact"
                            color="black" base-color="grey-lighten-1" hide-details="auto" rounded="lg" prepend-icon=""
                            prepend-inner-icon="mdi-calendar-outline"></v-date-input>
                    </v-col>
                    <v-col cols="6">
                        <v-radio-group inline v-model="gender" hide-details class="mt-0 px-9 py-2">
                            <v-radio label="Male" value="male" color="black" density="compact"></v-radio>
                            <v-radio label="Female" value="female" color="black" density="compact"></v-radio>
                        </v-radio-group>
                    </v-col>
                    <v-col cols="12">
                        <v-select label="Gym Location" v-model="selectedGym" :items="gyms" item-title="name"
                            item-value="id" variant="outlined" density="compact" hide-details class="mb-4">
                        </v-select>
                    </v-col>
                </v-row>

                <div class="text-overline text-grey-lighten-1 mb-2"
                    style=" font-family: 'Roboto Condensed', sans-serif !important;">ASSIGN ROLE
                </div>
                <v-select v-model="userRole" :items="roles" item-title="name" item-value="id" variant="outlined"
                    label="Select User Role" :rules="[v => !!v || 'Role is required']"></v-select>
            </v-card-text>
            <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn variant="text" @click="close()">cancel</v-btn>
                <v-btn color="#ee6909" variant="flat" class="px-8 font-weight-black" rounded="lg" :loading="loading"
                    @click="addUser()">SAVE</v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
    <!-- Confirmation Dialog -->
    <v-dialog v-model="showDeleteDialog" max-width="400">
        <v-card class="rounded-xl pa-4 overflow-hidden">
            <v-card-title class="text-h6 font-weight-black text-uppercase d-flex align-center">
                <v-icon start :color="deleteConfig.color" icon="mdi-alert-circle-outline" class="mr-2"></v-icon>
                {{ deleteConfig.title }}
            </v-card-title>
            <v-card-text class="text-body-1 py-4 text-grey-darken-2">
                {{ deleteConfig.message }}
            </v-card-text>
            <v-card-actions class="pt-4">
                <v-spacer></v-spacer>
                <v-btn variant="text" color="grey-darken-1" class="font-weight-bold" @click="showDeleteDialog = false">CANCEL</v-btn>
                <v-btn :color="deleteConfig.color" variant="flat" class="px-6 font-weight-black text-white" rounded="lg" :loading="loading" @click="handleActionExec">
                    {{ deleteConfig.confirmText }}
                </v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>

<style scoped>
.condensed-header {
    font-family: 'Roboto Condensed', sans-serif !important;
    letter-spacing: 0.08rem !important;
    white-space: nowrap;
}

.enhanced-tabs .v-tab {
    min-width: 100px !important;
    height: 100% !important;
    color: #64748b !important;
    border-radius: 0 !important;
}

.enhanced-tabs .v-tab--selected {
    background-color: white !important;
    color: #ee6909 !important;
    box-shadow: none !important;
}

.hover-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 20px -10px rgba(0,0,0,0.1) !important;
    border-color: #ee6909 !important;
}

.view-toggle .v-btn {
    border-radius: 0 !important;
    color: #64748b !important;
    transition: all 0.2s ease !important;
}

.view-toggle .v-btn--active {
    background-color: white !important;
    color: #ee6909 !important;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1) !important;
}

.saas-container {
    border-radius: 8px !important;
    border: 1px solid #e2e8f0 !important;
}

.v-table {
    border-radius: 8px !important;
    border: 1px solid #e2e8f0 !important;
}

.v-table thead tr {
    background-color: #f8fafc !important;
}

.v-table tbody tr:hover {
    background-color: #f1f5f9 !important;
    transition: background-color 0.2s ease;
}

.v-btn {
    text-transform: none !important;
    letter-spacing: 0.02rem !important;
}
</style>
