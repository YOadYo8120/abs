<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Alert, AlertDescription } from '@/components/ui/alert';
import { ArrowLeft, Mail } from 'lucide-vue-next';
import { index as teachersIndex, store as teachersStore } from '@/routes/admin/teachers';

const form = useForm({
    name: '',
    email: '',
});

const submit = () => {
    form.post(teachersStore.url(), {
        onSuccess: () => {
            form.reset();
        }
    });
};
</script>

<template>
    <AppLayout>
        <Head title="Create Teacher" />

        <div class="container mx-auto max-w-2xl py-8">
            <div class="mb-6">
                <Button variant="ghost" @click="$inertia.visit(teachersIndex.url())">
                    <ArrowLeft class="mr-2 h-4 w-4" />
                    Back to Teachers
                </Button>
            </div>

            <Card>
                <CardHeader>
                    <CardTitle>Create New Teacher</CardTitle>
                    <CardDescription>Add a new teacher to the system</CardDescription>
                </CardHeader>
                <CardContent>
                    <Alert class="mb-6">
                        <Mail class="h-4 w-4" />
                        <AlertDescription>
                            A random password will be generated and sent to the teacher's email address automatically.
                        </AlertDescription>
                    </Alert>

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
                            <p class="text-xs text-muted-foreground">
                                Login credentials will be sent to this email address.
                            </p>
                        </div>

                        <div class="flex gap-4">
                            <Button type="submit" :disabled="form.processing">
                                {{ form.processing ? 'Creating...' : 'Create Teacher' }}
                            </Button>
                            <Button
                                type="button"
                                variant="outline"
                                @click="$inertia.visit(teachersIndex.url())"
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
