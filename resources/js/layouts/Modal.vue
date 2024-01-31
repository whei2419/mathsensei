<template>
  <div class="modal-container">
    <div class="main-section">
        <div class="icon">
            <img src="image/info.svg" alt="">
        </div>
        <p>{{ message }}</p>
        <div class="buttons-container">
            <button class="button transparent button-full font-size-small" @click="closeModal">Cancel</button>
            <button class="button primary button-full font-size-small" @click="save">Confirm</button>
        </div>
    </div>
  </div>
</template>

<script>
export default {
    props: {
        message: {
            type: String,
            required: true
        }
    },
    data() {
        return {

        }
    },
    methods: {
        closeModal() {
            this.$emit('close');
        },
        save() {
            this.$emit('save');
        }
    }
}
</script>

<style lang="scss" scoped>
.modal-container {
    width: 100%;
    height: 100%;
    background: rgba(104, 104, 104, 0.445);
    position: absolute;
    top: 0;
    left: 0;
    display: flex;
    align-items: center;
    justify-content: center;

    .main-section {
        max-width: 400px;
        padding: 2rem 2rem;
        box-sizing: border-box;
        box-shadow: rgba(60, 64, 67, 0.3) 0px 1px 2px 0px, rgba(60, 64, 67, 0.15) 0px 2px 6px 2px;
        border-radius: 10px;
        background: $white;
        text-align: center;

        p {
            font-size: 1.2rem;
            margin-top: 20px;
        }

        .buttons-container{
            margin-top: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            flex-direction: row;

            button {
                min-height: 50px;
                font-size: 1rem;
            }
        }
    }
}

</style>
