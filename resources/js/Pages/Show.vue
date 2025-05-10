<template>
    <Head :title="post.title" />

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

        <!-- Article Content -->
        <article class="mx-auto max-w-3xl px-4 py-12 sm:px-6 lg:px-8">
            <header class="mb-8">
                <div class="flex items-center space-x-4 mb-4">
               
                    <div>
                        <h3 class="font-medium">{{ post.user?.name }}</h3>
                        <div class="flex items-center space-x-4 text-sm text-gray-500">
                            <time>{{ new Date(post.created_at).toLocaleDateString('ar-SA') }}</time>
                            <span>·</span>
                            <span>{{ Math.ceil(post.body.length / 200) }} دقائق للقراءة</span>
                        </div>
                    </div>
                </div>
                <h1 class="text-4xl font-bold text-gray-900 mb-4">{{ post.title }}</h1>
                <div v-if="post.tags.length" class="flex flex-wrap gap-2">
                    <span 
                        v-for="tag in post.tags" 
                        :key="tag.id"
                        class="inline-flex items-center rounded-full bg-gray-100 px-3 py-1 text-sm font-medium text-gray-800"
                    >
                        {{ tag.name }}
                    </span>
                </div>
            </header>

            <div v-if="post.image" class="mb-8">
                <img
                    :src="post.image"
                    :alt="post.title"
                    class="w-full rounded-lg object-cover"
                />
            </div>

            <div class="prose prose-lg max-w-none">
                {{ post.body }}
            </div>
        </article>
    </div>
</template>

<script setup>
import { Head, Link } from '@inertiajs/vue3';

const props = defineProps({
    post: {
        type: Object,
        required: true
    }
});
</script>