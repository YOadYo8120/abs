<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { Button } from '@/components/ui/button';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import { Badge } from '@/components/ui/badge';
import { Plus, Pencil, Trash2, Eye } from 'lucide-vue-next';

interface Teacher {
    id: number;
    name: string;
    email: string;
    role: string;
    modules_count: number;
    created_at: string;
}

interface Props {
    teachers: {
        data: Teacher[];
        links: any[];
        current_page: number;
        last_page: number;
    };
}

defineProps<Props>();

const deleteTeacher = (id: number, name: string) => {
    if (confirm(`Are you sure you want to delete ${name}?`)) {
        router.delete(route('admin.teachers.destroy', id));
    }
};
</script>

<template>
    <AppLayout>
        <Head title="Teachers Management" />

        <div class="container mx-auto py-8">
            <div class="mb-6 flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold">Teachers Management</h1>
                    <p class="text-muted-foreground">Manage teacher accounts and assignments</p>
                </div>
                <Link :href="route('admin.teachers.create')">
                    <Button>
                        <Plus class="mr-2 h-4 w-4" />
                        Add Teacher
                    </Button>
                </Link>
            </div>

            <div class="rounded-lg border bg-card">
                <Table>
                    <TableHeader>
                        <TableRow>
                            <TableHead>Name</TableHead>
                            <TableHead>Email</TableHead>
                            <TableHead>Role</TableHead>
                            <TableHead>Modules</TableHead>
                            <TableHead>Joined</TableHead>
                            <TableHead class="text-right">Actions</TableHead>
                        </TableRow>
                    </TableHeader>
                    <TableBody>
                        <TableRow v-for="teacher in teachers.data" :key="teacher.id">
                            <TableCell class="font-medium">{{ teacher.name }}</TableCell>
                            <TableCell>{{ teacher.email }}</TableCell>
                            <TableCell>
                                <Badge :variant="teacher.role === 'admin' ? 'default' : 'secondary'">
                                    {{ teacher.role }}
                                </Badge>
                            </TableCell>
                            <TableCell>{{ teacher.modules_count }}</TableCell>
                            <TableCell>{{ new Date(teacher.created_at).toLocaleDateString() }}</TableCell>
                            <TableCell class="text-right">
                                <div class="flex justify-end gap-2">
                                    <Link :href="route('admin.teachers.show', teacher.id)">
                                        <Button variant="ghost" size="sm">
                                            <Eye class="h-4 w-4" />
                                        </Button>
                                    </Link>
                                    <Link :href="route('admin.teachers.edit', teacher.id)">
                                        <Button variant="ghost" size="sm">
                                            <Pencil class="h-4 w-4" />
                                        </Button>
                                    </Link>
                                    <Button
                                        variant="ghost"
                                        size="sm"
                                        @click="deleteTeacher(teacher.id, teacher.name)"
                                    >
                                        <Trash2 class="h-4 w-4 text-destructive" />
                                    </Button>
                                </div>
                            </TableCell>
                        </TableRow>
                    </TableBody>
                </Table>
            </div>

            <!-- Pagination -->
            <div v-if="teachers.last_page > 1" class="mt-4 flex justify-center gap-2">
                <Link
                    v-for="link in teachers.links"
                    :key="link.label"
                    :href="link.url"
                    :class="[
                        'px-3 py-1 rounded',
                        link.active ? 'bg-primary text-primary-foreground' : 'bg-secondary hover:bg-secondary/80'
                    ]"
                    v-html="link.label"
                />
            </div>
        </div>
    </AppLayout>
</template>
