import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../wayfinder'
/**
* @see \App\Http\Controllers\Teacher\ResourceController::download
* @see app/Http/Controllers/Teacher/ResourceController.php:154
* @route '/student/resources/{resource}/download'
*/
export const download = (args: { resource: number | { id: number } } | [resource: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: download.url(args, options),
    method: 'get',
})

download.definition = {
    methods: ["get","head"],
    url: '/student/resources/{resource}/download',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Teacher\ResourceController::download
* @see app/Http/Controllers/Teacher/ResourceController.php:154
* @route '/student/resources/{resource}/download'
*/
download.url = (args: { resource: number | { id: number } } | [resource: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { resource: args }
    }

    if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
        args = { resource: args.id }
    }

    if (Array.isArray(args)) {
        args = {
            resource: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        resource: typeof args.resource === 'object'
        ? args.resource.id
        : args.resource,
    }

    return download.definition.url
            .replace('{resource}', parsedArgs.resource.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Teacher\ResourceController::download
* @see app/Http/Controllers/Teacher/ResourceController.php:154
* @route '/student/resources/{resource}/download'
*/
download.get = (args: { resource: number | { id: number } } | [resource: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: download.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Teacher\ResourceController::download
* @see app/Http/Controllers/Teacher/ResourceController.php:154
* @route '/student/resources/{resource}/download'
*/
download.head = (args: { resource: number | { id: number } } | [resource: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: download.url(args, options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Teacher\ResourceController::download
* @see app/Http/Controllers/Teacher/ResourceController.php:154
* @route '/student/resources/{resource}/download'
*/
const downloadForm = (args: { resource: number | { id: number } } | [resource: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: download.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Teacher\ResourceController::download
* @see app/Http/Controllers/Teacher/ResourceController.php:154
* @route '/student/resources/{resource}/download'
*/
downloadForm.get = (args: { resource: number | { id: number } } | [resource: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: download.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Teacher\ResourceController::download
* @see app/Http/Controllers/Teacher/ResourceController.php:154
* @route '/student/resources/{resource}/download'
*/
downloadForm.head = (args: { resource: number | { id: number } } | [resource: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: download.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

download.form = downloadForm

const resources = {
    download: Object.assign(download, download),
}

export default resources