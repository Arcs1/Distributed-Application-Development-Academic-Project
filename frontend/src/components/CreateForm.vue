<script>
export default {
  data() {
    return {
      selectedOption: '',
      previewImage: null,
      image: null,


    }
  },
  emits: ["createItem"],
  props: {
    selected: {
      type: String,
      default: ""
    },
    formItems: {
      type: Array,
      default: () => []
    },
    selectItems: {
      type: Array,
      default: () => []
    },
    isSelectedRequired: {
      type: Boolean,
      default: false
    },
    options: {
      type: Array,
      default: () => []
    },
    isFileUploadRequired: {
      type: Boolean,
      default: false
    },
    title: {
      type: String,
      default: ""
    },
    isTextAreaRequired: {
      type: Boolean,
      default: false
    },
    textAreaItems: {
      type: Array,
      default: () => []
    }
  },
  methods: {
    createItem(array) {
      array = this.formItems
      array.push(this.selectedOption)
      array.push(this.image)
      array.push(this.textAreaItems)

      console.log(array)
      this.$emit("createItem", array);

    }, uploadImage(e) {
      this.image = e.target.files[0];
      let reader = new FileReader();
      reader.readAsDataURL(this.image)
      reader.onload = e => {
        this.previewImage = e.target.result;
      }
    },

  },

}
</script>


<template>
  <div class="form-content">
    <h1>{{ title }}</h1>
    <form>
      <div class="form-group" v-for="item in formItems" :key="item">
        <label for="exampleFormControlInput1">{{ item.label }}</label>
        <input :type="item.type" class="form-control" id="exampleFormControlInput1" v-model="item.value" required>
      </div>
      <div v-if="isSelectedRequired" class="form-group">
        <label v-for="item in selectItems" :key="item" for="exampleFormControlInput1">{{ item.label }}</label>
        <select id="selectedItem" name="selectedItem" v-model="this.selectedOption">
          <option v-for="item in options" :key="item" :value="{ text: item.value }" :selected="selected">{{ item.option }}
          </option>
        </select>
      </div>

      <div v-if="isFileUploadRequired" class="form-group">
        <form method="POST" enctype="multipart/form-data">
          <label>Foto</label>
          <input type="file" @change="uploadImage" id="myFile" name="image">
          <div v-if="previewImage">
            <img :src="previewImage" width="100" height="100" />
          </div>
        </form>
      </div>
      <div v-if="isTextAreaRequired">
        <div class="form-group" v-for="item in textAreaItems" :key="item">
          <label>{{ item.label }}</label>
          <br>
          <textarea id="subject" name="subject" v-model="item.value"></textarea>
        </div>
      </div>
    </form>
    <input type="submit" value="Submit" @click="createItem">
  </div>
</template>


<style scoped>
.form-content {
  background-color: white;
  padding: 10px 40px 30px 40px;
}

input[type=file] {
  width: 100%;
  background-color: grey;
  color: white;
  padding: 14px 20px;
  margin: 8px 0;
  border: none;
  border-radius: 4px;
  cursor: pointer;
}

input[type=submit]:hover {
  background-color: #45a049;
}

input[type=submit] {
  width: 100%;
  background-color: #4CAF50;
  color: white;
  padding: 14px 20px;
  margin: 8px 0;
  border: none;
  border-radius: 4px;
  cursor: pointer;
}

input[type],
select,
textarea,
input[type=file] {
  width: 100%;
  padding: 12px 20px;
  margin: 8px 0;
  display: inline-block;
  border: 1px solid #ccc;
  border-radius: 4px;
  box-sizing: border-box;
}

textarea {
  resize: none;
}
</style>