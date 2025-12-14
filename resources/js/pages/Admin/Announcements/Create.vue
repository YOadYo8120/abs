<script setup lang="ts">
import { ref } from 'vue'
import { Head, router, useForm } from '@inertiajs/vue3'
import AppLayout from '@/layouts/AppLayout.vue'
import { Button } from '@/components/ui/button'
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card'
import { Input } from '@/components/ui/input'
import { Label } from '@/components/ui/label'
import { Textarea } from '@/components/ui/textarea'

const form = useForm({
    title: '',
    content: '',
    publish_now: true,
})

const submit = () => {
    form.post('/admin/announcements', {
        onSuccess: () => {
            form.reset()
        },
    })
}
</script>

<template>
    <AppLayout>
        <Head title="Create Announcement" />

        <div class="container mx-auto py-6 max-w-3xl">
            <div class="mb-6">
                <h1 class="text-3xl font-bold">Create Global Announcement</h1>
                <p class="text-muted-foreground">Send an announcement to all students and teachers</p>
            </div>

            <Card>
                <CardHeader>
                    <CardTitle>Announcement Details</CardTitle>
                    <CardDescription>This announcement will be visible to everyone</CardDescription>
                </CardHeader>
                <CardContent>
                    <form @submit.prevent="submit" class="space-y-6">
                        <div class="space-y-2">
                            <Label for="title">Title</Label>
                            <Input
                                id="title"
                                v-model="form.title"
                                placeholder="Enter announcement title"
                                :class="{ 'border-red-500': form.errors.title }"
                            />
                            <p v-if="form.errors.title" class="text-sm text-red-500">{{ form.errors.title }}</p>
                        </div>

                        <div class="space-y-2">
                            <Label for="content">Content</Label>
                            <Textarea
                                id="content"
                                v-model="form.content"
                                placeholder="Enter announcement content"
                                rows="6"
                                :class="{ 'border-red-500': form.errors.content }"
                            />
                            <p v-if="form.errors.content" class="text-sm text-red-500">{{ form.errors.content }}</p>
                        </div>

                        <div class="flex gap-3">
                            <Button type="submit" :disabled="form.processing">
                                {{ form.processing ? 'Creating...' : 'Create Announcement' }}
                            </Button>
                            <Button type="button" variant="outline" @click="router.visit('/admin/announcements')">
                                Cancel
                            </Button>
                        </div>
                    </form>
                </CardContent>
            </Card>
        </div>
    </AppLayout>
</template>
