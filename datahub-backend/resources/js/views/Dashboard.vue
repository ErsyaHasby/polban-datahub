<template>
    <div class="dashboard-container">
        <h1>Login Berhasil!</h1>
        <p>Selamat datang di Dashboard.</p>
        <button @click="logout">Logout</button>
    </div>
</template>

<script>
export default {
    name: 'Dashboard',
    methods: {
        logout() {
            localStorage.removeItem('token');
            this.$router.push({ name: 'Login' });
        }
    }
};
</script>

<style scoped>
.dashboard-container {
    max-width: 400px;
    margin: 50px auto;
    padding: 20px;
    border: 1px solid #ccc;
    border-radius: 5px;
    text-align: center;
}
</style>
