<template>
    <div class="game-container">
        <div class="game-information">
            <div class="header-game">
                <button class=" button primary  font-size-small">
                    Menu
                </button>
                <div class="time-container">
                    <p class="time">60</p>
                </div>
                <button class="button primary  font-size-small">
                    Help
                </button>
            </div>
            <h1 class="question-header">Equation {{ questionIndex + 1 }}</h1>
            <h2 class="problem-name">
                {{ currentQuestion[questionIndex].name }}
            </h2>

            <div v-for="(step, index) in currentQuestion" :key="index">
                <div v-if="index === questionIndex">
                    <div class="step-container" v-for="(step, index) in currentQuestion[index].step" :key="index">
                        <div v-if="currentStep == index || isDone(index) === true" class="step-count"
                            :class="{ 'is-done': isDone(index) }">
                            <input :ref="setRefName(questionIndex, 'left', index)" type="text" />
                            <span>=</span>
                            <input :ref="setRefName(questionIndex, 'right', index)" />
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
            sampleData: [
                {
                    questions: [
                        {
                            id: 1,
                            name: "3x+5=0",
                            level_id: 1,
                            created_at: "2024-01-24T15:46:09.000000Z",
                            updated_at: "2024-01-24T15:46:09.000000Z",
                            level: {
                                id: 1,
                                created_at: "2024-01-24T15:39:53.000000Z",
                                updated_at: "2024-01-24T15:43:35.000000Z",
                                name: "Easy",
                            },
                            step: [
                                {
                                    id: 1,
                                    left: "3x",
                                    right: "-5",
                                    question_id: 1,
                                    created_at: "2024-01-24T18:45:10.000000Z",
                                    updated_at: "2024-01-24T18:45:10.000000Z",
                                },
                                {
                                    id: 2,
                                    left: "3x ÷ 3",
                                    right: "5÷ 3",
                                    question_id: 1,
                                    created_at: "2024-01-24T18:48:11.000000Z",
                                    updated_at: "2024-01-24T18:48:40.000000Z",
                                },
                                {
                                    id: 3,
                                    left: "x",
                                    right: "5 / 3",
                                    question_id: 1,
                                    created_at: "2024-01-24T18:48:59.000000Z",
                                    updated_at: "2024-01-24T18:48:59.000000Z",
                                },
                            ],
                        },
                        {
                            id: 2,
                            name: "y+7x=3",
                            level_id: 2,
                            created_at: "2024-01-24T15:47:24.000000Z",
                            updated_at: "2024-01-24T15:47:24.000000Z",
                            level: {
                                id: 2,
                                created_at: "2024-01-24T15:43:41.000000Z",
                                updated_at: "2024-01-24T15:43:41.000000Z",
                                name: "Medium",
                            },
                            step: [
                                {
                                    id: 1,
                                    left: "3x",
                                    right: "-5",
                                    question_id: 1,
                                    created_at: "2024-01-24T18:45:10.000000Z",
                                    updated_at: "2024-01-24T18:45:10.000000Z",
                                },
                                {
                                    id: 2,
                                    left: "3x ÷ 3",
                                    right: "5÷ 3",
                                    question_id: 1,
                                    created_at: "2024-01-24T18:48:11.000000Z",
                                    updated_at: "2024-01-24T18:48:40.000000Z",
                                },
                                {
                                    id: 3,
                                    left: "x",
                                    right: "5 / 3",
                                    question_id: 1,
                                    created_at: "2024-01-24T18:48:59.000000Z",
                                    updated_at: "2024-01-24T18:48:59.000000Z",
                                },
                            ],
                        },
                        {
                            id: 3,
                            name: "x + y + z = 0",
                            level_id: 3,
                            created_at: "2024-01-24T15:48:07.000000Z",
                            updated_at: "2024-01-24T15:48:07.000000Z",
                            level: {
                                id: 3,
                                created_at: "2024-01-24T15:43:46.000000Z",
                                updated_at: "2024-01-24T15:43:46.000000Z",
                                name: "Hard",
                            },
                            step: [
                                {
                                    id: 1,
                                    left: "3x",
                                    right: "-5",
                                    question_id: 1,
                                    created_at: "2024-01-24T18:45:10.000000Z",
                                    updated_at: "2024-01-24T18:45:10.000000Z",
                                },
                                {
                                    id: 2,
                                    left: "3x ÷ 3",
                                    right: "5÷ 3",
                                    question_id: 1,
                                    created_at: "2024-01-24T18:48:11.000000Z",
                                    updated_at: "2024-01-24T18:48:40.000000Z",
                                },
                                {
                                    id: 3,
                                    left: "x",
                                    right: "5 / 3",
                                    question_id: 1,
                                    created_at: "2024-01-24T18:48:59.000000Z",
                                    updated_at: "2024-01-24T18:48:59.000000Z",
                                },
                            ],
                        },
                    ],
                },
            ],
        };
    },
    computed: {
        stepCount() {
            let stepCount = this.currentQuestion[this.questionIndex].step.length - 1;
            return stepCount;
        },
        currentQuestion() {
            return this.sampleData[0].questions;
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
        handleAnswear() {
            if (this.currentStep === this.stepCount) {
                this.questionIndex++;
                this.currentStep = 0;
                return;
            }

            const currentStepLeft = this.setRefName(this.questionIndex, 'left', this.currentStep);
            const currentStepRight = this.setRefName(this.questionIndex, 'right', this.currentStep);
            const leftInput = this.$refs[currentStepLeft][0].value;
            const rightInput = this.$refs[currentStepRight][0].value;
            let currentStepData = this.currentQuestion[this.questionIndex].step[this.currentStep];

            if (leftInput === null || leftInput === '' || rightInput === null || rightInput === '') {
                return;
            }
            if (leftInput === currentStepData.left && rightInput === currentStepData.right) {
                if (this.currentStep <= this.stepCount) {
                    this.currentStep++;
                    console.log(this.currentStep);
                }
            }

        }
    }
};
</script>

<style lang="scss" scoped>
.game-container {
    width: 100%;
    height: 100vh;
    margin: 0;
    background: $pale-cream;

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
            margin-bottom: 30px;
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
            }
        }
    }
}
</style>
