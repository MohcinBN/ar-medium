
<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { ref } from 'vue';
import { format } from 'date-fns';
import Modal from '@/Components/Modal.vue';

const props = defineProps({
    users: {
        type: Object,
        required: true
    },
    success: {
        type: String,
        default: null
    }
});

const showRoleModal = ref(false);
const selectedUser = ref(null);
const selectedRole = ref('');

const formatDate = (date) => {
    if (!date) return '-';
    return format(new Date(date), 'MMM d, yyyy');
};

const deleteUser = (id) => {
    if (window.confirm('هل أنت متأكد من حذف هذا المستخدم؟')) {
        router.delete(route('admin.users.delete', id));
    }
};

const openRoleModal = (user) => {
    selectedUser.value = user;
    selectedRole.value = user.roles[0]?.name || '';
    showRoleModal.value = true;
};

const updateRole = () => {
    router.put(route('admin.users.updateRole', selectedUser.value.id), {
        role: selectedRole.value
    }, {
        onSuccess: () => {
            showRoleModal.value = false;
            selectedUser.value = null;
        }
    });
};
</script>

<template>
    <Head title="إدارة المستخدمين" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">إدارة المستخدمين</h2>
            </div>
        </template>

        <div class="py-12" dir="rtl">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div v-if="success" class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative text-right">
                    {{ success }}
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الإسم</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">البريد الإلكتروني</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الدور</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">تاريخ التسجيل</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr v-for="user in users.data" :key="user.id">
                                    <td class="px-6 py-4 whitespace-nowrap text-right">{{ user.name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right">{{ user.email }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right">{{ user.roles[0]?.name || 'لا يوجد دور' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right">{{ formatDate(user.created_at) }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-right">
                                        <button @click="openRoleModal(user)" class="text-indigo-600 hover:text-indigo-900 mr-3">تغيير الدور</button>
                                        <button @click="deleteUser(user.id)" class="text-red-600 hover:text-red-900">حذف</button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Pagination -->
                <div class="mt-4">
                    <div class="flex flex-wrap justify-center gap-1">
                        <template v-for="(link, index) in users.links" :key="index">
                            <div v-if="!link.url" 
                                 class="px-4 py-2 text-sm border rounded text-gray-500 bg-gray-100"
                                 v-html="link.label" />
                            <Link v-else
                                  :href="link.url"
                                  class="px-4 py-2 text-sm border rounded hover:bg-gray-50"
                                  :class="{'bg-blue-500 text-white hover:bg-blue-600': link.active}"
                                  v-html="link.label" />
                        </template>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>

    <!-- Role Modal -->
    <Modal :show="showRoleModal" @close="showRoleModal = false">
        <div class="p-6" dir="rtl">
            <h3 class="text-lg font-medium text-gray-900 mb-4 text-right">تغيير دور المستخدم</h3>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 text-right">اختر الدور</label>
                <select v-model="selectedRole" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md text-right">
                    <option value="User">مستخدم</option>
                    <option value="Admin">مشرف</option>
                    <option value="SuperAdmin">مشرف عام</option>
                </select>
            </div>
            <div class="mt-6 flex justify-start">
                <button @click="updateRole" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 ml-3">
                    تحديث الدور
                </button>
                <button @click="showRoleModal = false" class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    إلغاء
                </button>
            </div>
        </div>
    </Modal>
</template>