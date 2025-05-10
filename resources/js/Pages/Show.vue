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

            <!-- Comments Section -->
            <div class="mt-12 border-t border-gray-200 pt-8">
                <h2 class="text-2xl font-bold mb-6">التعليقات</h2>
                
                <!-- Add Comment Form -->
                <form v-if="$page.props.auth.user" @submit.prevent="submitComment" class="mb-8">
                    <div class="mb-4">
                        <label for="comment" class="block text-sm font-medium text-gray-700 mb-2">
                            أضف تعليقاً
                        </label>
                        <textarea
                            id="comment"
                            v-model="form.body"
                            rows="3"
                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500"
                            placeholder="اكتب تعليقك هنا..."
                            dir="rtl"
                        ></textarea>
                    </div>
                    <div class="flex justify-end">
                        <button
                            type="submit"
                            class="rounded-md bg-green-600 px-4 py-2 text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2"
                            :disabled="form.processing"
                        >
                            إرسال التعليق
                        </button>
                    </div>
                </form>
                <div v-else class="mb-8 text-center">
                    <p class="text-gray-600">
                        <Link :href="route('login')" class="text-green-600 hover:text-green-700">سجل دخولك</Link>
                        لإضافة تعليق
                    </p>
                </div>

                <!-- Comments List -->
                <div class="space-y-6">
                    <div v-if="post.comments?.length" v-for="comment in post.comments" :key="comment.id" class="bg-gray-50 rounded-lg p-4">
                        <div class="flex-1">
                            <div class="flex items-center justify-between mb-2">
                                <div class="flex items-center gap-2">
                                    <h3 class="font-medium text-gray-900">{{ comment.user?.name }}</h3>
                                    <time class="text-sm text-gray-500">
                                        {{ new Date(comment.created_at).toLocaleDateString('ar-SA') }}
                                    </time>
                                </div>
                            </div>
                            <p class="text-gray-700" dir="rtl">{{ comment.body }}</p>
                        </div>
                    </div>
                    <p v-else class="text-gray-500 text-center">لا توجد تعليقات بعد. كن أول من يعلق!</p>
                </div>
            </div>
        </article>
    </div>
</template>

<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { useForm } from '@inertiajs/vue3';

const props = defineProps({
    post: {
        type: Object,
        required: true
    }
});

const form = useForm({
    body: '',
    post_id: props.post.id
});

const submitComment = () => {
    form.post(route('posts.comments.store', props.post.id), {
        onSuccess: () => {
            form.reset();
        },
    });
};
</script>