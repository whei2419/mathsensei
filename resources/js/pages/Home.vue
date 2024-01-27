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
import bgSound from '../../sounds/ES_Sunshine Rain.mp3'
import axios from 'axios';
import baseUrl from '../utils.js';
export default {
    data() {
        return {
            audio: new Audio(bgSound),
            userData: null
        };
    },
    mounted() {
        this.playMusic();
        this.userData = JSON.parse(localStorage.getItem('userDetails'));
    },
    methods: {
        playMusic() {
            this.audio.play();
        },
        pauseMusic() {
            this.audio.pause();
        },
        handleStart() {
            localStorage.setItem('isStart', true);
            console.log(localStorage.getItem('isStart'));
            console.log(this.userData);
            const token = localStorage.getItem('token');

            axios({
                method: 'post',
                url: `${baseUrl}/api/game/add`,
                data: {
                    user_id: this.userData.id
                },
                headers: {
                    Authorization: `Bearer ${token}`
                },
                responseType: 'stream'
            })
                .then((res) => {
                    console.log(res);
                });

        }
    },
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
