<script setup>
import { QuillEditor } from '@vueup/vue-quill'
import '@vueup/vue-quill/dist/vue-quill.snow.css';
import { ref, watch } from 'vue';

const props = defineProps({
  modelValue: {
    type: String,
    default: '',
  },
  placeholder: {
    type: String,
    default: 'Write your notes here...',
  },
  readOnly: {
    type: Boolean,
    default: false,
  }
})


const emit = defineEmits(['update:modelValue'])

// 3. Set up the internal state
const editorContent = ref(props.modelValue)


watch(editorContent, (newValue) => {
  emit('update:modelValue', newValue)
})

watch(() => props.modelValue, (newValue) => {
  if (editorContent.value !== newValue) {
    editorContent.value = newValue
  }
})
</script>

<template>
   <div class="w-full max-w-4xl 
    dark:[&_.ql-toolbar]:!border-gray-700 [&_.ql-toolbar]:rounded-t-lg 
    
    [&_.ql-container]:!border-gray-300 dark:[&_.ql-container]:!border-gray-700

    dark:[&_.ql-editor.ql-blank::before]:!text-gray-100 dark:[&_.ql-editor.ql-blank::before]:!font-normal
    
    [&_.ql-editor]:text-gray-900 dark:[&_.ql-editor]:text-gray-100
    
    [&_.ql-editor]:min-h-[12rem] [&_.ql-editor]:max-h-[12rem] [&_.ql-editor]:overflow-y-auto  
   
  ">
  <QuillEditor toolbar="minimal" 
  class="rounded-b-lg"
    contentType="html"
    v-model:content="editorContent"
    :placeholder="placeholder"
    :readOnly="readOnly">
  </QuillEditor>
  </div>
</template>