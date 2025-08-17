<template>
    <div class="p-6">
        <h1 class="text-2xl font-bold mb-6">Create School Year</h1>
        <form @submit.prevent="submit" class="max-w-lg">
            <div class="mb-4">
                <label class="block text-gray-700">Year Name</label>
                <input v-model="form.year_name" type="text" class="w-full border rounded p-2" required>
                <div v-if="errors.year_name" class="text-red-500 text-sm">{{ errors.year_name }}</div>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700">Start Date</label>
                <input v-model="form.start_date" type="date" class="w-full border rounded p-2" required>
                <div v-if="errors.start_date" class="text-red-500 text-sm">{{ errors.start_date }}</div>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700">End Date</label>
                <input v-model="form.end_date" type="date" class="w-full border rounded p-2" required>
                <div v-if="errors.end_date" class="text-red-500 text-sm">{{ errors.end_date }}</div>
            </div>
            <div class="mb-4">
                <label class="flex items-center">
                    <input v-model="form.is_current" type="checkbox" class="mr-2">
                    Current School Year
                </label>
            </div>
            <div class="mb-4">
                <label class="flex items-center">
                    <input v-model="form.is_active" type="checkbox" class="mr-2">
                    Active
                </label>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700">Description</label>
                <textarea v-model="form.description" class="w-full border rounded p-2"></textarea>
            </div>
            <div class="flex gap-4">
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Save</button>
                <Link href="/admin/school-years" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">Cancel</Link>
            </div>
        </form>
    </div>
</template>

<script setup>
import { reactive } from 'vue';
import { Link, router } from '@inertiajs/vue3';

const form = reactive({
    year_name: '',
    start_date: '',
    end_date: '',
    is_current: false,
    is_active: true,
    description: ''
});

const errors = reactive({});

const submit = () => {
    router.post('/admin/school-years', form, {
        onError: (err) => {
            Object.assign(errors, err);
        },
        onSuccess: () => {
            Object.keys(errors).forEach(key => delete errors[key]);
        }
    });
};
</script>