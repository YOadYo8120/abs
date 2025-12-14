<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { Button } from '@/components/ui/button';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import { Badge } from '@/components/ui/badge';
import { Plus, Pencil, Trash2, Eye } from 'lucide-vue-next';

interface Module {
    id: number;
    name: string;
    code: string;
    description: string | null;
    year: number;
    specialization: string | null;
    track: string | null;
    teacher: {
        id: number;
        name: string;
        email: string;
    } | null;
    created_at: string;
}

interface Props {
    modules: {
        data: Module[];
        links: any[];
        current_page: number;
        last_page: number;
    };
}

defineProps<Props>();

const deleteModule = (id: number, name: string) => {
    if (confirm(`Are you sure you want to delete "${name}"?`)) {
        router.delete(route('admin.modules.destroy', id));
    }
};

const getYearBadgeVariant = (year: number) => {
    const variants = ['default', 'secondary', 'outline', 'destructive', 'default'];
    return variants[year - 1] || 'secondary';
};
</script>

<template>
    <AppLayout>
        <Head title="Modules Management" />

        <div class="container mx-auto py-8">
            <div class="mb-6 flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold">Modules Management</h1>
                    <p class="text-muted-foreground">Manage course modules and assignments</p>
                </div>
                <Link :href="route('admin.modules.create')">
                    <Button>
                        <Plus class="mr-2 h-4 w-4" />
                        Add Module
                    </Button>
                </Link>
            </div>

            <div class="rounded-lg border bg-card">
                <Table>
                    <TableHeader>
                        <TableRow>
                            <TableHead>Code</TableHead>
                            <TableHead>Name</TableHead>
                            <TableHead>Year</TableHead>
                            <TableHead>Specialization</TableHead>
                            <TableHead>Track</TableHead>
                            <TableHead>Teacher</TableHead>
                            <TableHead>Description</TableHead>
                            <TableHead class="text-right">Actions</TableHead>
                        </TableRow>
                    </TableHeader>
                    <TableBody>
                        <TableRow v-for="module in modules.data" :key="module.id">
                            <TableCell class="font-mono font-medium">{{ module.code }}</TableCell>
                            <TableCell class="font-medium">{{ module.name }}</TableCell>
                            <TableCell>
                                <Badge :variant="getYearBadgeVariant(module.year)">
                                    Year {{ module.year }}
                                </Badge>
                            </TableCell>
                            <TableCell>
                                <Badge v-if="module.specialization" variant="outline">
                                    {{ module.specialization }}
                                </Badge>
                                <span v-else class="text-xs text-muted-foreground">Common</span>
                            </TableCell>
                            <TableCell>
                                <Badge v-if="module.track" variant="secondary" class="text-xs">
                                    {{ module.track }}
                                </Badge>
                                <span v-else class="text-xs text-muted-foreground">-</span>
                            </TableCell>
                            <TableCell>
                                <div v-if="module.teacher">
                                    <div class="font-medium">{{ module.teacher.name }}</div>
                                    <div class="text-sm text-muted-foreground">{{ module.teacher.email }}</div>
                                </div>
                                <span v-else class="text-muted-foreground">Not assigned</span>
                            </TableCell>
                            <TableCell>
                                <span class="text-sm text-muted-foreground">
                                    {{ module.description || 'No description' }}
                                </span>
                            </TableCell>
                            <TableCell class="text-right">
                                <div class="flex justify-end gap-2">
                                    <Link :href="route('admin.modules.show', module.id)">
                                        <Button variant="ghost" size="sm">
                                            <Eye class="h-4 w-4" />
                                        </Button>
                                    </Link>
                                    <Link :href="route('admin.modules.edit', module.id)">
                                        <Button variant="ghost" size="sm">
                                            <Pencil class="h-4 w-4" />
                                        </Button>
                                    </Link>
                                    <Button
                                        variant="ghost"
                                        size="sm"
                                        @click="deleteModule(module.id, module.name)"
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
            <div v-if="modules.last_page > 1" class="mt-4 flex justify-center gap-2">
                <Link
                    v-for="link in modules.links"
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
