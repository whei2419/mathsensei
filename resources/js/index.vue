<template>
    <div class="main-container">
        <div v-if="isLoading" class="loader"></div>
        <p>Loading..</p>
    </div>
</template>
<script>
import axios from 'axios';
import config from '../js/utils.js';

export default {
    data() {
    return {
      isLoading: true
    };
  },
    created() {
        if (config.token) {
            axios({
                method: 'get',
                url: `${config.baseUrl}/api/user`,
                headers: {
                    Authorization: `Bearer ${config.token}`
                },
            })
                .then((res) => {
                    this. isLoading = false;
                    console.log(res.status);
                    if (res.status != 200) {
                        this.goToLogin();
                    } else {
                        this.goToHome();
                    }
                });

        } else {
            this.goToLogin();
        }
    },
    methods: {
        goToHome() {
            this.$router.push('/Home');
        },
        goToLogin() {
            this.$router.push('/login');
        }
    }
};
</script>
<style lang="scss" scoped>
.main-container{
    width: 100%;
    height: 100vh;
    margin: 0;
    background: $pale-cream;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-direction: column;
}
p{
   font-size: 1.5rem;
}
.loader {
  border: 8px solid $light-red;
  border-top: 8px solid $red;
  border-radius: 50%;
  width: 5rem;
  height: 5rem;
  animation: spin 2s linear infinite;
  margin-bottom: 10px;
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}
</style>
