<template>
    <div class="game-container" @click="randomTap">
        <div class="game-information">
            <div class="header-game">
                <button class=" button primary  font-size-small">
                    Menu
                </button>
                <div class="time-container">
                    <p class="time">{{ timer }}</p>
                </div>
                <button class="button primary  font-size-small" @click="showAnswear">
                    Hint
                </button>
            </div>
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
                <button v-if="!isNext" class="skip-btn button secondary button-full font-size-small" @click="handleSkip">
                    Skip
                </button>
            </div>

        </div>
    </div>
</template>

<script>
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
            timer: 0,
            showSkip: true,
            gameData: {
                gameID: null,
                questionID: null,
                randomTap: null,
                hint: null,
                wrongTry: null,
                correctTry: null,
                skip: null,
                time_spent: null,
            },
            isNext: false
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
        randomTap(e) {
            if (e.target.tagName === 'BUTTON' || e.target.tagName === 'INPUT') {
                return;
            }
            this.gameData.randomTap++;
        },
        startTimer() {
            if (this.timer === 0) {
                return;
            }
            const intervalId = setInterval(() => {
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
            if (this.isLast) {

            } else {
                this.currentStep = 0;
                this.questionIndex++;
            }

            console.log(this.gameData);
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
        this.startTimer();
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
