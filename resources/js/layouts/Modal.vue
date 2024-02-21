<template>
  <div class="modal-container">
    <div class="main-section">
      <div class="modal-header">
        <h2>{{ title }}</h2>
        <div class="icon">
            <button class="button x-mark" @click="closeModal">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
      </div>
      <div class="content">
        <p>{{ message }}</p>
      </div>
      <div class="buttons-container">
        <button
        v-if="close"
          class="button transparent button-full font-size-small"
          @click="closeModal"
        >
          Cancel
        </button>
        <button
          v-if="confirm"
          class="button primary button-full font-size-small"
          @click="save"
        >
          Confirm
        </button>
      </div>
    </div>
  </div>
</template>

<script>
import "@fortawesome/fontawesome-free/css/all.css";
import { boolean } from "yup";
export default {
  props: {
    title: {
      type: String,
      required: false,
    },
    confirm: {
      type: Boolean,
      default: true,
    },
    close: {
      type: Boolean,
      default: true,
    },
    message: {
      type: String,
      required: true,
    },
  },
  data() {
    return {};
  },
  methods: {
    closeModal() {
      this.$emit("close");
    },
    save() {
      this.$emit("save");
    },
  },
};
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
    position: relative;
    min-width: 350px;
    max-width: 400px;
    padding: 20px;
    box-sizing: border-box;
    box-shadow: rgba(60, 64, 67, 0.3) 0px 1px 2px 0px,
      rgba(60, 64, 67, 0.15) 0px 2px 6px 2px;
    border-radius: 10px;
    background: $white;
    text-align: center;

    p {
      font-size: 1.2rem;
    }

    .modal-header {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 20px;
        box-sizing: border-box;

        .x-mark {
            border-radius: 50%;
            color: $white;
            background: $red;
            display: flex;
            justify-content: center;
            align-items: center;
            width: 30px;
            height: 30px;
        }
    }

    .content {
        margin-top: 50px;
        padding: 10px;
        border-radius:8px ;
    }

    .buttons-container {
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
