<script setup>
import Notif from "@/components/Notif.vue";
import Loading from "@/components/Loading.vue";
</script>
<template>
    <div class="min-h-screen bg-">
        <Notif />
        <Loading />
        <RouterView />
    </div>
</template>
