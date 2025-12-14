import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../../wayfinder'
/**
* @see \App\Http\Controllers\Admin\JustificationController::index
* @see app/Http/Controllers/Admin/JustificationController.php:18
* @route '/admin/justifications'
*/
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/admin/justifications',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Admin\JustificationController::index
* @see app/Http/Controllers/Admin/JustificationController.php:18
* @route '/admin/justifications'
*/
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\JustificationController::index
* @see app/Http/Controllers/Admin/JustificationController.php:18
* @route '/admin/justifications'
*/
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Admin\JustificationController::index
* @see app/Http/Controllers/Admin/JustificationController.php:18
* @route '/admin/justifications'
*/
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Admin\JustificationController::index
* @see app/Http/Controllers/Admin/JustificationController.php:18
* @route '/admin/justifications'
*/
const indexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Admin\JustificationController::index
* @see app/Http/Controllers/Admin/JustificationController.php:18
* @route '/admin/justifications'
*/
indexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Admin\JustificationController::index
* @see app/Http/Controllers/Admin/JustificationController.php:18
* @route '/admin/justifications'
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
* @see \App\Http\Controllers\Admin\JustificationController::show
* @see app/Http/Controllers/Admin/JustificationController.php:56
* @route '/admin/justifications/{justification}'
*/
export const show = (args: { justification: string | number } | [justification: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})

show.definition = {
    methods: ["get","head"],
    url: '/admin/justifications/{justification}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Admin\JustificationController::show
* @see app/Http/Controllers/Admin/JustificationController.php:56
* @route '/admin/justifications/{justification}'
*/
show.url = (args: { justification: string | number } | [justification: string | number ] | string | number, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { justification: args }
    }

    if (Array.isArray(args)) {
        args = {
            justification: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        justification: args.justification,
    }

    return show.definition.url
            .replace('{justification}', parsedArgs.justification.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\JustificationController::show
* @see app/Http/Controllers/Admin/JustificationController.php:56
* @route '/admin/justifications/{justification}'
*/
show.get = (args: { justification: string | number } | [justification: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Admin\JustificationController::show
* @see app/Http/Controllers/Admin/JustificationController.php:56
* @route '/admin/justifications/{justification}'
*/
show.head = (args: { justification: string | number } | [justification: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: show.url(args, options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Admin\JustificationController::show
* @see app/Http/Controllers/Admin/JustificationController.php:56
* @route '/admin/justifications/{justification}'
*/
const showForm = (args: { justification: string | number } | [justification: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: show.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Admin\JustificationController::show
* @see app/Http/Controllers/Admin/JustificationController.php:56
* @route '/admin/justifications/{justification}'
*/
showForm.get = (args: { justification: string | number } | [justification: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: show.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Admin\JustificationController::show
* @see app/Http/Controllers/Admin/JustificationController.php:56
* @route '/admin/justifications/{justification}'
*/
showForm.head = (args: { justification: string | number } | [justification: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: show.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

show.form = showForm

/**
* @see \App\Http\Controllers\Admin\JustificationController::update
* @see app/Http/Controllers/Admin/JustificationController.php:100
* @route '/admin/justifications/{justification}'
*/
export const update = (args: { justification: string | number } | [justification: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: update.url(args, options),
    method: 'patch',
})

update.definition = {
    methods: ["patch"],
    url: '/admin/justifications/{justification}',
} satisfies RouteDefinition<["patch"]>

/**
* @see \App\Http\Controllers\Admin\JustificationController::update
* @see app/Http/Controllers/Admin/JustificationController.php:100
* @route '/admin/justifications/{justification}'
*/
update.url = (args: { justification: string | number } | [justification: string | number ] | string | number, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { justification: args }
    }

    if (Array.isArray(args)) {
        args = {
            justification: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        justification: args.justification,
    }

    return update.definition.url
            .replace('{justification}', parsedArgs.justification.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\JustificationController::update
* @see app/Http/Controllers/Admin/JustificationController.php:100
* @route '/admin/justifications/{justification}'
*/
update.patch = (args: { justification: string | number } | [justification: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: update.url(args, options),
    method: 'patch',
})

/**
* @see \App\Http\Controllers\Admin\JustificationController::update
* @see app/Http/Controllers/Admin/JustificationController.php:100
* @route '/admin/justifications/{justification}'
*/
const updateForm = (args: { justification: string | number } | [justification: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: update.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'PATCH',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Admin\JustificationController::update
* @see app/Http/Controllers/Admin/JustificationController.php:100
* @route '/admin/justifications/{justification}'
*/
updateForm.patch = (args: { justification: string | number } | [justification: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: update.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'PATCH',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

update.form = updateForm

/**
* @see \App\Http\Controllers\Admin\JustificationController::download
* @see app/Http/Controllers/Admin/JustificationController.php:142
* @route '/admin/justifications/{file}/download'
*/
export const download = (args: { file: string | number } | [file: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: download.url(args, options),
    method: 'get',
})

download.definition = {
    methods: ["get","head"],
    url: '/admin/justifications/{file}/download',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Admin\JustificationController::download
* @see app/Http/Controllers/Admin/JustificationController.php:142
* @route '/admin/justifications/{file}/download'
*/
download.url = (args: { file: string | number } | [file: string | number ] | string | number, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { file: args }
    }

    if (Array.isArray(args)) {
        args = {
            file: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        file: args.file,
    }

    return download.definition.url
            .replace('{file}', parsedArgs.file.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\JustificationController::download
* @see app/Http/Controllers/Admin/JustificationController.php:142
* @route '/admin/justifications/{file}/download'
*/
download.get = (args: { file: string | number } | [file: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: download.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Admin\JustificationController::download
* @see app/Http/Controllers/Admin/JustificationController.php:142
* @route '/admin/justifications/{file}/download'
*/
download.head = (args: { file: string | number } | [file: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: download.url(args, options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Admin\JustificationController::download
* @see app/Http/Controllers/Admin/JustificationController.php:142
* @route '/admin/justifications/{file}/download'
*/
const downloadForm = (args: { file: string | number } | [file: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: download.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Admin\JustificationController::download
* @see app/Http/Controllers/Admin/JustificationController.php:142
* @route '/admin/justifications/{file}/download'
*/
downloadForm.get = (args: { file: string | number } | [file: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: download.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Admin\JustificationController::download
* @see app/Http/Controllers/Admin/JustificationController.php:142
* @route '/admin/justifications/{file}/download'
*/
downloadForm.head = (args: { file: string | number } | [file: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: download.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

download.form = downloadForm

const JustificationController = { index, show, update, download }

export default JustificationController