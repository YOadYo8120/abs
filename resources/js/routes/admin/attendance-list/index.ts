import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../wayfinder'
/**
* @see \App\Http\Controllers\Admin\AttendanceListController::index
* @see app/Http/Controllers/Admin/AttendanceListController.php:21
* @route '/admin/attendance-list'
*/
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/admin/attendance-list',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Admin\AttendanceListController::index
* @see app/Http/Controllers/Admin/AttendanceListController.php:21
* @route '/admin/attendance-list'
*/
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\AttendanceListController::index
* @see app/Http/Controllers/Admin/AttendanceListController.php:21
* @route '/admin/attendance-list'
*/
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Admin\AttendanceListController::index
* @see app/Http/Controllers/Admin/AttendanceListController.php:21
* @route '/admin/attendance-list'
*/
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Admin\AttendanceListController::index
* @see app/Http/Controllers/Admin/AttendanceListController.php:21
* @route '/admin/attendance-list'
*/
const indexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Admin\AttendanceListController::index
* @see app/Http/Controllers/Admin/AttendanceListController.php:21
* @route '/admin/attendance-list'
*/
indexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Admin\AttendanceListController::index
* @see app/Http/Controllers/Admin/AttendanceListController.php:21
* @route '/admin/attendance-list'
*/
indexForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

index.form = indexForm

/**
* @see \App\Http\Controllers\Admin\AttendanceListController::generate
* @see app/Http/Controllers/Admin/AttendanceListController.php:54
* @route '/admin/attendance-list/generate'
*/
export const generate = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: generate.url(options),
    method: 'get',
})

generate.definition = {
    methods: ["get","head"],
    url: '/admin/attendance-list/generate',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Admin\AttendanceListController::generate
* @see app/Http/Controllers/Admin/AttendanceListController.php:54
* @route '/admin/attendance-list/generate'
*/
generate.url = (options?: RouteQueryOptions) => {
    return generate.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\AttendanceListController::generate
* @see app/Http/Controllers/Admin/AttendanceListController.php:54
* @route '/admin/attendance-list/generate'
*/
generate.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: generate.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Admin\AttendanceListController::generate
* @see app/Http/Controllers/Admin/AttendanceListController.php:54
* @route '/admin/attendance-list/generate'
*/
generate.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: generate.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Admin\AttendanceListController::generate
* @see app/Http/Controllers/Admin/AttendanceListController.php:54
* @route '/admin/attendance-list/generate'
*/
const generateForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: generate.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Admin\AttendanceListController::generate
* @see app/Http/Controllers/Admin/AttendanceListController.php:54
* @route '/admin/attendance-list/generate'
*/
generateForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: generate.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Admin\AttendanceListController::generate
* @see app/Http/Controllers/Admin/AttendanceListController.php:54
* @route '/admin/attendance-list/generate'
*/
generateForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: generate.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

generate.form = generateForm

const attendanceList = {
    index: Object.assign(index, index),
    generate: Object.assign(generate, generate),
}

export default attendanceList