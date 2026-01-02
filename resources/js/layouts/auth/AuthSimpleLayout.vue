<script setup lang="ts">
import { GraduationCap } from 'lucide-vue-next';
import { home } from '@/routes';
import { Link } from '@inertiajs/vue3';

defineProps<{
    title?: string;
    description?: string;
}>();
</script>

<template>
    <div class="flex min-h-svh flex-col items-center justify-center gap-6 bg-background p-6 md:p-10">
        <div class="w-full max-w-md">
            <div class="flex flex-col gap-8">
                <div class="flex flex-col items-center gap-6">
                    <Link
                        :href="home()"
                        class="flex flex-col items-center gap-3 font-medium group"
                    >
                        <div class="bg-primary/10 rounded-2xl p-4 shadow-lg group-hover:shadow-xl transition-all duration-200">
                            <GraduationCap class="w-12 h-12 text-primary" />
                        </div>
                        <div class="text-center">
                            <h1 class="text-2xl font-bold">ABS Academic System</h1>
                            <p class="text-sm text-muted-foreground">ENSA Safi Engineering School</p>
                        </div>
                        <span class="sr-only">{{ title }}</span>
                    </Link>
                    <div class="space-y-3 text-center max-w-sm">
                        <h2 class="text-2xl font-bold">{{ title }}</h2>
                        <p class="text-muted-foreground leading-relaxed">
                            {{ description }}
                        </p>
                    </div>
                </div>
                <div class="bg-card rounded-2xl shadow-xl border p-8">
                    <slot />
                </div>
            </div>
        </div>
    </div>
</template>
