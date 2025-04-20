<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { ref } from 'vue';
import { format } from 'date-fns';
import Modal from '@/Components/Modal.vue';
import PostForm from '@/Components/PostForm.vue';

const props = defineProps({
    posts: {
        type: Array,
        required: true,
        default: () => []
    },
    tags: {
        type: Array,
        required: true,
        default: () => []
    },
    success: {
        type: String,
        default: null
    }
});

const showCreateModal = ref(false);
const showEditModal = ref(false);
const editingPost = ref(null);

const formatDate = (date) => {
    if (!date) return '-';
    return format(new Date(date), 'MMM d, yyyy');
};

const deletePost = (id) => {
    if (window.confirm('هل أنت متأكد من حذف هذا المقال؟')) {
        router.delete(route('posts.destroy', id));
    }
};

const openEditModal = (post) => {
    editingPost.value = post;
    showEditModal.value = true;
};

const closeEditModal = () => {
    editingPost.value = null;
    showEditModal.value = false;
};
</script>

<template>
    <Head title="المقالات" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">المقالات</h2>
                <button
                    @click="showCreateModal = true"
                    class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded"
                >
                    مقال جديد
                </button>
            </div>
        </template>

        <div class="py-12" dir="rtl">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div v-if="success" class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative text-right">
                    {{ success }}
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            العنوان
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            الحالة
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            تاريخ النشر
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            تاريخ الإنشاء
                                        </th>
                                        <th scope="col" class="relative px-6 py-3">
                                            <span class="sr-only">تعديل</span>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <tr v-for="post in posts" :key="post.id">
                                        <td class="px-6 py-4 whitespace-nowrap text-right">
                                            {{ post.title }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right">
                                            <span :class="{
                                                'px-2 inline-flex text-xs leading-5 font-semibold rounded-full': true,
                                                'bg-green-100 text-green-800': post.status === 'published',
                                                'bg-yellow-100 text-yellow-800': post.status === 'draft'
                                            }">
                                                {{ post.status === 'published' ? 'منشور' : 'مسودة' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm text-gray-500">
                                            {{ formatDate(post.published_at) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm text-gray-500">
                                            {{ formatDate(post.created_at) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-left text-sm font-medium">
                                            <button 
                                                @click="openEditModal(post)"
                                                class="text-indigo-600 hover:text-indigo-900 ml-3"
                                            >
                                                تعديل
                                            </button>
                                            <button 
                                                @click="deletePost(post.id)"
                                                class="text-red-600 hover:text-red-900"
                                            >
                                                حذف
                                            </button>
                                        </td>
                                    </tr>
                                    <tr v-if="posts.length === 0">
                                        <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                                            لا توجد مقالات
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>

    <!-- Create Modal -->
    <Modal :show="showCreateModal" @close="showCreateModal = false">
        <PostForm 
            :tags="tags"
            mode="create"
            @close="showCreateModal = false"
        />
    </Modal>

    <!-- Edit Modal -->
    <Modal :show="showEditModal" @close="closeEditModal">
        <PostForm 
            v-if="editingPost"
            :tags="tags"
            :post="editingPost"
            mode="edit"
            @close="closeEditModal"
        />
    </Modal>
</template>