import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../wayfinder'
import schedules from './schedules'
import attendance from './attendance'
import announcements from './announcements'
import resources from './resources'
import attendanceList from './attendance-list'
/**
* @see \App\Http\Controllers\Teacher\DashboardController::dashboard
* @see app/Http/Controllers/Teacher/DashboardController.php:13
* @route '/teacher/dashboard'
*/
export const dashboard = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: dashboard.url(options),
    method: 'get',
})

dashboard.definition = {
    methods: ["get","head"],
    url: '/teacher/dashboard',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Teacher\DashboardController::dashboard
* @see app/Http/Controllers/Teacher/DashboardController.php:13
* @route '/teacher/dashboard'
*/
dashboard.url = (options?: RouteQueryOptions) => {
    return dashboard.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Teacher\DashboardController::dashboard
* @see app/Http/Controllers/Teacher/DashboardController.php:13
* @route '/teacher/dashboard'
*/
dashboard.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: dashboard.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Teacher\DashboardController::dashboard
* @see app/Http/Controllers/Teacher/DashboardController.php:13
* @route '/teacher/dashboard'
*/
dashboard.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: dashboard.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Teacher\DashboardController::dashboard
* @see app/Http/Controllers/Teacher/DashboardController.php:13
* @route '/teacher/dashboard'
*/
const dashboardForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: dashboard.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Teacher\DashboardController::dashboard
* @see app/Http/Controllers/Teacher/DashboardController.php:13
* @route '/teacher/dashboard'
*/
dashboardForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: dashboard.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Teacher\DashboardController::dashboard
* @see app/Http/Controllers/Teacher/DashboardController.php:13
* @route '/teacher/dashboard'
*/
dashboardForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: dashboard.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

dashboard.form = dashboardForm

const teacher = {
    dashboard: Object.assign(dashboard, dashboard),
    schedules: Object.assign(schedules, schedules),
    attendance: Object.assign(attendance, attendance),
    announcements: Object.assign(announcements, announcements),
    resources: Object.assign(resources, resources),
    attendanceList: Object.assign(attendanceList, attendanceList),
}

export default teacher