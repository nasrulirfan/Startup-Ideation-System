
<template>
  <div class="container">
    <div class="select-box">
      <div class="options-container" :class="{ active: selectedActive }">
        <div v-for="(option, index) in optionsValue" :key="index">
          <div class="option" @click="handleOptions(option)">
            <input type="radio" class="radio" name="category" />
            <label :for="option">{{ option }}</label>
          </div>
        </div>
      </div>
      <div
        class="selected"
        v-bind:value="selectedValue"
        @click="toggleSelected"
      >
        {{ selectedValue }}
      </div>
    </div>
  </div>
</template>
  
<script>
export default {
  props: {
    optionsValue: {
      type: Array,
    },
    passedValue: {
      default: "Choose Options",
      required: false,
    }
  },
  data() {
    return {
      selectedValue: this.passedValue,
      selectedActive: false,
    };
  },
  mounted(){
    if (this.selectedValue === "" || this.selectedValue === null){
      this.selectedValue = "Choose Options"
    }
  },
  methods: {
    toggleSelected() {
      this.selectedActive = !this.selectedActive;
    },
    handleOptions(value) {
      this.toggleSelected();
      this.selectedValue = value;
      this.$emit("clicked", this.selectedValue);
    },
  },
};
</script>

<style scoped lang = 'scss'>
.select-box {
  display: flex;
  width: 400px;
  flex-direction: column;
}

.select-box .options-container {
  background: #2f3640;
  color: #f5f6fa;
  max-height: 0;
  width: 100%;
  opacity: 0;
  transition: all 0.4s;
  border-radius: 8px;
  overflow: hidden;
  order: 1;
  transition: all 0.3s ease 0s;
  box-shadow: 0px 8px 15px rgb(0 0 0 / 21%);
  &:hover {
    transform: translateY(-7px);
  }
}

.selected {
  background: #2f3640;
  border-radius: 8px;
  margin-bottom: 8px;
  color: #f5f6fa;
  position: relative;
  order: 0;
}

.selected::after {
  content: "";
  background: url("../assets/img/arrow-down.svg");
  background-size: contain;
  background-repeat: no-repeat;

  position: absolute;
  height: 100%;
  width: 32px;
  right: 10px;
  top: 5px;

  transition: all 0.4s;
}

.select-box .options-container.active {
  max-height: 240px;
  opacity: 1;
  overflow-y: scroll;
}

.select-box .options-container.active + .selected::after {
  transform: rotateX(180deg);
  top: -6px;
}

.select-box .options-container::-webkit-scrollbar {
  width: 8px;
  background: #0d141f;
  border-radius: 0 8px 8px 0;
}

.select-box .options-container::-webkit-scrollbar-thumb {
  background: #525861;
  border-radius: 0 8px 8px 0;
}

.select-box .option,
.selected {
  padding: 12px 24px;
  cursor: pointer;
}

.select-box .option:hover {
  background: #414b57;
}

.select-box label {
  cursor: pointer;
}

.select-box .option .radio {
  display: none;
}
</style>

        