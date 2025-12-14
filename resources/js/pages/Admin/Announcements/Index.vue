<script setup lang="ts">
import { ref } from 'vue'
import { Head, Link, router } from '@inertiajs/vue3'
import AppLayout from '@/layouts/AppLayout.vue'
import { Button } from '@/components/ui/button'
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card'
import { Badge } from '@/components/ui/badge'
import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from '@/components/ui/table'

interface Announcement {
    id: number
    title: string
    content: string
    scope: string
    published_at: string | null
    created_at: string
    user: {
        first_name: string
        last_name: string
    }
}

interface Props {
    announcements: {
        data: Announcement[]
        links: any[]
        current_page: number
        last_page: number
    }
}

const props = defineProps<Props>()

const deleteAnnouncement = (id: number) => {
    if (confirm('Are you sure you want to delete this announcement?')) {
        router.delete(route('admin.announcements.destroy', id))
    }
}

const publishAnnouncement = (id: number) => {
    router.post(route('admin.announcements.publish', id))
}
</script>

<template>
    <AppLayout>
        <Head title="Announcements" />

        <div class="container mx-auto py-6">
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h1 class="text-3xl font-bold">Announcements</h1>
                    <p class="text-muted-foreground">Manage global announcements for all users</p>
                </div>
                <Button as-child>
                    <Link :href="route('admin.announcements.create')">
                        Create Announcement
                    </Link>
                </Button>
            </div>

            <Card>
                <CardHeader>
                    <CardTitle>All Announcements</CardTitle>
                    <CardDescription>Global announcements visible to all students and teachers</CardDescription>
                </CardHeader>
                <CardContent>
                    <Table>
                        <TableHeader>
                            <TableRow>
                                <TableHead>Title</TableHead>
                                <TableHead>Content</TableHead>
                                <TableHead>Status</TableHead>
                                <TableHead>Published</TableHead>
                                <TableHead>Created</TableHead>
                                <TableHead>Actions</TableHead>
                            </TableRow>
                        </TableHeader>
                        <TableBody>
                            <TableRow v-for="announcement in announcements.data" :key="announcement.id">
                                <TableCell class="font-medium">{{ announcement.title }}</TableCell>
                                <TableCell class="max-w-md truncate">{{ announcement.content }}</TableCell>
                                <TableCell>
                                    <Badge v-if="announcement.published_at" variant="success">Published</Badge>
                                    <Badge v-else variant="secondary">Draft</Badge>
                                </TableCell>
                                <TableCell>
                                    {{ announcement.published_at ? new Date(announcement.published_at).toLocaleString() : '-' }}
                                </TableCell>
                                <TableCell>{{ new Date(announcement.created_at).toLocaleDateString() }}</TableCell>
                                <TableCell>
                                    <div class="flex gap-2">
                                        <Button v-if="!announcement.published_at" size="sm" @click="publishAnnouncement(announcement.id)">
                                            Publish
                                        </Button>
                                        <Button size="sm" variant="outline" as-child>
                                            <Link :href="route('admin.announcements.edit', announcement.id)">
                                                Edit
                                            </Link>
                                        </Button>
                                        <Button size="sm" variant="destructive" @click="deleteAnnouncement(announcement.id)">
                                            Delete
                                        </Button>
                                    </div>
                                </TableCell>
                            </TableRow>
                            <TableRow v-if="announcements.data.length === 0">
                                <TableCell colspan="6" class="text-center text-muted-foreground">
                                    No announcements found. Create your first announcement!
                                </TableCell>
                            </TableRow>
                        </TableBody>
                    </Table>

                    <!-- Pagination -->
                    <div v-if="announcements.last_page > 1" class="flex justify-center gap-2 mt-4">
                        <Button
                            v-for="link in announcements.links"
                            :key="link.label"
                            :variant="link.active ? 'default' : 'outline'"
                            size="sm"
                            :disabled="!link.url"
                            @click="link.url && router.visit(link.url)"
                            v-html="link.label"
                        />
                    </div>
                </CardContent>
            </Card>
        </div>
    </AppLayout>
</template>
