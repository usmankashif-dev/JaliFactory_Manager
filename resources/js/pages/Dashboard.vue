<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, usePage, Link } from '@inertiajs/vue3';
import PlaceholderPattern from '../components/PlaceholderPattern.vue';

const page = usePage();
const user = page.props.auth?.user || { name: 'User' };

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
];

// List of sidebar pages (add or adjust as needed)
const sidebarPages = [
  { name: 'Mall', route: 'mall-view' },
  { name: 'Orders', route: 'order-view' },
  { name: 'Order List', route: 'orders.index' },
  { name: 'Machines', route: 'machine-view' },
  { name: 'Stock', route: 'stock' },
  { name: 'Verified Bundles', route: 'verified.bundles' },
  { name: 'Add Mall', route: 'Add-mall' },
  // Removed 'Make Order' because it requires an id parameter
];
</script>

<template>
    <Head title="Dashboard" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex flex-col items-center justify-center min-h-[60vh] gap-6 bg-background text-foreground">
            <h1 class="text-4xl font-extrabold text-center text-blue-900 dark:text-blue-200 mb-2">Tahir Jali Factory</h1>
            <div class="text-2xl font-semibold text-gray-700 dark:text-gray-200">Welcome, <span class="text-blue-700 dark:text-blue-300">{{ user.name }}</span></div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 w-full max-w-4xl mt-8">
                <Link v-for="page in sidebarPages" :key="page.route" :href="route(page.route)" class="block p-6 bg-background text-foreground border border-gray-200 dark:border-gray-700 rounded-xl shadow hover:bg-blue-50 dark:hover:bg-blue-900 text-center text-xl font-bold text-blue-800 dark:text-blue-200 transition">
                    {{ page.name }}
                </Link>
            </div>
        </div>
    </AppLayout>
</template>
