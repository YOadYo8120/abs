<script setup lang="ts">
import { Head, useForm, usePage } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Checkbox } from '@/components/ui/checkbox';
import { ArrowLeft } from 'lucide-vue-next';
import { computed } from 'vue';

interface Teacher {
    id: number;
    name: string;
    email: string;
    role: string;
}

interface Props {
    teacher: Teacher;
}

const props = defineProps<Props>();

const page = usePage();
const currentUserEmail = computed(() => page.props.auth?.user?.email);
const canPromoteToAdmin = computed(() => currentUserEmail.value === 'y.adil8120@uca.ac.ma');

const form = useForm({
    name: props.teacher.name,
    email: props.teacher.email,
    password: '',
    password_confirmation: '',
    make_admin: props.teacher.role === 'admin',
});

const submit = () => {
    form.put(route('admin.teachers.update', props.teacher.id));
};
</script>

<template>
    <AppLayout>
        <Head title="Edit Teacher" />

        <div class="container mx-auto max-w-2xl py-8">
            <div class="mb-6">
                <Button variant="ghost" @click="$inertia.visit(route('admin.teachers.index'))">
                    <ArrowLeft class="mr-2 h-4 w-4" />
                    Back to Teachers
                </Button>
            </div>

            <Card>
                <CardHeader>
                    <CardTitle>Edit Teacher</CardTitle>
                    <CardDescription>Update teacher information</CardDescription>
                </CardHeader>
                <CardContent>
                    <form @submit.prevent="submit" class="space-y-6">
                        <div class="space-y-2">
                            <Label for="name">Full Name</Label>
                            <Input
                                id="name"
                                v-model="form.name"
                                type="text"
                                required
                                placeholder="Enter teacher's full name"
                            />
                            <span v-if="form.errors.name" class="text-sm text-destructive">
                                {{ form.errors.name }}
                            </span>
                        </div>

                        <div class="space-y-2">
                            <Label for="email">Email</Label>
                            <Input
                                id="email"
                                v-model="form.email"
                                type="email"
                                required
                                placeholder="teacher@uca.ac.ma"
                            />
                            <span v-if="form.errors.email" class="text-sm text-destructive">
                                {{ form.errors.email }}
                            </span>
                        </div>

                        <div class="space-y-2">
                            <Label for="password">New Password (leave blank to keep current)</Label>
                            <Input
                                id="password"
                                v-model="form.password"
                                type="password"
                                placeholder="Enter new password"
                            />
                            <span v-if="form.errors.password" class="text-sm text-destructive">
                                {{ form.errors.password }}
                            </span>
                        </div>

                        <div class="space-y-2">
                            <Label for="password_confirmation">Confirm New Password</Label>
                            <Input
                                id="password_confirmation"
                                v-model="form.password_confirmation"
                                type="password"
                                placeholder="Confirm new password"
                            />
                        </div>

                        <div v-if="canPromoteToAdmin" class="flex items-center space-x-2">
                            <Checkbox
                                id="make_admin"
                                v-model:checked="form.make_admin"
                            />
                            <Label
                                for="make_admin"
                                class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70"
                            >
                                Promote to Admin
                            </Label>
                        </div>

                        <div class="flex gap-4">
                            <Button type="submit" :disabled="form.processing">
                                {{ form.processing ? 'Updating...' : 'Update Teacher' }}
                            </Button>
                            <Button
                                type="button"
                                variant="outline"
                                @click="$inertia.visit(route('admin.teachers.index'))"
                            >
                                Cancel
                            </Button>
                        </div>
                    </form>
                </CardContent>
            </Card>
        </div>
    </AppLayout>
</template>
