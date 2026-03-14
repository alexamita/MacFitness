import { ref, computed } from "vue";
import api from "./api"; // Axios instance for API calls


/** * GLOBAL STATE
 * Defined outside the function so the state is shared across all components that use this service (Singleton pattern).
 */
// Initialize from localStorage so state persists on page refresh
const storedUser = localStorage.getItem("user");
const user = ref(storedUser ? JSON.parse(storedUser) : null); // Holds user profile data

// const user = ref(null);

const loading = ref(false); // Tracks if an API request is in progress
const error = ref(null); // Holds any error messages from the server

export function useAuth() {

    // COMPUTED PROPERTY: Automatically updates whenever user.value changes.
    // Returns true if user has data, false if null
    const isAuthenticated = computed(() => !!user.value);

    // COMPUTED: Automatically calculates if the user is an admin.
    const isAdmin = computed(() => user.value?.role === 1);

    /**
     * LOGIN FUNCTION
     * Takes {email, password} and talks to the backend
     */
    async function login(credentials) {
        loading.value = true;
        error.value = null;

        try {
            // Basic client-side validation to check whether credentials are entered
            if (!credentials.email || !credentials.password) {
                throw new Error("Email and password are required");
            }

            // POST request to /login
            const response = await api.post("login", credentials);
            const { token, user: userData } = response.data;

            // console.log(response.data);

            if (token && userData) {
                // Updating user.value automatically updates isAuthenticated and isAdmin
                user.value = userData;


                // PERSISTENCE: Save session data so it survives page refreshes
                localStorage.setItem("authToken", token);
                localStorage.setItem("user", JSON.stringify(user.value));

                return response;
            } else {
                throw new Error("Invalid response format from server");
            }
        } catch (err) {
            // Capture specific server error messages or use a default
            error.value = err.response?.data?.message || err.message || "Login failed";
            throw err;
        } finally {
            loading.value = false; // Always stop the loading spinner
        }
    }

    /**
     * REGISTER FUNCTION
     * Sends form data (like the one from your Signup page) to the server
     */
    async function register(formData) {
        loading.value = true;
        error.value = null;
        try {
            const response = await api.post("register", formData);
            const { token, user: userData } = response.data;
            if (token && userData) {
                user.value = userData;
                localStorage.setItem("authToken", token);
                localStorage.setItem("user", JSON.stringify(user.value));

                return response;
            } else {
                throw new Error("Invalid response format from server");
            }
        } catch (err) {
            error.value = err.response?.data?.message || "Registration failed";
            throw err;
        } finally {
            loading.value = false;
        }
    }

    /**
     * LOGOUT FUNCTION
     * Clears everything locally to "forget" the user
     */
    function logout() {
        user.value = null;
        localStorage.removeItem("authToken");
        localStorage.removeItem("user");
    }
    // Expose these variables and functions to any component that calls useAuth()
    return {
        user,
        loading,
        error,
        isAuthenticated,
        isAdmin,
        login,
        register,
        logout,
    };
}
