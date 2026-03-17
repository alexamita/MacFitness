<script setup>
import { ref, onMounted } from 'vue'
import api from '../services/api';

const token = localStorage.getItem('authToken');
const error = ref('')
const loading = ref(false)
const tab = ref(null)
const showAddUserDialog = ref(false)
const showAddRoleDialog = ref(false)
const showAddEquipmentDialog = ref(false)
const showEditUserDialog = ref(false)

const rules = {
    required: value => !!value || 'Required.',
    min: v => v.length >= 8 || 'Min 8 characters',
}

// Static list of gym locations for the dropdown menu
const gymLocations = {
    1: 'Iron Forge Fitness',
    2: 'Pulse Performance Center',
    3: 'ZenCore Wellness Studio',
    4: 'MetroFit Downtown',
    5: 'Elite Sports Conditioning Hub'
};

// Transforming gymLocations for Vuetify v-select compatibility
const gymItems = Object.entries(gymLocations).map(([id, name]) => ({
    title: name,
    value: id
}));

//users
//models
const fullName = ref(null)
const firstName = ref(null)
const lastName = ref(null)
const email = ref(null)
const phoneNumber = ref(null)
const dob = ref(null)
const gender = ref(null)
const selectedGym = ref(null)
const users = ref(null)
const userRole = ref(null)

//fetch data
async function fetchUsers() {

    try {
        await api.get('users', { headers: { 'Authorization': `Bearer ${token}` } })
            .then(function (response) {
                if (response.data) {
                    users.value = response.data
                }
            })
    } catch (err) {
        error.value = err.response?.data?.message || 'Retrieving data failed'
        throw err
    }
}

async function fetchRoles() {
    try {
        await api.get('roles', { headers: { 'Authorization': `Bearer ${token}` } })
            .then(function (response) {
                if (response.data) {
                    roles.value = response.data
                }
            })
    } catch (err) {
        error.value = err.response?.data?.message || 'Retrieving data failed'
        throw err
    }
}

//add user
async function addUser() {
    const payload = {
        name: `${firstName.value} ${lastName.value}`,
        email: email.value,
        phoneNumber: phoneNumber.value,
        date_of_birth: dob.value,
        gender: gender.value,
        gym_id: Number(selectedGym.value), // Since selectedGym stores the ID directly from the updated v-select:
        role_id: 4 // Static role ID (e.g., '4' for a standard Member)
    }

    try {
        await api.post('users', payload,
            { headers: { 'Authorization': `Bearer ${token}` } })
            .then(function (response) {
                error.value = ''
                loading.value = false
                close()
                fetchUsers();
            })
    } catch (err) {
        error.value = err.response?.data?.message || 'Creating user failed'
        throw err
    }
}

//roles
const roles = ref(null)


//equipment
const equipment = ref(null)
const equipmentName = ref(null)
const usage = ref(null)
const serialNumber = ref(null)
const assetCode = ref(null)
const equipmentValue = ref(null)
const status = ref(null)

// add equipment
async function fetchEquipment() {

    try {
        await api.get('getEquipment', { headers: { 'Authorization': `Bearer ${token}` } })
            .then(function (response) {
                if (response.data) {
                    equipment.value = response.data
                }
            })
    } catch (err) {
        error.value = err.response?.data?.message || 'Retrieving data failed'
        throw err
    }
}

//add Equipment
async function addEquipment() {
    const payload = {
        name: equipmentName.value,
        usage: usage.value,
        serial_no: serialNumber.value,
        assetCode: assetCode.value,
        value: Number(equipmentValue.value),
        status: status.value,
    }

    try {
        await api.post('saveEquipment', payload,
            { headers: { 'Authorization': `Bearer ${token}` } })
            .then(function (response) {
                error.value = ''
                loading.value = false
                close()
                fetchEquipment();
            })
    } catch (err) {
        error.value = err.response?.data?.message || 'Could not add equipment'
        throw err
    }
}

//clear reactive model values
function close() {
    showAddEquipmentDialog.value = false
    showAddUserDialog.value = false
    showEditUserDialog.value = false
    fullName.value = null
    firstName.value = null
    lastName.value = null
    email.value = null
    phoneNumber.value = null
    dob.value = null
    gender.value = null
    selectedGym.value = null
    userRole.value = null

    // equipment
    equipmentName.value = null
    usage.value = null
    serialNumber.value = null
    assetCode.value = null
    equipmentValue.value = null
    status.value = null
}

onMounted(() => {
    fetchUsers();
    fetchEquipment();
    fetchRoles();
    // fetchSubscriptions();
});

</script>

<template>
    <v-container class="text-center mt-12" style="background-color:#CFD0D6">
        <v-card>
            <v-tabs v-model="tab" align-tabs="center" color="primary">
                <v-tab :value="1">Users</v-tab>
                <v-tab :value="2">Roles</v-tab>
                <v-tab :value="3">Equipment</v-tab>
                <v-tab :value="3">Gyms</v-tab>
            </v-tabs>

            <v-tabs-window v-model="tab">
                <!-- Users -->
                <v-tabs-window-item :value="1">
                    <div v-if="users == null || users == undefined || Object.keys(users).length == 0" align="center">
                        <v-row>
                            <v-col cols="12" md="6" sm="12">
                                <div class="text-h6">No users found</div>
                            </v-col>
                            <v-col cols="12" md="6" sm="12">
                                <v-btn class="ma-2" color="blue-darken-2" icon="mdi-plus"
                                    @click="showAddUserDialog = true"></v-btn>
                            </v-col>
                        </v-row>
                    </div>
                    <div v-else>
                        <v-container>
                            <v-row>
                                <v-col cols="12" md="12" sm="12" align="right">
                                    <v-btn class="ma-2" color="blue-darken-2" icon="mdi-plus"
                                        @click="showAddUserDialog = true"></v-btn>
                                </v-col>
                            </v-row>
                            <v-row>
                                <v-col>
                                    <v-table class="border">
                                        <thead>
                                            <tr>
                                                <th class="text-left"> Name </th>
                                                <th class="text-left"> Email </th>
                                                <th class="text-left"> Phone </th>
                                                <th class="text-left"> Date of Birth </th>
                                                <th class="text-left"> Gender </th>
                                                <th class="text-left"> Prefered Gym </th>
                                                <th class="text-left"> Role </th>
                                                <th class="text-center" colspan="3"> Action </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="item in users" :key="item.id">
                                                <td>{{ item.name }}</td>
                                                <td>{{ item.email }}</td>
                                                <td>{{ item.phoneNumber }}</td>
                                                <td>{{ item.dob }}</td>
                                                <td>{{ item.gender }}</td>
                                                <td>{{ item.selectedGym }}</td>
                                                <td>{{ item.userRole }}</td>
                                                <td v-if="item.deleted_at == null">
                                                    <v-btn color="success" size="small"
                                                        @click="changeRole(item)"><v-icon icon="mdi-pencil"></v-icon>
                                                        Change Role</v-btn>
                                                </td>
                                                <td v-if="item.deleted_at == null">
                                                    <v-btn color="primary" size="small" @click="editUser(item)"><v-icon
                                                            icon="mdi-pencil"></v-icon> Edit User</v-btn>
                                                </td>
                                                <td v-if="item.deleted_at == null">
                                                    <v-btn color="error" size="small"><v-icon
                                                            icon="mdi-account-cancel"></v-icon> Deactivate</v-btn>
                                                </td>
                                                <td colspan="3" v-if="item.deleted_at !== null" align="center">
                                                    <v-btn color="warning" size="small"><v-icon
                                                            icon="mdi-account-check"></v-icon> Activate</v-btn>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </v-table>
                                </v-col>
                            </v-row>
                        </v-container>
                    </div>
                </v-tabs-window-item>
                <!-- Roles -->
                <v-tabs-window-item :value="2">
                    <div v-if="roles == null || roles == undefined || Object.keys(roles).length == 0" align="center">
                        <v-row>
                            <v-col cols="12" md="6" sm="12">
                                <div class="text-h6">No roles found</div>
                            </v-col>
                            <v-col cols="12" md="6" sm="12">
                                <v-btn class="ma-2" color="blue-darken-2" icon="mdi-plus"
                                    @click="showAddRoleDialog = true"></v-btn>
                            </v-col>
                        </v-row>
                    </div>
                    <div v-else>
                        <v-container>
                            <v-row>
                                <v-col>
                                    <v-table class="border">
                                        <thead>
                                            <tr>
                                                <th class="text-left"> Name </th>
                                                <th class="text-left"> Abilities </th>
                                                <th class="text-left"> No of Users </th>
                                                <th class="text-center" colspan="3"> Action </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="item in roles" :key="item.id">
                                                <td>{{ item.name }}</td>
                                                <td>{{ item.abilities }}</td>
                                                <td>{{ item.noOfUsers }}</td>
                                                <td v-if="item.deleted_at == null">
                                                    <v-btn color="primary" size="small" @click="editRole(item)"><v-icon
                                                            icon="mdi-pencil"></v-icon> Edit User</v-btn>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </v-table>
                                </v-col>
                            </v-row>
                        </v-container>
                    </div>
                </v-tabs-window-item>
                <!-- Equipment -->
                <v-tabs-window-item :value="3">
                    <div v-if="equipment == null || equipment == undefined || Object.keys(equipment).length == 0"
                        align="center">
                        <v-row>
                            <v-col cols="12" md="6" sm="12">
                                <div class="text-h6">No equipment found</div>
                            </v-col>
                            <v-col cols="12" md="6" sm="12">
                                <v-btn class="ma-2" color="blue-darken-2" icon="mdi-plus"
                                    @click="showAddEquipmentDialog = true"></v-btn>
                            </v-col>
                        </v-row>
                    </div>
                    <div v-else>
                        <v-container>
                            <v-row>
                                <v-col cols="12" md="12" sm="12" align="right">
                                    <v-btn class="ma-2" color="blue-darken-2" icon="mdi-plus"
                                        @click="showAddEquipmentDialog = true"></v-btn>
                                </v-col>
                            </v-row>
                            <v-row>
                                <v-col>
                                    <v-table class="border">
                                        <thead>
                                            <tr>
                                                <th class="text-left"> Name </th>
                                                <th class="text-left"> Model No </th>
                                                <th class="text-left"> Value </th>
                                                <th class="text-left"> Usage </th>
                                                <th class="text-left"> Status </th>
                                                <th class="text-center" colspan="3"> Action </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="item in equipment" :key="item.id">
                                                <td>{{ item.name }}</td>
                                                <td>{{ item.model_no }}</td>
                                                <td>{{ item.value }}</td>
                                                <td>{{ item.usage }}</td>
                                                <td>{{ item.status }}</td>
                                                <td v-if="item.deleted_at == null">
                                                    <v-btn color="primary" size="small"
                                                        @click="editEquipment(item)"><v-icon icon="mdi-pencil"></v-icon>
                                                        Edit User</v-btn>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </v-table>
                                </v-col>
                            </v-row>
                        </v-container>
                    </div>
                </v-tabs-window-item>
            </v-tabs-window>
        </v-card>
    </v-container>
    <!-- Add User Dialog -->
    <v-dialog v-model="showAddUserDialog" max-width="600">
        <v-form @submit.prevent>
            <v-card>
                <v-card-title class="pa-6">
                    <v-row>
                        Add User
                        <v-spacer></v-spacer>
                        <v-btn class="ma-2" color="blue-darken-2" icon="mdi-close" @click="close();"></v-btn>
                    </v-row>
                </v-card-title>
                <v-card-text>
                    <v-row>
                        <v-col md="6">
                            <v-text-field label="First Name" v-model="firstName" required
                                :rules="[rules.required]"></v-text-field>
                        </v-col>
                        <v-col md="6">
                            <v-text-field label="Last Name" v-model="lastName" required
                                :rules="[rules.required]"></v-text-field>
                        </v-col>
                    </v-row>

                    <v-row>
                        <v-col md="6">
                            <v-text-field label="Email" v-model="email" required
                                :rules="[rules.required]"></v-text-field>
                        </v-col>
                        <v-col md="6">
                            <v-text-field label="Phone Number" v-model="phoneNumber" required
                                :rules="[rules.required]"></v-text-field>
                        </v-col>
                    </v-row>
                    <v-row>
                        <v-col md="6">
                            <v-select label="Gym Location" v-model="selectedGym" :items="gymItems" item-title="title"
                                item-value="value" variant="outlined"></v-select>
                        </v-col>
                        <v-col md="6">
                            <v-date-input label="Date of Birth" v-model="dob" required
                                :rules="[rules.required]"></v-date-input>
                        </v-col>
                    </v-row>
                    <v-row>
                        <v-col cols="12" md="2" sm="6"> Role: </v-col>
                        <v-col cols="12" md="10" sm="6">
                            <v-radio-group v-model="userRole" :rules="[rules.required]" inline>
                                <v-radio label="Admin" value="1"></v-radio>
                                <v-radio label="Trainer" value="2"></v-radio>
                                <v-radio label="Staff" value="3"></v-radio>
                                <v-radio label="User" value="4"></v-radio>
                            </v-radio-group>
                        </v-col>
                    </v-row>
                    <v-row>
                        <v-col cols="12" md="2" sm="6"> Gender: </v-col>
                        <v-col cols="12" md="10" sm="6">
                            <v-radio-group v-model="gender" :rules="[rules.required]" inline>
                                <v-radio label="Male" value="Male"></v-radio>
                                <v-radio label="Female" value="Female"></v-radio>
                            </v-radio-group>
                        </v-col>
                    </v-row>
                </v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn text="Close" variant="plain" @click="close()"></v-btn>
                    <v-btn color="primary" text="Save" variant="tonal" @click="addUser()"></v-btn>
                </v-card-actions>
            </v-card>
        </v-form>
    </v-dialog>
    <!-- Edit User Dialog -->
    <v-dialog v-model="showEditUserDialog" max-width="600">
        <v-form @submit.prevent>
            <v-card>
                <v-card-title class="pa-6">
                    <v-row>
                        Edit User
                        <v-spacer></v-spacer>
                        <v-btn class="ma-2" color="blue-darken-2" icon="mdi-close" @click="close();"></v-btn>
                    </v-row>
                </v-card-title>
                <v-card-text>
                    <v-row dense>
                        <v-col>
                            <v-text-field label="Name" v-model="firstName" required
                                :rules="[rules.required]"></v-text-field>
                        </v-col>
                    </v-row>
                    <v-row dense>
                        <v-col cols="12" md="2" sm="6"> Role: </v-col>
                        <v-col cols="12" md="10" sm="6">
                            <v-radio-group v-model="userRole" :rules="[rules.required]">
                                <v-row>
                                    <v-col cols="12" md="6" sm="6">
                                        <v-radio label="Admin" value="2"></v-radio>
                                    </v-col>
                                    <v-col cols="12" md="6" sm="6">
                                        <v-radio label="Healthcare Provider" value="3"></v-radio>
                                    </v-col>
                                </v-row>
                            </v-radio-group>
                        </v-col>
                    </v-row>
                    <v-row dense>
                        <v-col>
                            <v-text-field label="Email" v-model="email" required
                                :rules="[rules.required]"></v-text-field>
                        </v-col>
                    </v-row>
                    <v-row dense>
                        <v-col>
                            <v-text-field label="Phone" v-model="phoneNumber" required
                                :rules="[rules.required]"></v-text-field>
                        </v-col>
                    </v-row>
                </v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn text="Close" variant="plain" @click="close()"></v-btn>
                    <v-btn color="primary" type="submit" text="Update" variant="tonal" @click="updateUser()"></v-btn>
                </v-card-actions>
            </v-card>
        </v-form>
    </v-dialog>
    <!-- Add Equipment Dialog -->
    <v-dialog v-model="showAddEquipmentDialog" max-width="600">
        <v-form @submit.prevent>
            <v-card>
                <v-card-title class="pa-6">
                    <v-row>
                        Add Equipment
                        <v-spacer></v-spacer>
                        <v-btn class="ma-2" color="blue-darken-2" icon="mdi-close" @click="close();"></v-btn>
                    </v-row>
                </v-card-title>
                <v-card-text>
                    <v-row>
                        <v-col md="6">
                            <v-text-field label="Name" v-model="equipmentName" required
                                :rules="[rules.required]"></v-text-field>
                        </v-col>
                        <v-col md="6">
                            <v-text-field label="Usage Notes" v-model="usage" required
                                :rules="[rules.required]"></v-text-field>
                        </v-col>
                    </v-row>

                    <v-row>
                        <v-col md="6">
                            <v-text-field label="Manufacturer Serial No." v-model="serialNumber" required
                                :rules="[rules.required]"></v-text-field>
                        </v-col>
                        <v-col md="6">
                            <v-text-field label="Asset Code" v-model="assetCode" required
                                :rules="[rules.required]"></v-text-field>
                        </v-col>
                    </v-row>
                    <v-row>
                        <v-col md="6">
                            <v-text-field label="Value" v-model="equipmentValue" item-value="value"
                                variant="outlined"></v-text-field>
                        </v-col>
                        <v-col md="6">
                            <v-text-field label="Status" v-model="status" required
                                :rules="[rules.required]"></v-text-field>
                        </v-col>
                    </v-row>
                </v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn text="Close" variant="plain" @click="close()"></v-btn>
                    <v-btn color="primary" text="Save" variant="tonal" @click="addEquipment()"></v-btn>
                </v-card-actions>
            </v-card>
        </v-form>
    </v-dialog>
</template>
