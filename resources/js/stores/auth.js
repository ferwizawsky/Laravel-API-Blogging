import { ref, computed } from "vue";
import { defineStore } from "pinia";

export const useAuth = defineStore("user", () => {
    const user = ref({
        id: 1,
        name: "",
        role_id: 0,
    });
    const token = ref(localStorage.getItem("token") ?? null);
    const isAuthenticated = computed(() => {
        return token.value ? true : false;
    });
    return { user, token, isAuthenticated };
});
