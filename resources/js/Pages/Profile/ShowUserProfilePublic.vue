<template>
    <Head :title="user.name" />

    <div class="min-h-screen bg-white">
        <!-- Header -->
        <nav class="border-b border-black/10 bg-white">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="flex h-16 items-center justify-between">
                    <div class="flex items-center">
                        <Link :href="route('home')" class="text-2xl font-bold">
                            Medium
                        </Link>
                    </div>
                    <div class="flex items-center space-x-4">
                        <template v-if="$page.props.auth.user">
                            <Link
                                :href="route('posts.create')"
                                class="rounded-full bg-green-600 px-4 py-2 text-white hover:bg-green-700"
                            >
                                كتابة مقال
                            </Link>
                            <Link :href="route('dashboard')" class="text-gray-600 hover:text-gray-900">
                                لوحة التحكم
                            </Link>
                        </template>
                        <template v-else>
                            <Link
                                :href="route('login')"
                                class="text-gray-600 hover:text-gray-900"
                            >
                                تسجيل الدخول
                            </Link>
                            <Link
                                :href="route('register')"
                                class="rounded-full bg-black px-4 py-2 text-white hover:bg-gray-800"
                            >
                                ابدأ الكتابة
                            </Link>
                        </template>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Profile Info -->
        <div class="border-b border-black/10 bg-yellow-400/10 py-12">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="flex items-center space-x-4">
                    <img
                        :src="`https://ui-avatars.com/api/?name=${user.name}&background=random&size=128`"
                        :alt="user.name"
                        class="h-32 w-32 rounded-full"
                    />
                    <div class="mr-4">
                        <h1 class="text-3xl font-bold text-black">{{ user.name }}</h1>
                        <p v-if="user.bio" class="mt-2 text-gray-600">{{ user.bio }}</p>
                        <div class="mt-4 flex items-center space-x-6">
                            <div class="text-sm">
                                <span class="font-semibold">20</span>
                                <span class="text-gray-600"> متابِعون</span>
                            </div>
                            <div class="text-sm">
                                <span class="font-semibold">0</span>
                                <span class="text-gray-600"> متابَعون</span>
                            </div>
                        </div>
                        <button class="mt-4 rounded-full bg-green-600 px-4 py-2 text-white hover:bg-green-700">
                            متابعة
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <main class="py-12">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 gap-8 lg:grid-cols-3">
                    <!-- Latest Posts of the user in show -->
                    <div class="lg:col-span-2">
                        <h2 class="mb-8 text-2xl font-bold">المقالات</h2>
                        <div class="space-y-8">
                            <div v-for="post in user.posts" :key="post.id" class="flex space-x-6">
                                <div class="flex-1">
                                    <Link
                                        :href="route('posts.show', post.id)"
                                        class="mt-2 block"
                                    >
                                        <h3 class="text-xl font-bold text-gray-900">
                                            {{ post.title }}
                                        </h3>
                                        <p class="mt-2 text-gray-600 line-clamp-3">
                                            {{ post.body }}
                                        </p>
                                    </Link>
                                    <div class="mt-4 flex items-center space-x-4">
                                        <time class="text-sm text-gray-500">{{ new Date(post.created_at).toLocaleDateString('ar-SA') }}</time>
                                        <span class="text-sm text-gray-500">·</span>
                                        <span class="text-sm text-gray-500">{{ Math.ceil(post.body.length / 200) }} دقائق للقراءة</span>
                                        <Link 
                                            :href="route('posts.show', post.id)"
                                            class="text-sm text-green-600 hover:text-green-700"
                                        >
                                            قراءة المزيد
                                        </Link>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </main>
    </div>
</template>

<script setup>
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    user: Object,
});

function route(...args) {
    return window.route(...args);
}
</script>
