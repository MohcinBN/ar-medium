<template>
    <Head title="الرئيسية" />

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

        <!-- Hero Section -->
        <div class="border-b border-black/10 bg-yellow-400/10 py-24">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <h1 class="mb-6 text-6xl font-bold text-black">اكتشف القصص والأفكار والخبرات</h1>
                <p class="text-xl text-gray-600">
                    اقرأ واكتب وشارك قصصك على منصتنا. انضم إلى مجتمعنا اليوم.
                </p>
            </div>
        </div>

        <!-- Main Content -->
        <main class="py-12">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 gap-8 lg:grid-cols-3">
                    <!-- Latest Posts -->
                    <div class="lg:col-span-2">
                        <h2 class="mb-8 text-2xl font-bold">أحدث المقالات</h2>
                        <div class="space-y-8">
                            <div v-for="post in latestPosts" :key="post.id" class="flex space-x-6">
                                <div class="flex-1">
                                    <div class="flex items-center space-x-2">
                                        <img
                                            :src="`https://ui-avatars.com/api/?name=${post.user.name}&background=random`"
                                            :alt="post.user.name"
                                            class="h-6 w-6 rounded-full"
                                        />
                                        <span class="text-sm text-gray-600">{{ post.user.name }}</span>
                                    </div>
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
                                <div v-if="post.image" class="h-32 w-32">
                                    <img
                                        :src="post.image"
                                        :alt="post.title"
                                        class="h-full w-full object-cover"
                                    />
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Sidebar -->
                    <div class="lg:col-span-1">
                        <!-- Staff Picks -->
                        <div class="rounded-lg border border-gray-200 p-6">
                            <h3 class="mb-6 text-lg font-bold">اختيارات المحررين</h3>
                            <div class="space-y-6">
                                <div v-for="post in staffPicks" :key="post.id">
                                    <div class="flex items-center space-x-2">
                                        <img
                                            :src="`https://ui-avatars.com/api/?name=${post.user.name}&background=random`"
                                            :alt="post.user.name"
                                            class="h-6 w-6 rounded-full"
                                        />
                                        <span class="text-sm text-gray-600">{{ post.user.name }}</span>
                                    </div>
                                    <Link
                                        
                                        class="mt-2 block"
                                    >
                                        <h4 class="font-semibold text-gray-900">{{ post.title }}</h4>
                                    </Link>
                                </div>
                            </div>
                        </div>

                        <!-- Footer Links -->
                        <div class="mt-8">
                            <div class="flex flex-wrap gap-4 text-sm text-gray-600">
                                <a href="#" class="hover:text-gray-900">سياسة الخصوصية</a>
                                <a href="#" class="hover:text-gray-900">الشروط والأحكام</a>
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
    latestPosts: Array,
    staffPicks: Array,
});

function route(...args) {
    return window.route(...args);
}
</script>
