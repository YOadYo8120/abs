<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3'
import { Button } from '@/components/ui/button'
import { GraduationCap, Users, Calendar, BookOpen, FileText, Shield } from 'lucide-vue-next'
import { login } from '@/routes'
import { register } from '@/routes'
</script>

<template>
    <div class="min-h-screen bg-background">
        <Head title="Welcome to ABS Academic System" />

        <!-- Navigation -->
        <nav class="border-b bg-card/50 backdrop-blur-sm sticky top-0 z-50">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex h-16 items-center justify-between">
                    <div class="flex items-center gap-3">
                        <GraduationCap class="h-8 w-8 text-primary" />
                        <span class="text-xl font-bold">ABS Academic System</span>
                    </div>
                    <div class="flex items-center gap-4">
                        <Link :href="login.url()">
                            <Button variant="ghost">Log in</Button>
                        </Link>
                        <Link :href="register.url()">
                            <Button>Get Started</Button>
                        </Link>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Hero Section -->
        <section class="py-20 px-4">
            <div class="container mx-auto text-center max-w-4xl">
                <div class="flex justify-center mb-6">
                    <div class="bg-primary/10 rounded-full p-6">
                        <GraduationCap class="h-16 w-16 text-primary" />
                    </div>
                </div>
                <h1 class="text-5xl font-bold mb-6">
                    Academic Management System
                </h1>
                <p class="text-xl text-muted-foreground mb-8 leading-relaxed">
                    Comprehensive platform for managing students, attendance, schedules, and academic resources
                    at ENSA Safi engineering school.
                </p>
                <div class="flex gap-4 justify-center flex-wrap">
                    <Link :href="register.url()">
                        <Button size="lg" class="text-lg px-8">
                            <GraduationCap class="mr-2 h-5 w-5" />
                            Get Started
                        </Button>
                    </Link>
                    <Link :href="login.url()">
                        <Button size="lg" variant="outline" class="text-lg px-8">
                            <Shield class="mr-2 h-5 w-5" />
                            Sign In
                        </Button>
                    </Link>
                </div>
            </div>
        </section>

        <!-- Features Grid -->
        <section class="py-16 px-4 bg-muted/50">
            <div class="container mx-auto max-w-6xl">
                <h2 class="text-3xl font-bold text-center mb-12">Key Features</h2>
                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <!-- Student Management -->
                    <Card class="hover:shadow-lg transition-all">
                        <CardHeader>
                            <div class="bg-blue-500/10 w-12 h-12 rounded-lg flex items-center justify-center mb-4">
                                <Users class="h-6 w-6 text-blue-500" />
                            </div>
                            <CardTitle>Student Management</CardTitle>
                            <CardDescription>
                                Comprehensive student information system with profiles, specializations, and academic tracking
                            </CardDescription>
                        </CardHeader>
                    </Card>

                    <!-- Attendance Tracking -->
                    <Card class="hover:shadow-lg transition-all">
                        <CardHeader>
                            <div class="bg-green-500/10 w-12 h-12 rounded-lg flex items-center justify-center mb-4">
                                <FileText class="h-6 w-6 text-green-500" />
                            </div>
                            <CardTitle>Attendance Tracking</CardTitle>
                            <CardDescription>
                                Real-time attendance management with detailed reports and absence justifications
                            </CardDescription>
                        </CardHeader>
                    </Card>

                    <!-- Schedule Management -->
                    <Card class="hover:shadow-lg transition-all">
                        <CardHeader>
                            <div class="bg-purple-500/10 w-12 h-12 rounded-lg flex items-center justify-center mb-4">
                                <Calendar class="h-6 w-6 text-purple-500" />
                            </div>
                            <CardTitle>Schedule Management</CardTitle>
                            <CardDescription>
                                Organized class schedules with semester planning and session tracking
                            </CardDescription>
                        </CardHeader>
                    </Card>

                    <!-- Module Organization -->
                    <Card class="hover:shadow-lg transition-all">
                        <CardHeader>
                            <div class="bg-amber-500/10 w-12 h-12 rounded-lg flex items-center justify-center mb-4">
                                <BookOpen class="h-6 w-6 text-amber-500" />
                            </div>
                            <CardTitle>Module Organization</CardTitle>
                            <CardDescription>
                                Structured course modules with teacher assignments and year-based categorization
                            </CardDescription>
                        </CardHeader>
                    </Card>

                    <!-- Resource Sharing -->
                    <Card class="hover:shadow-lg transition-all">
                        <CardHeader>
                            <div class="bg-indigo-500/10 w-12 h-12 rounded-lg flex items-center justify-center mb-4">
                                <FileText class="h-6 w-6 text-indigo-500" />
                            </div>
                            <CardTitle>Resource Sharing</CardTitle>
                            <CardDescription>
                                Digital resource library for sharing course materials and study resources
                            </CardDescription>
                        </CardHeader>
                    </Card>

                    <!-- Role-Based Access -->
                    <Card class="hover:shadow-lg transition-all">
                        <CardHeader>
                            <div class="bg-rose-500/10 w-12 h-12 rounded-lg flex items-center justify-center mb-4">
                                <Shield class="h-6 w-6 text-rose-500" />
                            </div>
                            <CardTitle>Role-Based Access</CardTitle>
                            <CardDescription>
                                Secure access control for administrators, teachers, and students
                            </CardDescription>
                        </CardHeader>
                    </Card>
                </div>
            </div>
        </section>

        <!-- Call to Action -->
        <section class="py-20 px-4">
            <div class="container mx-auto max-w-3xl text-center">
                <h2 class="text-3xl font-bold mb-4">Ready to Get Started?</h2>
                <p class="text-muted-foreground mb-8 text-lg">
                    Join our academic community and streamline your educational management experience
                </p>
                <Link :href="register.url()">
                    <Button size="lg" class="text-lg px-8">
                        <GraduationCap class="mr-2 h-5 w-5" />
                        Create Account
                    </Button>
                </Link>
            </div>
        </section>

        <!-- Footer -->
        <footer class="border-t bg-muted/30 py-8 px-4">
            <div class="container mx-auto text-center">
                <div class="flex items-center justify-center gap-2 mb-4">
                    <GraduationCap class="h-6 w-6 text-primary" />
                    <span class="font-semibold">ABS Academic System</span>
                </div>
                <p class="text-sm text-muted-foreground">
                    © {{ new Date().getFullYear() }} ENSA Safi. All rights reserved.
                </p>
            </div>
        </footer>
    </div>
</template>
