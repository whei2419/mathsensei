<template>
    <div class="game-container">
        <div class="game-information">
            <div class="header-game">
                <button class=" button primary  font-size-small">
                    Menu
                </button>
                <div class="time-container">
                    <p class="time">{{ timer }}</p>
                </div>
                <button class="button primary  font-size-small">
                    Help
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
                        <div v-if="currentStep == index || isDone(index) === true" class="step-count"
                            :class="{ 'is-done': isDone(index) }">
                            <input :ref="setRefName(questionIndex, 'left', index)" type="text" @input="handleChange" />
                            <span>=</span>
                            <input :ref="setRefName(questionIndex, 'right', index)" @input="handleChange" />
                        </div>

                    </div>
                </div>
            </div>

            <button class="check-button button primary button-full font-size-small" @click="handleAnswear">
                {{ buttonText }}
            </button>
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
        };
    },
    computed: {
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
        startTimer() {
            const intervalId = setInterval(() => {
                this.timer--;
                if (this.timer <= 0) {
                    clearInterval(intervalId);
                }
            }, 1000);
        },
        isDone(index) {
            if (this.currentStep > index) {
                return true;
            } else {
                return false;
            }
        },
        setRefName(questionIndex, side, index) {
            return `${questionIndex}${side}${index}`;
        },
        showAnswear() { },
        handleChange(e) {
            e.target.classList.remove("wrong-answer");
            this.showError.class = false;
            this.showError.value = '';
        },
        handleAnswear() {
            if (this.currentStep === this.stepCount) {
                this.questionIndex++;
                this.currentStep = 0;
                return;
            }

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
                return;
            }
            if (leftInput[0].value === currentStepData.left && rightInput[0].value === currentStepData.right) {
                if (this.currentStep <= this.stepCount) {
                    this.currentStep++;
                    console.log(this.currentStep);
                }
            } else {
                this.showError.class = true;
                this.showError.value = "Please enter the right answer";
                leftInput[0].classList.add("wrong-answer", "shake");
                rightInput[0].classList.add("wrong-answer", "shake");

                setTimeout(() => {
                    leftInput[0].classList.remove("shake");
                    rightInput[0].classList.remove("shake");
                }, 500);
            }

        }
    },
    created() {
        this.sampleData = JSON.parse(localStorage.getItem('question'));
        this.timer = this.remainingSeconds;
        this.startTimer();
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

        .step-container {
            box-sizing: border-box;

            .step-count {
                display: flex;
                flex-direction: row;
                gap: 10px;
                width: 100%;
            }

            .is-done {
                input {
                    background-color: $pale-cream ;
                    color: $light-red;
                }
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
