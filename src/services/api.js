import axios from "axios";

const api = axios.create({
baseURL: "http://127.0.0.1:8000/api/",
headers: {
    "Content-Type": "application/json",
},
});

// REQUEST INTERCEPTOR: Runs before every request leaves your app
api.interceptors.request.use(
    (config) => {
        const token = localStorage.getItem("authToken");
        if (token) {
            // Attach the token to the Authorization header
            config.headers.Authorization = `Bearer ${token}`;
        }
        return config;
    },
    (error) => {
        return Promise.reject(error);
    }
);

// RESPONSE INTERCEPTOR: Handle 401 (Unauthorized) globally
api.interceptors.response.use(
    (response) => response,
    (error) => {
        if (error.response?.status === 401) {
            // Optional: Auto-logout user if token expires
            localStorage.clear();
            window.location.href = "/login";
        }
        return Promise.reject(error);
    }
);

export default api;
