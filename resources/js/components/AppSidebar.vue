<script setup lang="ts">
import NavFooter from '@/components/NavFooter.vue';
import NavMain from '@/components/NavMain.vue';
import NavUser from '@/components/NavUser.vue';
import {
    Sidebar,
    SidebarContent,
    SidebarFooter,
    SidebarHeader,
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
} from '@/components/ui/sidebar';
import { dashboard } from '@/routes';
import { type NavItem } from '@/types';
import { Link, usePage } from '@inertiajs/vue3';
import { LayoutGrid, Users, BookOpen, Calendar, Megaphone, FileText, Upload, ClipboardCheck, GraduationCap } from 'lucide-vue-next';
import { computed } from 'vue';
import AppLogo from './AppLogo.vue';

const page = usePage();
const userRole = computed(() => page.props.auth?.user?.role);

const mainNavItems = computed<NavItem[]>(() => {
    const baseItems: NavItem[] = [
        {
            title: 'Dashboard',
            href: dashboard(),
            icon: LayoutGrid,
        },
    ];

    // Admin navigation
    if (userRole.value === 'admin') {
        return [
            ...baseItems,
            {
                title: 'Students',
                href: '/admin/students',
                icon: GraduationCap,
            },
            {
                title: 'Teachers',
                href: '/admin/teachers',
                icon: Users,
            },
            {
                title: 'Modules',
                href: '/admin/modules',
                icon: BookOpen,
            },
            {
                title: 'Schedules',
                href: '/admin/schedules',
                icon: Calendar,
            },
            {
                title: 'Announcements',
                href: '/admin/announcements',
                icon: Megaphone,
            },
            {
                title: 'Justifications',
                href: '/admin/justifications',
                icon: FileText,
            },
            {
                title: 'Attendance Lists',
                href: '/admin/attendance-list',
                icon: ClipboardCheck,
            },
        ];
    }

    // Teacher navigation
    if (userRole.value === 'teacher') {
        return [
            ...baseItems,
            {
                title: 'My Schedules',
                href: '/teacher/schedules',
                icon: Calendar,
            },
            {
                title: 'Attendance Lists',
                href: '/teacher/attendance-list',
                icon: ClipboardCheck,
            },
            {
                title: 'Announcements',
                href: '/teacher/announcements',
                icon: Megaphone,
            },
            {
                title: 'Resources',
                href: '/teacher/resources',
                icon: Upload,
            },
        ];
    }

    // Student navigation
    if (userRole.value === 'student') {
        return [
            ...baseItems,
            {
                title: 'Justifications',
                href: '/student/justifications',
                icon: FileText,
            },
        ];
    }

    return baseItems;
});

const footerNavItems: NavItem[] = [];
</script>

<template>
    <Sidebar collapsible="icon" variant="inset">
        <SidebarHeader>
            <SidebarMenu>
                <SidebarMenuItem>
                    <SidebarMenuButton size="lg" as-child>
                        <Link :href="dashboard()">
                            <AppLogo />
                        </Link>
                    </SidebarMenuButton>
                </SidebarMenuItem>
            </SidebarMenu>
        </SidebarHeader>

        <SidebarContent>
            <NavMain :items="mainNavItems" />
        </SidebarContent>

        <SidebarFooter>
            <NavFooter :items="footerNavItems" />
            <NavUser />
        </SidebarFooter>
    </Sidebar>
    <slot />
</template>
