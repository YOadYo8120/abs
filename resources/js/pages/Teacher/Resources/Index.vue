<script setup lang="ts">
import { Head, router } from '@inertiajs/vue3'
import AppLayout from '@/layouts/AppLayout.vue'
import { Button } from '@/components/ui/button'
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card'
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table'
import { Badge } from '@/components/ui/badge'
import { Plus, Download, Trash2, FileText } from 'lucide-vue-next'

interface Resource {
    id: number
    title: string
    description: string | null
    file_name: string
    file_size: number
    scope: string
    module?: { code: string; name: string }
    published_at: string
}

interface Props {
    resources: {
        data: Resource[]
        current_page: number
        last_page: number
    }
}

const props = defineProps<Props>()

const deleteResource = (id: number) => {
    if (confirm('Are you sure you want to delete this resource?')) {
        router.delete(`/teacher/resources/${id}`)
    }
}

const downloadResource = (id: number) => {
    window.location.href = `/teacher/resources/${id}/download`
}
</script>

<template>
    <AppLayout>
        <Head title="My Resources" />

        <div class="container mx-auto py-6">
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h1 class="text-3xl font-bold">My Resources</h1>
                    <p class="text-muted-foreground">Manage files shared with your students</p>
                </div>
                <Button @click="router.visit('/teacher/resources/create')">
                    <Plus class="mr-2 h-4 w-4" />
                    Upload Resource
                </Button>
            </div>

            <Card>
                <CardHeader>
                    <CardTitle>Uploaded Resources</CardTitle>
                    <CardDescription>PDF files available to your students</CardDescription>
                </CardHeader>
                <CardContent>
                    <Table v-if="resources.data.length > 0">
                        <TableHeader>
                            <TableRow>
                                <TableHead>Title</TableHead>
                                <TableHead>Scope</TableHead>
                                <TableHead>File</TableHead>
                                <TableHead>Size</TableHead>
                                <TableHead>Uploaded</TableHead>
                                <TableHead>Actions</TableHead>
                            </TableRow>
                        </TableHeader>
                        <TableBody>
                            <TableRow v-for="resource in resources.data" :key="resource.id">
                                <TableCell>
                                    <div>
                                        <div class="font-medium">{{ resource.title }}</div>
                                        <div v-if="resource.description" class="text-sm text-muted-foreground">
                                            {{ resource.description }}
                                        </div>
                                    </div>
                                </TableCell>
                                <TableCell>
                                    <Badge variant="outline" v-if="resource.scope === 'module' && resource.module">
                                        {{ resource.module.code }}
                                    </Badge>
                                    <Badge variant="outline" v-else>
                                        Class
                                    </Badge>
                                </TableCell>
                                <TableCell>
                                    <div class="flex items-center gap-2">
                                        <FileText class="h-4 w-4 text-muted-foreground" />
                                        <span class="text-sm">{{ resource.file_name }}</span>
                                    </div>
                                </TableCell>
                                <TableCell>
                                    {{ (resource.file_size / 1024).toFixed(2) }} KB
                                </TableCell>
                                <TableCell>
                                    {{ new Date(resource.published_at).toLocaleDateString() }}
                                </TableCell>
                                <TableCell>
                                    <div class="flex items-center gap-2">
                                        <Button
                                            size="sm"
                                            variant="outline"
                                            @click="downloadResource(resource.id)"
                                        >
                                            <Download class="h-4 w-4" />
                                        </Button>
                                        <Button
                                            size="sm"
                                            variant="destructive"
                                            @click="deleteResource(resource.id)"
                                        >
                                            <Trash2 class="h-4 w-4" />
                                        </Button>
                                    </div>
                                </TableCell>
                            </TableRow>
                        </TableBody>
                    </Table>
                    <div v-else class="text-center py-8 text-muted-foreground">
                        No resources uploaded yet. Click "Upload Resource" to add your first file.
                    </div>
                </CardContent>
            </Card>
        </div>
    </AppLayout>
</template>
