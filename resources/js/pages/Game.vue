<template>
  <div id="mainDiv" class="game-container" @click="randomTap">
    <particles></particles>
    <div class="game-information">
      <div v-if="!nextView" class="header-game">
        <div v-if="!isCompleted" class="time-container">
          <img class="time-clock" src="image/clock.svg" alt="" />
          <p class="time">{{ formattedTime }}</p>
        </div>
        <button
          v-if="!isCompleted"
          class="hint-btn button primary font-size-small"
          @click="handleHint"
        >
          <span style="background-color: #2196f3" class="indicator"></span
          ><span>Hint</span> <img src="image/bulb.svg" alt="" />
        </button>
      </div>
      <div v-if="!isCompleted && !nextView" class="question-section">
        <h1 class="question-header">Equation {{ questionIndex + 1 }}</h1>
        <h2 class="problem-name">
          {{ currentQuestion[questionIndex].question_text }}
        </h2>
        <p class="text-danger" :class="{ 'show-error': showError.class }">
          {{ showError.value }}
        </p>

        <div v-if="isFirstInput" class="step-container">
          <div class="step-count">
            <input
              ref="left"
              v-model="firstLeft"
              type="text"
              @input="handleChange"
            />
            <span>=</span>
            <input ref="right" v-model="firstRight" @input="handleChange" />
          </div>
        </div>

        <div v-for="(step, index) in currentQuestion" :key="index">
          <div v-if="index === questionIndex">
            <div
              class="step-container"
              v-for="(step, index) in currentQuestion[index].solutions[
                solutionKey
              ]"
              :key="index"
            >
              <div v-if="currentStep >= index" class="step-count">
                <input
                  :ref="setRefName(questionIndex, 'left', index)"
                  type="text"
                  @input="handleChange"
                />
                <span>=</span>
                <input
                  :ref="setRefName(questionIndex, 'right', index)"
                  @input="handleChange"
                />
              </div>
            </div>
          </div>
        </div>
        <div class="button-container">
          <button
            class="check-button button primary button-full font-size-small"
            @click="handleAnswear"
          >
            Check
          </button>
          <button
            class="skip-btn button secondary button-full font-size-small"
            @click="handleSkip"
          >
            Skip
          </button>
        </div>
      </div>
      <div v-if="nextView && !isCompleted" class="question-complete completed">
        <h1>You completed a question would you like to go to the next</h1>
        <Vue3Lottie :animationData="checkAnimation" :height="200" :width="200" />
        <!-- <span class="check-icon"><i class="fa-solid fa-circle-check"></i></span> -->
            <button
            class="check-button button secondary button-full font-size-small"
            @click="goToNext"
          >
           <span>Next</span>  <span><i class="fa-solid fa-arrow-right ml-1"></i></span>
          </button>
      </div>
      <div v-if="isCompleted" class="completed">
        <h1>Thank you for playing the game</h1>
        <p>Your Score is:</p>
        <h2 class="score-text">{{ score }}</h2>
        <div class="button-container">
          <button
            class="button primary button-full font-size-small"
            @click="goHome"
          >
            Return home
          </button>
          <button
            class="button success button-sm font-size-small"
            @click="restart"
          >
            <i class="fa-solid fa-arrow-rotate-right"></i>
          </button>
        </div>
      </div>

    </div>
    <Modal
      v-if="isOpen"
      :title="title"
      :close="false"
      :confirm="false"
      @close="handleClose"
      :message="modalMessage"
    ></Modal>
    <Modal
      v-if="timerModal"
      :title="timerTitle"
      @save="handleAddTime"
      @close="timerClose"
      :message="timerMessage"
    ></Modal>
  </div>
</template>

<script>
import moment from "moment";
import config from "../utils.js";
import particles from "../layouts/Particle.vue";
import Modal from "../layouts/Modal.vue";
import lottie from 'lottie-web';
import animationData from '../../../public/image/check.json';
import { Vue3Lottie } from "vue3-lottie";


export default {
  name: "Game",
  components: {
    particles,
    Modal,
    Vue3Lottie

  },
  data() {
    return {
    nextView:false,
      sequence: [],
      timelimit: 60,
      timerModal: false,
      timerTitle: "Time ending",
      timerMessage:
        "Your time is running out do you want to  extend your timer +15 sec",
      title: "Hint",
      isOpen: false,
      modalMessage: "",
      isFirstInput: true,
      firstLeft: null,
      firstRight: null,
      questionIndex: 0,
      currentStep: 0,
      sampleData: [],
      showError: {
        class: false,
        value: "",
      },
      isCompleted: false,
      timer: 60,
      showSkip: true,
      gameId: null,
      formattedTime: null,
      gameData: {
        gameID: this.gameId,
        questionID: null,
        randomTaps: 0,
        hint: [],
        wrongTry: 0,
        correctTry: 0,
        skip: 0,
        time_spent: 0,
        next: 0,
        extraTime: 0,
      },
      score: 0,
      timeSpent: 0,
      additionalTimeCount: 0,
      isNext: false,
      lastQuestionTime: 0,
      solutionKey: null,
      stepCounter: [],
      intervalId: null,
      checkAnimation:null
    };
  },
  mounted(){
    this.checkAnimation = animationData;
  },
  computed: {
    isLast() {
      if (this.sampleData.length === this.questionIndex + 1) {
        this.isNext = true;
        return true;
      } else {
        this.isNext = false;
        return false;
      }
    },
    isLastStep() {
      if (
        this.currentQuestion[this.questionIndex].solutions[this.solutionKey]
          .length ===
        this.currentStep + 1
      ) {
        return true;
      } else {
        return false;
      }
    },
    remainingSeconds() {
      const endTimeString = localStorage.getItem("endTime");
      if (!endTimeString) return 0;

      const endTime = new Date(endTimeString);

      const currentTime = new Date();
      const remainingMilliseconds = Math.max(0, endTime - currentTime);
      const remainingSeconds = Math.floor(remainingMilliseconds / 1000);

      return remainingSeconds;
    },
    stepCount() {
      let stepCount =
        this.currentQuestion[this.questionIndex].solutions[this.solutionKey]
          .length - 1;
      return stepCount;
    },
    currentQuestion() {
      return this.sampleData;
    },
    buttonText() {
      if (this.currentStep > this.stepCount) {
        return "Next";
      } else {
        return "Check";
      }
    },
  },
  methods: {
    logSequence(name) {
      const time = moment().format("YYYY-MM-DD HH:mm:ss");
      this.sequence.push({ action: name, time: time });
    },
    restart() {
      this.isNext = false;
      this.isCompleted = false;
      this.currentStep = 0;
      this.questionIndex = 0;
      this.firstLeft = null;
      this.firstRight = null;
      this.solutionKey = null;
      this.isFirstInput = true;
      this.timer = this.timelimit;
      this.startTimer();
    },
    handleAddTime() {
        this.logSequence("add-time",);
      this.additionalTimeCount = this.additionalTimeCount + 1;
      this.timer = this.timer + 15;
      this.timerModal = false;
      this.startTimer();
    },
    timerClose() {
      this.handleSkip();
      this.timerModal = false;
    },
    handleHint() {
      this.logSequence("hint");
      this.gameData.hint = this.gameData.hint + 1;
      const existingStepIndex = this.stepCounter.findIndex(
        (item) => item.step === this.currentStep
      );
      if (existingStepIndex !== -1) {
        this.stepCounter[existingStepIndex].count =
          (this.stepCounter[existingStepIndex].count || 1) + 1;
      } else {
        this.stepCounter.push({ step: this.currentStep, count: 1 });
      }
      if (this.currentStep === 0) {
        this.modalMessage =
          this.currentQuestion[this.questionIndex].solutions["1"][0].text;
        this.isOpen = true;
        return;
      }
      this.modalMessage =
        this.currentQuestion[this.questionIndex].solutions[this.solutionKey][
          this.currentStep
        ].text;
      this.isOpen = true;
    },
    handleClose() {
        this.logSequence("close-modal");
      this.isOpen = false;
    },
    clearData() {
      localStorage.removeItem("startTime");
      localStorage.removeItem("endTime");
      localStorage.removeItem("game_id");
      localStorage.removeItem("question");
      localStorage.removeItem("isStart");
    },
    goHome() {
      this.clearData();
      this.$router.push("/home");
    },
    getResult() {
      const token = localStorage.getItem("token");
      const gameID = localStorage.getItem("game_id");
      axios({
        method: "post",
        url: `${config.baseUrl}/api/game/score`,
        data: {
          game_id: gameID,
        },
        headers: {
          Authorization: `Bearer ${token}`,
        },
      }).then((res) => {
        this.score = res.data.score;
        this.isCompleted = true;
      });
    },
    countHintPerStep() {},
    submit(lastQuestion = false) {
      const token = localStorage.getItem("token");
      this.gameData.questionID = this.sampleData[this.questionIndex].id;
      this.gameData.time_spent = this.remainingSeconds;
      this.lastQuestionTime = this.timer;
      this.gameData.time_spent = this.timeSpent - 1;
      this.gameData.hint = this.stepCounter;
      this.gameData.extraTime = this.additionalTimeCount;
      axios({
        method: "post",
        url: `${config.baseUrl}/api/game/result`,
        data: {
          next: this.gameData.next,
          extra_time: this.gameData.extraTime,

          game_id: this.gameData.gameID,
          question_id: this.gameData.questionID,
          random_tap: this.gameData.randomTaps,
          hint_used: JSON.stringify(this.gameData.hint),
          wrong_try: this.gameData.wrongTry,
          correct_try: this.gameData.correctTry,
          skip: this.gameData.skip,
          time_spent: this.gameData.time_spent,
        },
        headers: {
          Authorization: `Bearer ${token}`,
        },
      }).then((res) => {
        this.gameData.next = 0;
        this.gameData.extraTime = 0;
        this.gameData.questionID = null;
        this.gameData.randomTaps = 0;
        this.gameData.hint = [];
        this.gameData.wrongTry = 0;
        this.gameData.correctTry = 0;
        this.gameData.skip = 0;
        this.gameData.time_spent = 0;
        this.timeSpent = 0;

        this.stopTimer();
        this.startTimer();

        if (this.isLast && lastQuestion) {
          this.stopTimer();
          this.getResult();
        }
      });
    },
    randomTap(e) {
      const div = document.getElementById("mainDiv");
      if (e.target.tagName === "BUTTON" || e.target.tagName === "INPUT") {
        return;
      }

      if (!div.contains(e.target)) {
        return;
      }

      this.logSequence("random");
      this.gameData.randomTaps++;
    },

    startTimer() {
      if (!this.timer === 0) {
        return;
      }

      this.intervalId = setInterval(() => {
        let minutes = Math.floor(this.timer / 60);
        let seconds = this.timer % 60;

        // Format the time as "m:ss"
        let formattedTime = `${minutes}:${seconds < 10 ? "0" : ""}${seconds}`;

        // Update the timer value with the formatted time
        this.formattedTime = formattedTime;
        if (this.timer < 30) {
          let text = document.querySelector(".time-container");

          if (text.classList.contains("grow")) {
          } else {
            text.classList.add("grow");
          }
        }

        if (this.timer === 0) {
          this.stopTimer();
          this.timerModal = true;
        }

        this.timer--;
        this.timeSpent++;

        if (this.timer < 0) {
          clearInterval(this.intervalId);
        }
      }, 1000);
    },
    stopTimer() {
      clearInterval(this.intervalId);
      this.intervalId = null;
    },
    setRefName(questionIndex, side, index) {
      return `${questionIndex}${side}${index}`;
    },
    showAnswear() {
      const currentStepLeft = this.setRefName(
        this.questionIndex,
        "left",
        this.currentStep
      );
      const currentStepRight = this.setRefName(
        this.questionIndex,
        "right",
        this.currentStep
      );

      if (this.solutionKey === null) {
        const firstLeftInput = this.$refs.left;
        const firstRightInput = this.$refs.right;
        let currentStepData =
          this.currentQuestion[this.questionIndex].solutions[1][
            this.currentStep
          ];
        firstLeftInput.value = currentStepData.left;
        firstRightInput.value = currentStepData.right;
        this.firstLeft = currentStepData.left;
        this.firstRight = currentStepData.right;
      } else {
        let currentStepData =
          this.currentQuestion[this.questionIndex].solutions[this.solutionKey][
            this.currentStep
          ];
        this.$nextTick(() => {
          let leftInput = this.$refs[currentStepLeft];
          let rightInput = this.$refs[currentStepRight];

          leftInput[0].value = currentStepData.left;
          rightInput[0].value = currentStepData.right;
          this.gameData.hint++;
        });
      }
    },
    handleChange(e) {
      this.logSequence("typing");
      e.target.classList.remove("wrong-answer");
      this.showError.class = false;
      this.showError.value = "";
    },
    goToNext(){
        this.logSequence("next");
        this.timer = this.timelimit;
        this.questionIndex++;
        this.stopTimer();
        this.startTimer();
        this.currentStep = 0;
        this.firstLeft = null;
        this.firstRight = null;
        this.solutionKey = null;
        this.isFirstInput = true;
        this.timer = this.timelimit;
        this.nextView = false;
    },
    handleSkip() {
        this.gameData.skip++;
        this.logSequence("skip");
        this.stopTimer();
         if(this.isLast){
            this.submit(true);
            this.nextView = false;
         }else {
            this.submit();
            this.nextView = true;
         }
    },
    handleNext() {
      this.logSequence("next");
      this.gameData.next = this.gameData.next + 1;
      this.submit();
      if (this.isLast) {
      } else {
        this.currentStep = 0;
        this.questionIndex++;
        this.firstLeft = null;
        this.firstRight = null;
        this.solutionKey = null;
        this.isFirstInput = true;
        this.timer = this.timelimit;
      }
    },
    checkSolutionKey() {
      const firstLeftInput = this.$refs.left;
      const firstRightInput = this.$refs.right;

      let solution = this.sampleData[this.questionIndex].solutions;
      let filteredKey = null;

      for (let key in solution) {
        if (solution.hasOwnProperty(key)) {
          if (
            solution[key].some(
              (obj) =>
                obj.left === this.firstLeft && obj.right === this.firstRight
            )
          ) {
            filteredKey = key;
            break;
          }
        }
      }

      if (
        firstLeftInput.value === null ||
        firstLeftInput.value === "" ||
        firstRightInput.value === null ||
        firstRightInput.value === ""
      ) {
        this.showError.class = true;
        this.showError.value = "Please complete your answer";
        firstLeftInput.classList.add("wrong-answer", "shake");
        firstRightInput.classList.add("wrong-answer", "shake");
        setTimeout(() => {
          firstLeftInput.classList.remove("shake");
          firstRightInput.classList.remove("shake");
        }, 500);
        this.gameData.wrongTry++;
        return;
      } else if (filteredKey == null) {
        this.showError.class = true;
        this.showError.value = "Please input the correct answer";
        firstLeftInput.classList.add("wrong-answer", "shake");
        firstRightInput.classList.add("wrong-answer", "shake");
        setTimeout(() => {
          firstLeftInput.classList.remove("shake");
          firstRightInput.classList.remove("shake");
        }, 500);
        this.gameData.wrongTry++;
        return;
      } else {
        this.isFirstInput = false;
        let currentStepData =
          this.currentQuestion[this.questionIndex].solutions[filteredKey][
            this.currentStep
          ];
        this.solutionKey = filteredKey;
        const currentStepLeft = this.setRefName(this.questionIndex, "left", 0);
        const currentStepRight = this.setRefName(
          this.questionIndex,
          "right",
          0
        );

        this.$nextTick(() => {
          const leftInput = this.$refs[currentStepLeft];
          const rightInput = this.$refs[currentStepRight];
          leftInput[0].value = currentStepData.left;
          rightInput[0].value = currentStepData.right;
          leftInput[0].classList.add("is-done");
          rightInput[0].classList.add("is-done");
          if(this.isLastStep){
            this.nextView = true;
            return;
          }
          this.currentStep++;
        });
      }
    },

    handleAnswear() {
      this.logSequence("check-asnwear");
      if (this.currentStep === 0) {
        this.checkSolutionKey();
        return;
      }
      const currentStepLeft = this.setRefName(
        this.questionIndex,
        "left",
        this.currentStep
      );
      const currentStepRight = this.setRefName(
        this.questionIndex,
        "right",
        this.currentStep
      );
      const leftInput = this.$refs[currentStepLeft];
      const rightInput = this.$refs[currentStepRight];
      let currentStepData =
        this.currentQuestion[this.questionIndex].solutions[this.solutionKey][
          this.currentStep
        ];

      if (
        leftInput[0].value === null ||
        leftInput[0].value === "" ||
        rightInput[0].value === null ||
        rightInput[0].value === ""
      ) {
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
      console.log(currentStepData.right,currentStepData.left);
      if (
        leftInput[0].value === currentStepData.left &&
        rightInput[0].value === currentStepData.right
      ) {

        if (this.currentStep <= this.stepCount) {
          leftInput[0].classList.add("is-done");
          rightInput[0].classList.add("is-done");

          if (this.isLastStep) {
            if(this.isLast) {
              this.submit(true);
              return
            }
            this.nextView = true;
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
    },
  },
  created() {
    let questionsFromLocalStorage = JSON.parse(
      localStorage.getItem("question")
    );

    if (questionsFromLocalStorage) {
      questionsFromLocalStorage.forEach((question) => {
        question.skip = false;
      });
      this.sampleData = questionsFromLocalStorage;
    }
    this.gameId = localStorage.getItem("game_id");
    this.gameData.gameID = this.gameId;

    this.startTimer();
  },
};
</script>

<style lang="scss" scoped>
.game-container {
  width: 100%;
  height: 100vh;
  padding: 30px 10px;
  margin: 0;
  background: $pale-cream;
  box-sizing: border-box;

  .hint-btn {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 10px;
  }

  .time-container {
    display: flex;
    justify-content: flex-start;
    align-items: center;
    gap: 5px;

    &.grow {
      animation: growAndPulse 1s infinite ease-in-out;
    }
  }

  .completed {
    margin-top: 20px;
    text-align: center;

    h1 {
      margin-bottom: 10px;
      margin-top: 20px;
      color: $light-red;
    }

    p {
      font-size: $font-medium;
    }

    .score-text {
      font-size: $font-large;
      color: $light-red;
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
    position: relative;
    box-sizing: border-box;
    box-shadow: rgba(60, 64, 67, 0.3) 0px 1px 2px 0px,
      rgba(60, 64, 67, 0.15) 0px 2px 6px 2px;
    border: solid 3px $light-red;
    border-radius: 10px;

    .question-header {
      font-size: $font-large;
      color: $light-red;
      text-align: center;
      margin-bottom: 2rem;
      position: relative;

      span {
        width: 30px;
        height: 30px;
        position: absolute;
        top: 0;
        left: 0;
      }
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
        color: $red;
        text-align: center;
      }

      @keyframes growAndPulse {
        0% {
          transform: scale(1);
          opacity: 1;
        }

        50% {
          transform: scale(1.2);
          opacity: 0.8;
        }

        100% {
          transform: scale(1);
          opacity: 1;
        }
      }
    }

    .button-container {
      display: flex;
      gap: 20px;
      flex-direction: row;
      margin-top: 50px;

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
        background-color: $pale-cream;
        color: $light-red;
        pointer-events: none;
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
.indicator {
  width: 10px;
  height: 10px;
  border-radius: 20px;
}
.question-complete {
    h1 {
        margin-top: 40px !important;
        margin-bottom: 10px !important;
    }
    .check-icon {
        font-size: 5rem;
        color:$green ;
    }
    .button {
        margin-top: 20px !important;
    }
}
</style>
