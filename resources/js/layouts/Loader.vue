<template>
    <div class="loader-container">
        <div class="loader"></div>
        <p>Loading..</p>
    </div>
</template>

<script>
    export default {

    }
</script>

<style lang="scss" scoped>
.loader-container {
    width: 100%;
    height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
    flex-direction: column;

    p {
        font-size: 2rem;
    }
    .loader {
    border: 8px solid #b68fff;
    border-top: 8px solid $light-red;
    border-radius: 50%;
    width: 5rem;
    height: 5rem;
    animation: spin 2s linear infinite;
    margin-bottom: 10px;
}

@keyframes spin {
    0% {
        transform: rotate(0deg);
    }

    100% {
        transform: rotate(360deg);
    }
}
}

</style>
