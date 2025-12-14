<script setup lang="ts">
import { computed, watch } from 'vue'
import { Head, useForm } from '@inertiajs/vue3'
import AppLayout from '@/layouts/AppLayout.vue'
import { Button } from '@/components/ui/button'
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card'
import { Input } from '@/components/ui/input'
import { Label } from '@/components/ui/label'
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select'
import { Alert, AlertDescription } from '@/components/ui/alert'
import InputError from '@/components/InputError.vue'
import { update as studentsUpdate, index as studentsIndex } from '@/routes/admin/students'
import { ArrowLeft } from 'lucide-vue-next'

interface User {
    id: number
    name: string
    email: string
}

interface Student {
    id: number
    user_id: number
    year: number
    specialization: string | null
    track: string | null
    user: User
}

const props = defineProps<{
    student: Student
}>()

const form = useForm({
    name: props.student.user.name,
    email: props.student.user.email,
    year: props.student.year,
    specialization: props.student.specialization,
    track: props.student.track,
})

const specializations = [
    { value: 'GI', label: 'Génie Informatique' },
    { value: 'GRT', label: 'Génie Réseaux & Télécommunications' },
    { value: 'GInd', label: 'Génie Industriel' },
    { value: 'GM', label: 'Génie Mécatronique' },
    { value: 'GA', label: 'Génie Aéronautique' },
    { value: 'GPM', label: 'Génie Procédés & Matériaux' },
]

const tracks = [
    { value: 'DEV', label: 'Software Development' },
    { value: 'AI', label: 'AI & Data Engineering' },
]

// Computed properties
const needsSpecialization = computed(() => form.year && form.year >= 3)
const needsTrack = computed(() =>
    form.specialization === 'GI' && form.year && form.year >= 4
)

// Watch for year changes
watch(() => form.year, (newYear) => {
    if (newYear && newYear < 3) {
        form.specialization = null
        form.track = null
    }
})

// Watch for specialization changes
watch(() => form.specialization, (newSpec) => {
    if (newSpec !== 'GI') {
        form.track = null
    }
})

const submit = () => {
    form.put(studentsUpdate.url({ student: props.student.id }))
}
</script>

<template>
    <AppLayout>
        <Head title="Edit Student" />

        <div class="py-12">
            <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <div class="flex items-center gap-4">
                    <Button variant="ghost" @click="$inertia.visit(studentsIndex.url())">
                        <ArrowLeft class="mr-2 h-4 w-4" />
                        Back
                    </Button>
                    <div>
                        <h2 class="text-2xl font-semibold text-gray-900">Edit Student</h2>
                        <p class="mt-1 text-sm text-gray-600">
                            Update student information and academic details
                        </p>
                    </div>
                </div>

                <Alert>
                    <AlertDescription>
                        <strong>ENSA Safi Structure:</strong>
                        <ul class="list-disc list-inside mt-2 space-y-1">
                            <li><strong>CP1 & CP2 (Years 1-2):</strong> Common core, no specialization</li>
                            <li><strong>Years 3-5:</strong> Choose specialization (Cycle Ingénieur)</li>
                            <li><strong>Years 4-5 (GI only):</strong> Choose track (Dev or AI)</li>
                        </ul>
                    </AlertDescription>
                </Alert>

                <Card>
                    <CardHeader>
                        <CardTitle>Student Information</CardTitle>
                        <CardDescription>Update student details</CardDescription>
                    </CardHeader>
                    <CardContent>
                        <form @submit.prevent="submit" class="space-y-6">
                            <!-- Name -->
                            <div>
                                <Label for="name">Full Name</Label>
                                <Input
                                    id="name"
                                    v-model="form.name"
                                    type="text"
                                    required
                                />
                                <InputError :message="form.errors.name" />
                            </div>

                            <!-- Email -->
                            <div>
                                <Label for="email">Email Address</Label>
                                <Input
                                    id="email"
                                    v-model="form.email"
                                    type="email"
                                    required
                                />
                                <InputError :message="form.errors.email" />
                            </div>

                            <!-- Year -->
                            <div>
                                <Label for="year">Academic Year</Label>
                                <Select v-model="form.year" required>
                                    <SelectTrigger>
                                        <SelectValue placeholder="Select year" />
                                    </SelectTrigger>
                                    <SelectContent>
                                        <SelectItem :value="1">CP1 (1st Year - Common Core)</SelectItem>
                                        <SelectItem :value="2">CP2 (2nd Year - Common Core)</SelectItem>
                                        <SelectItem :value="3">3rd Year - Cycle Ingénieur</SelectItem>
                                        <SelectItem :value="4">4th Year - Cycle Ingénieur</SelectItem>
                                        <SelectItem :value="5">5th Year - Cycle Ingénieur</SelectItem>
                                    </SelectContent>
                                </Select>
                                <InputError :message="form.errors.year" />
                            </div>

                            <!-- Specialization (Years 3-5 only) -->
                            <div v-if="needsSpecialization">
                                <Label for="specialization">Specialization</Label>
                                <Select v-model="form.specialization" required>
                                    <SelectTrigger>
                                        <SelectValue placeholder="Select specialization" />
                                    </SelectTrigger>
                                    <SelectContent>
                                        <SelectItem
                                            v-for="spec in specializations"
                                            :key="spec.value"
                                            :value="spec.value"
                                        >
                                            {{ spec.label }}
                                        </SelectItem>
                                    </SelectContent>
                                </Select>
                                <InputError :message="form.errors.specialization" />
                            </div>

                            <!-- Track (GI Years 4-5 only) -->
                            <div v-if="needsTrack">
                                <Label for="track">Track (Génie Informatique Only)</Label>
                                <Select v-model="form.track" required>
                                    <SelectTrigger>
                                        <SelectValue placeholder="Select track" />
                                    </SelectTrigger>
                                    <SelectContent>
                                        <SelectItem
                                            v-for="track in tracks"
                                            :key="track.value"
                                            :value="track.value"
                                        >
                                            {{ track.label }}
                                        </SelectItem>
                                    </SelectContent>
                                </Select>
                                <InputError :message="form.errors.track" />
                            </div>

                            <!-- Submit Buttons -->
                            <div class="flex gap-4">
                                <Button
                                    type="submit"
                                    :disabled="form.processing"
                                >
                                    {{ form.processing ? 'Updating...' : 'Update Student' }}
                                </Button>
                                <Button
                                    type="button"
                                    variant="outline"
                                    @click="$inertia.visit(studentsIndex.url())"
                                >
                                    Cancel
                                </Button>
                            </div>
                        </form>
                    </CardContent>
                </Card>
            </div>
        </div>
    </AppLayout>
</template>
