
<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { ref, computed, onMounted } from 'vue';

const props = defineProps({
    tags: {
        type: Array,
        required: true
    },
    post: {
        type: Object,
        required: true
    }
});

//console.log(props.tags);

const form = useForm({
    title: props.post.title,
    body: props.post.body,
    status: props.post.status,
    tags: props.post.tags?.map(tag => tag.id) || []
});

const searchQuery = ref('');
const showTagDropdown = ref(false);

const filteredTags = computed(() => {
    return props.tags.filter(tag => 
        tag.name.toLowerCase().includes(searchQuery.value.toLowerCase()) && 
        !form.tags.includes(tag.id)
    );
});

onMounted(() => {
    document.addEventListener('click', hideTagDropdownWhenClickOutside);
});

const hideTagDropdownWhenClickOutside = (event) => {
    if(!event.target.closest('#tags')) {
        showTagDropdown.value = false;
    }
}

const addTag = (tag) => {
    form.tags.push(tag.id);
    showTagDropdown.value = false;
    searchQuery.value = '';
};

const removeTag = (tagId) => {
    form.tags = form.tags.filter(id => id !== tagId);
};

const getTagName = (tagId) => {
    return props.tags.find(tag => tag.id === tagId)?.name;
};

const updatePost = () => {
    form.put(route('posts.update', props.post.id));
}
</script>

<template>
    <Head title="Edit Post" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Edit Post
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <form class="max-w-2xl" @submit.prevent="updatePost">
                            <div class="mb-6">
                                <InputLabel for="title" value="Title" />
                                <TextInput
                                    id="title"
                                    type="text"
                                    class="mt-1 block w-full"
                                    v-model="form.title"
                                    required
                                    autofocus
                                />
                                <InputError class="mt-2" :message="form.errors.title" />
                            </div>

                            <div class="mb-6">
                                <InputLabel for="body" value="Content" />
                                <textarea
                                    id="body"
                                    v-model="form.body"
                                    class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                    rows="8"
                                    required
                                ></textarea>
                                <InputError class="mt-2" :message="form.errors.body" />
                            </div>

                            <div class="mb-6">
                                <InputLabel for="tags" value="Tags" />
                                <div class="relative">
                                    <TextInput
                                        id="tags"
                                        type="text"
                                        class="mt-1 block w-full"
                                        v-model="searchQuery"
                                        @focus="showTagDropdown = true"
                                        placeholder="Search for tags..."
                                    />
                                    <!-- Tag Dropdown -->
                                    <div v-if="showTagDropdown && filteredTags.length > 0"
                                        class="absolute z-10 mt-1 w-full bg-white shadow-lg rounded-md border border-gray-200 max-h-60 overflow-auto">
                                        <div v-for="tag in filteredTags"
                                            :key="tag.id"
                                            @click="addTag(tag)"
                                            class="px-4 py-2 hover:bg-gray-100 cursor-pointer">
                                            {{ tag.name }}
                                        </div>
                                    </div>
                                </div>
                                <!-- Selected Tags -->
                                <div class="mt-2 flex flex-wrap gap-2">
                                    <div v-for="tagId in form.tags"
                                        :key="tagId"
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        {{ getTagName(tagId) }}
                                        <button type="button" @click="removeTag(tagId)" class="ml-1.5 text-blue-600 hover:text-blue-800">
                                            &times;
                                        </button>
                                    </div>
                                </div>
                                <InputError class="mt-2" :message="form.errors.tags" />
                            </div>

                            <div class="mb-6">
                                <InputLabel for="status" value="Status" />
                                <select
                                    id="status"
                                    v-model="form.status"
                                    class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                >
                                    <option value="draft">Draft</option>
                                    <option value="published">Published</option>
                                </select>
                                <InputError class="mt-2" :message="form.errors.status" />
                            </div>

                            <div class="flex items-center gap-4">
                                <PrimaryButton>Update Post</PrimaryButton>
                                <button
                                    type="button"
                                    class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150"
                                    @click="$inertia.visit(route('posts.index'))"
                                >
                                    Cancel
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>