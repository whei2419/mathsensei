<template>
    <div class="game-container">
        <div class="main">
            <h1>Equation sensei 2</h1>
            <div class="start-container">
                <button @click="handleStart" class="solve-now-btn button primary button-full font-size-small">
                    Solve now
                </button>
            </div>
        </div>
    </div>
</template>

<script>
import bgSound from "../../sounds/ES_Sunshine Rain.mp3";
import axios from "axios";
import config from "../utils.js";
export default {
    data() {
        return {
            audio: new Audio(bgSound),
            userData: null,
            token: null,
        };
    },
    mounted() {
        // this.playMusic();
    },
    methods: {
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
                const endTime = new Date(startTime.getTime() + 60 * 1000);
                const endTimeString = endTime.toISOString();
                localStorage.setItem('endTime', endTimeString);
                this.$router.push('/game');
            });
        },
        handleStart() {
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
                localStorage.setItem('game_id', res.data.game.user_id);
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
    .main {
        width: 100%;
        margin: 0 auto;
        padding: 3rem;
        box-sizing: border-box;
    }

    .start-container {
        width: 100%;

        .solve-now-btn {
            widows: 100%;
        }
    }
}
</style>
