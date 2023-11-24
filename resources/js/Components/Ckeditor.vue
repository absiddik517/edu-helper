<template>
  <div>
    <ckeditor
      :editor="editor"
      v-model="editorData"
      :config="editorConfig"
      @input="updateModelValue"
      row="5"
      placeholder="Additional Informations"
    ></ckeditor>
  </div>
</template>

<script>
import ClassicEditor from "@ckeditor/ckeditor5-build-classic";

export default {
  props: {
    modelValue: [String, Number],
    placeholder: {
      type: String,
      default: 'Start typing...'
    }
  },
  data() {
    return {
      editor: ClassicEditor,
      editorData: this.modelValue,
      editorConfig: {
        // The configuration of the editor.
        placeholder: this.placeholder
      },
    };
  },

  mounted() {
    console.log(this.modelValue);
  },
  methods: {
    updateModelValue(event){
      console.log('edited')
        let value = this.editorData;
        this.$emit('update:modelValue', value);
        this.$emit('changed', value);
        this.$emit('input', value);
      }
  },
};
</script>
