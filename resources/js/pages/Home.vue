<template>
    <div class="game-container">
        <particles></particles>
        <loader v-if="isLoading"></loader>
        <div v-else class="main">
            <div class="logo-container">
                <img src="image/logo.svg" alt="logo">
            </div>
            <div class="start-container">
                <p class="home-text">Welcome <span>{{ userData.name }}</span> to our game press the button to start</p>
                <img src="image/highlight.svg" alt="">
                <button @click="handleStart" class="solve-now-btn button primary button-full font-size-small">
                    <span>Solve now</span> <img src="image/triangle.svg" alt="">
                </button>
                <button @click="openModal" class="sign-out button transparent button-full font-size-small">
                    <span>Exit</span>
                </button>
            </div>
        </div>
        <Modal v-if="isOpen" @close="handleClose" @save="handleLogout" :message="modalMessage"></Modal>
    </div>
</template>

<script>
import particles from "../layouts/Particle.vue"
import bgSound from "../../sounds/ES_Sunshine Rain.mp3";
import axios from "axios";
import config from "../utils.js";
import loader  from "../layouts/Loader.vue";
import Modal from "../layouts/Modal.vue";
export default {
    components: {
        particles,
        loader,
        Modal
    },
    data() {
        return {
            audio: new Audio(bgSound),
            userData: null,
            token: null,
            isLoading: false,
            isOpen:false,
            modalMessage:'Are you suer you want to logout?'
        };
    },
    methods: {
        openModal(){
            this.isOpen = true;
        },
        handleClose(){
            this.isOpen = false;
        },
        handleLogout() {
            const token = localStorage.getItem("token");
            axios({
                method: "post",
                url: `${config.baseUrl}/api/user/logout`,
                headers: {
                    Authorization: `Bearer ${token}`,
                },
            }).then((res) => {
                localStorage.removeItem("token");
                this.isOpen = false;
                this.$router.push('/');
            });

        },
        playMusic() {
            this.audio.play();
        },
        pauseMusic() {
            this.audio.pause();
        },
        getQuestion() {
            const token = localStorage.getItem("token");
            axios({
                method: "get",
                url: `${config.baseUrl}/api/question/get`,
                headers: {
                    Authorization: `Bearer ${token}`,
                },
            }).then((res) => {
                console.log(res.data);
                localStorage.setItem('question', JSON.stringify(res.data.questions));
                const currentTime = new Date();
                const dateString = currentTime.toISOString();
                const startTime = new Date();
                localStorage.setItem('startTime', dateString);
                const endTime = new Date(startTime.getTime() + 180 * 1000);
                const endTimeString = endTime.toISOString();
                localStorage.setItem('endTime', endTimeString);
                this.$router.push('/game');
                this.isLoading = false;
            }).catch((error) => {
                console.error('Error occurred:', error);
            });
        },
        handleStart() {
            this.isLoading = true;
            const token = localStorage.getItem("token");
            axios({
                method: "post",
                url: `${config.baseUrl}/api/game/add`,
                data: {
                    user_id: this.userData.id,
                },
                headers: {
                    Authorization: `Bearer ${token}`,
                },
            }).then((res) => {
                localStorage.setItem("isStart", true);
                localStorage.setItem('game_id', res.data.game.id);
                this.getQuestion();
            });
        },
    },
    created() {
        this.token = config.token;
        this.userData = JSON.parse(localStorage.getItem("userDetails"));
    }
};
</script>

<style lang="scss" scoped>
.game-container {
    box-sizing: border-box;
    padding-top: 30px;
    position: relative;

    .main {
        max-width: 500px;
        margin: 0 auto;
        margin-top: 20px;
        padding: 1rem;
        box-sizing: border-box;
        box-shadow: rgba(60, 64, 67, 0.3) 0px 1px 2px 0px, rgba(60, 64, 67, 0.15) 0px 2px 6px 2px;
        border: solid 3px $light-red;
        border-radius: 10px;
    }

    .logo-container {
        display: flex;
        justify-content: center;
        align-items: center;
        box-sizing: border-box;

        img {
            width: 80%;
        }

        margin-bottom: 10px;
        box-sizing: border-box;
    }

    .start-container {
        width: 100%;
        border-radius: 10px;
        padding: 20px 20px 40px 20px;
        text-align: center;
        background-color: $pale-background;
        box-sizing: border-box;

        .home-text {
            font-size: 1.5rem;
            color: $pale-text;
            margin-bottom: 20px;
            span {
                font-size: 2rem;
                color: #4F709C;
            }
        }

        img {
            margin-bottom: 20px;
            width: 70%;
        }

        .solve-now-btn {
            box-sizing: border-box;
            widows: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 10px;
            margin-bottom: 10px;

            span {
                font-size: 1.5rem;
            }

            img {
                width: 25px;
                margin-bottom: 0;
            }
        }
    }
}</style>
