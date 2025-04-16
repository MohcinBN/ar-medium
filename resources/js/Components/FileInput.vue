<script setup>
import { ref } from 'vue';
import InputLabel from './InputLabel.vue';

const props = defineProps({
    modelValue: {
        type: [File, null],
        default: null,
    },
    label: {
        type: String,
        default: '',
    },
    accept: {
        type: String,
        default: 'image/*',
    },
    class: {
        type: String,
        default: '',
    },
    id: {
        type: String,
        default: '',
    }
});

const emit = defineEmits(['update:modelValue']);
const fileInput = ref(null);

const handleFileChange = (event) => {
    const file = event.target.files[0] || null;
    emit('update:modelValue', file);
};

const clearFile = () => {
    if (fileInput.value) {
        fileInput.value.value = '';
    }
    emit('update:modelValue', null);
};
</script>

<template>
    <div :class="class">
        <InputLabel v-if="label" :value="label" />
        
        <div class="mt-1 flex items-center">
            <input
                :id="id"
                ref="fileInput"
                type="file"
                :accept="accept"
                class="block w-full text-sm text-gray-500
                    file:mr-4 file:py-2 file:px-4
                    file:rounded-md file:border-0
                    file:text-sm file:font-semibold
                    file:bg-indigo-50 file:text-indigo-700
                    hover:file:bg-indigo-100
                    cursor-pointer"
                @change="handleFileChange"
            />
            <button
                v-if="modelValue"
                type="button"
                class="ml-2 text-sm text-red-600 hover:text-red-700"
                @click="clearFile"
            >
                Clear
            </button>
        </div>
    </div>
</template>
