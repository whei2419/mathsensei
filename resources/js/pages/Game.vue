<template>
    <div class="game-container" @click="randomTap">
        <div class="game-information">
            <div class="header-game">
                <button class=" button primary  font-size-small">
                    Menu
                </button>
                <div class="time-container">
                    <p class="time">{{ formattedTime }}</p>
                </div>
                <button class="button primary  font-size-small" @click="showAnswear">
                    Hint
                </button>
            </div>
            <div v-if="!isCompleted" class="question-section">
                <h1 class="question-header">Equation {{ questionIndex + 1 }}</h1>
                <h2 class="problem-name">
                    {{ currentQuestion[questionIndex].name }}
                </h2>
                <p class="text-danger" :class="{ 'show-error': showError.class }">{{ showError.value }}</p>

                <div v-for="(step, index) in currentQuestion" :key="index">
                    <div v-if="index === questionIndex">
                        <div class="step-container" v-for="(step, index) in currentQuestion[index].step" :key="index">
                            <div v-if="currentStep >= index" class="step-count">
                                <input :ref="setRefName(questionIndex, 'left', index)" type="text" @input="handleChange" />
                                <span>=</span>
                                <input :ref="setRefName(questionIndex, 'right', index)" @input="handleChange" />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="button-container">
                    <button v-if="!isNext" class="check-button button primary button-full font-size-small"
                        @click="handleAnswear">
                        Check
                    </button>
                    <button v-else class="check-button button primary button-full font-size-small" @click="handleNext">
                        Next
                    </button>
                    <button v-if="!isNext" class="skip-btn button secondary button-full font-size-small"
                        @click="handleSkip">
                        Skip
                    </button>
                </div>
            </div>
            <div v-else class="completed">
                <h1>Thank you for playing the game</h1>
                <p>Your Score is:</p>
                <h2 class="score-text">30</h2>
                <button class="button primary button-full font-size-small" @click="goHome">
                    Go home
                </button>
            </div>
        </div>
    </div>
</template>

<script>
import config from "../utils.js";
export default {
    name: "Game",
    data() {
        return {
            questionIndex: 0,
            currentStep: 0,
            sampleData: [],
            showError: {
                class: false,
                value: ''
            },
            isCompleted: false,
            timer: 0,
            showSkip: true,
            gameId: null,
            formattedTime: null,
            gameData: {
                gameID: this.gameId,
                questionID: null,
                randomTaps: 0,
                hint: 0,
                wrongTry: 0,
                correctTry: 0,
                skip: 0,
                time_spent: 0,
            },
            isNext: false,
            lastQuestionTime: 0,
        };
    },
    computed: {
        isLast() {
            console.log('index: ', this.questionIndex, "index", this.sampleData.length);

            if (this.sampleData.length === this.questionIndex + 1) {
                this.isNext = true;
                return true;
            } else {
                this.isNext = false;
                return false;

            }

        },
        isLastStep() {
            if (this.sampleData[this.questionIndex].step.length === this.currentStep + 1) {
                return true;
            } else {
                return false;
            }
        },
        remainingSeconds() {
            const endTimeString = localStorage.getItem('endTime');
            if (!endTimeString) return 0;

            const endTime = new Date(endTimeString);


            const currentTime = new Date();
            const remainingMilliseconds = Math.max(0, endTime - currentTime);
            const remainingSeconds = Math.floor(remainingMilliseconds / 1000);

            return remainingSeconds;
        },
        stepCount() {
            let stepCount = this.currentQuestion[this.questionIndex].step.length - 1;
            return stepCount;
        },
        currentQuestion() {
            return this.sampleData;
        },
        buttonText() {
            if (this.currentStep > this.stepCount) {
                return 'Next'
            } else {
                return "Check";
            }
        },
    },
    methods: {
        goHome(){
            this.$router.push('/home');
        },
        submit() {
            const token = localStorage.getItem("token");
            this.gameData.questionID = this.sampleData[this.questionIndex].id;
            this.gameData.time_spent = this.remainingSeconds;
            this.lastQuestionTime = this.timer;
            this.gameData.time_spent = 300 - this.lastQuestionTime;
            axios({
                method: "post",
                url: `${config.baseUrl}/api/game/result`,
                data: {
                    game_id: this.gameData.gameID,
                    question_id: this.gameData.questionID,
                    random_tap: this.gameData.randomTaps,
                    hint_used: this.gameData.hint,
                    wrong_try: this.gameData.wrongTry,
                    correct_try: this.gameData.correctTry,
                    skip: this.gameData.skip,
                    time_spent: this.gameData.time_spent,
                },
                headers: {
                    Authorization: `Bearer ${token}`,
                },

            }).then((res) => {
                this.gameData.questionID = null;
                this.gameData.randomTaps = 0;
                this.gameData.hint = 0;
                this.gameData.wrongTry = 0;
                this.gameData.correctTry = 0;
                this.gameData.skip = 0;
                this.gameData.time_spent = 0;
            });
        },
        randomTap(e) {
            if (e.target.tagName === 'BUTTON' || e.target.tagName === 'INPUT') {
                return;
            }
            this.gameData.randomTap++;
        },
        startTimer() {
            if (this.timer > 0) {
                return;
            }

            const intervalId = setInterval(() => {
                let minutes = Math.floor(this.timer / 60);
                let seconds = this.timer % 60;

                // Format the time as "m:ss"
                let formattedTime = `${minutes}:${seconds < 10 ? '0' : ''}${seconds}`;

                // Update the timer value with the formatted time
                this.formattedTime = formattedTime;

                this.timer--;

                if (this.timer <= 0) {
                    clearInterval(intervalId);
                }
            }, 1000);
        },
        setRefName(questionIndex, side, index) {
            return `${questionIndex}${side}${index}`;
        },
        showAnswear() {
            const currentStepLeft = this.setRefName(this.questionIndex, 'left', this.currentStep);
            const currentStepRight = this.setRefName(this.questionIndex, 'right', this.currentStep);
            let leftInput = this.$refs[currentStepLeft];
            let rightInput = this.$refs[currentStepRight];
            let currentStepData = this.currentQuestion[this.questionIndex].step[this.currentStep];

            leftInput[0].value = currentStepData.left;
            rightInput[0].value = currentStepData.right;
            this.gameData.hint++;
        },
        handleChange(e) {
            e.target.classList.remove("wrong-answer");
            this.showError.class = false;
            this.showError.value = '';
        },
        handleSkip() {
            if (this.isLast) {
                this.showSkip = false;
            } else {
                this.questionIndex++;
                this.gameData.skip++;
            }
        },
        handleNext() {
            this.submit()
            if (this.isLast) {
                this.isCompleted = true;

            } else {
                this.currentStep = 0;
                this.questionIndex++;
            }
        },
        handleAnswear() {
            const currentStepLeft = this.setRefName(this.questionIndex, 'left', this.currentStep);
            const currentStepRight = this.setRefName(this.questionIndex, 'right', this.currentStep);
            const leftInput = this.$refs[currentStepLeft];
            const rightInput = this.$refs[currentStepRight];
            let currentStepData = this.currentQuestion[this.questionIndex].step[this.currentStep];

            if (leftInput[0].value === null || leftInput[0].value === '' || rightInput[0].value === null || rightInput[0].value === '') {
                this.showError.class = true;
                this.showError.value = "Please complete your answer";
                leftInput[0].classList.add("wrong-answer", "shake");
                rightInput[0].classList.add("wrong-answer", "shake");
                setTimeout(() => {
                    leftInput[0].classList.remove("shake");
                    rightInput[0].classList.remove("shake");
                }, 500);
                this.gameData.wrongTry++;
                return;
            }
            if (leftInput[0].value === currentStepData.left && rightInput[0].value === currentStepData.right) {
                if (this.currentStep <= this.stepCount) {

                    leftInput[0].classList.add("is-done");
                    rightInput[0].classList.add("is-done");
                    if (this.isLastStep) {
                        this.isNext = true;
                    } else {
                        this.currentStep++;
                    }
                    this.gameData.correctTry++;
                }
            } else {
                this.showError.class = true;
                this.showError.value = "Please enter the right answer";
                leftInput[0].classList.add("wrong-answer", "shake");
                rightInput[0].classList.add("wrong-answer", "shake");
                this.gameData.wrongTry++;

                setTimeout(() => {
                    leftInput[0].classList.remove("shake");
                    rightInput[0].classList.remove("shake");
                }, 500);
            }



        }
    },
    created() {
        let questionsFromLocalStorage = JSON.parse(localStorage.getItem('question'));

        if (questionsFromLocalStorage) {

            questionsFromLocalStorage.forEach(question => {
                question.skip = false;
            });
            this.sampleData = questionsFromLocalStorage;
        }
        this.timer = this.remainingSeconds;
        this.formattedTime = this.timer;
        this.gameId = localStorage.getItem('game_id');
        this.gameData.gameID = this.gameId;

        this.startTimer();
        console.log(this.timer);
        console.log(this.sampleData);
    },
};
</script>

<style lang="scss" scoped>
.game-container {
    width: 100%;
    height: 100vh;
    margin: 0;
    background: $pale-cream;

    .completed{
        margin-top: 60px;
        text-align: center;

        h1{
            margin-bottom: 10px;
            margin-top: 20px;
            color:$light-red;
        }

        p {
            font-size: $font-medium;
        }
        .score-text {
            font-size: $font-large;
            color:$light-red;
            margin-bottom: 20px;
        }
    }

    .text-danger {
        color: red;
        text-align: center;
        height: 10px;
        padding-bottom: 20px;
    }

    .game-information {
        padding: 0 30px 30px;
        max-width: 500px;
        margin: 0 auto;
        height: 100%;
        position: relative;

        .question-header {
            font-size: $font-large;
            color: $light-red;
            text-align: center;
            margin-bottom: 2rem;
        }

        .problem-name {
            font-size: $font-medium;
            color: $light-red;
            text-align: center;
            margin-bottom: 20px;
            line-height: 20px;
        }

        .check-button {
            margin-top: auto;
        }

        .header-game {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-top: 20px;
            margin-bottom: 30px;

            button {
                padding: 10px 20px;
            }

            .time {
                font-size: $font-medium;
                color: $light-red;
                text-align: center;
            }
        }

        .button-container {
            display: flex;
            gap: 20px;
            flex-direction: row;
            margin-top: 30px;

            button:first-child {
                flex: 80%;
            }

            button:last-child {
                flex: 20%;
            }
        }

        .step-container {
            box-sizing: border-box;

            .step-count {
                display: flex;
                flex-direction: row;
                gap: 10px;
                width: 100%;
            }

            .is-done {
                background-color: $pale-cream ;
                color: $light-red;
            }

            span {
                font-size: $font-medium;
                color: $light-red;
            }

            input {
                width: 50%;
                height: 40px;
                background: $white;
                border: none;
                outline: 3px $light-red solid;
                border-radius: 10px;
                padding: 0 10px;
                font-family: $font-family-sans-serif;
                font-size: 1.7rem;
                margin-bottom: 20px;
                text-align: center;

                &.wrong-answer {
                    outline: 3px $red solid;
                }

                @keyframes shake {
                    0% {
                        transform: translateX(0);
                    }

                    25% {
                        transform: translateX(-5px);
                    }

                    50% {
                        transform: translateX(5px);
                    }

                    75% {
                        transform: translateX(-3px);
                    }

                    100% {
                        transform: translateX(0);
                    }
                }

                &.shake {
                    animation: shake 0.5s ease;
                }
            }
        }
    }
}
</style>
