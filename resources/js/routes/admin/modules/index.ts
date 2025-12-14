import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../wayfinder'
/**
* @see \App\Http\Controllers\Admin\ModuleController::index
* @see app/Http/Controllers/Admin/ModuleController.php:16
* @route '/admin/modules'
*/
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/admin/modules',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Admin\ModuleController::index
* @see app/Http/Controllers/Admin/ModuleController.php:16
* @route '/admin/modules'
*/
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\ModuleController::index
* @see app/Http/Controllers/Admin/ModuleController.php:16
* @route '/admin/modules'
*/
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Admin\ModuleController::index
* @see app/Http/Controllers/Admin/ModuleController.php:16
* @route '/admin/modules'
*/
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Admin\ModuleController::index
* @see app/Http/Controllers/Admin/ModuleController.php:16
* @route '/admin/modules'
*/
const indexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Admin\ModuleController::index
* @see app/Http/Controllers/Admin/ModuleController.php:16
* @route '/admin/modules'
*/
indexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: index.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Admin\ModuleController::index
* @see app/Http/Controllers/Admin/ModuleController.php:16
* @route '/admin/modules'
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
* @see \App\Http\Controllers\Admin\ModuleController::create
* @see app/Http/Controllers/Admin/ModuleController.php:31
* @route '/admin/modules/create'
*/
export const create = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: create.url(options),
    method: 'get',
})

create.definition = {
    methods: ["get","head"],
    url: '/admin/modules/create',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Admin\ModuleController::create
* @see app/Http/Controllers/Admin/ModuleController.php:31
* @route '/admin/modules/create'
*/
create.url = (options?: RouteQueryOptions) => {
    return create.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\ModuleController::create
* @see app/Http/Controllers/Admin/ModuleController.php:31
* @route '/admin/modules/create'
*/
create.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: create.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Admin\ModuleController::create
* @see app/Http/Controllers/Admin/ModuleController.php:31
* @route '/admin/modules/create'
*/
create.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: create.url(options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Admin\ModuleController::create
* @see app/Http/Controllers/Admin/ModuleController.php:31
* @route '/admin/modules/create'
*/
const createForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: create.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Admin\ModuleController::create
* @see app/Http/Controllers/Admin/ModuleController.php:31
* @route '/admin/modules/create'
*/
createForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: create.url(options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Admin\ModuleController::create
* @see app/Http/Controllers/Admin/ModuleController.php:31
* @route '/admin/modules/create'
*/
createForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: create.url({
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

create.form = createForm

/**
* @see \App\Http\Controllers\Admin\ModuleController::store
* @see app/Http/Controllers/Admin/ModuleController.php:45
* @route '/admin/modules'
*/
export const store = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

store.definition = {
    methods: ["post"],
    url: '/admin/modules',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Admin\ModuleController::store
* @see app/Http/Controllers/Admin/ModuleController.php:45
* @route '/admin/modules'
*/
store.url = (options?: RouteQueryOptions) => {
    return store.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\ModuleController::store
* @see app/Http/Controllers/Admin/ModuleController.php:45
* @route '/admin/modules'
*/
store.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Admin\ModuleController::store
* @see app/Http/Controllers/Admin/ModuleController.php:45
* @route '/admin/modules'
*/
const storeForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: store.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Admin\ModuleController::store
* @see app/Http/Controllers/Admin/ModuleController.php:45
* @route '/admin/modules'
*/
storeForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: store.url(options),
    method: 'post',
})

store.form = storeForm

/**
* @see \App\Http\Controllers\Admin\ModuleController::show
* @see app/Http/Controllers/Admin/ModuleController.php:66
* @route '/admin/modules/{module}'
*/
export const show = (args: { module: number | { id: number } } | [module: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})

show.definition = {
    methods: ["get","head"],
    url: '/admin/modules/{module}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Admin\ModuleController::show
* @see app/Http/Controllers/Admin/ModuleController.php:66
* @route '/admin/modules/{module}'
*/
show.url = (args: { module: number | { id: number } } | [module: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { module: args }
    }

    if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
        args = { module: args.id }
    }

    if (Array.isArray(args)) {
        args = {
            module: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        module: typeof args.module === 'object'
        ? args.module.id
        : args.module,
    }

    return show.definition.url
            .replace('{module}', parsedArgs.module.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\ModuleController::show
* @see app/Http/Controllers/Admin/ModuleController.php:66
* @route '/admin/modules/{module}'
*/
show.get = (args: { module: number | { id: number } } | [module: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Admin\ModuleController::show
* @see app/Http/Controllers/Admin/ModuleController.php:66
* @route '/admin/modules/{module}'
*/
show.head = (args: { module: number | { id: number } } | [module: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: show.url(args, options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Admin\ModuleController::show
* @see app/Http/Controllers/Admin/ModuleController.php:66
* @route '/admin/modules/{module}'
*/
const showForm = (args: { module: number | { id: number } } | [module: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: show.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Admin\ModuleController::show
* @see app/Http/Controllers/Admin/ModuleController.php:66
* @route '/admin/modules/{module}'
*/
showForm.get = (args: { module: number | { id: number } } | [module: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: show.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Admin\ModuleController::show
* @see app/Http/Controllers/Admin/ModuleController.php:66
* @route '/admin/modules/{module}'
*/
showForm.head = (args: { module: number | { id: number } } | [module: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
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
* @see \App\Http\Controllers\Admin\ModuleController::edit
* @see app/Http/Controllers/Admin/ModuleController.php:78
* @route '/admin/modules/{module}/edit'
*/
export const edit = (args: { module: number | { id: number } } | [module: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: edit.url(args, options),
    method: 'get',
})

edit.definition = {
    methods: ["get","head"],
    url: '/admin/modules/{module}/edit',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Admin\ModuleController::edit
* @see app/Http/Controllers/Admin/ModuleController.php:78
* @route '/admin/modules/{module}/edit'
*/
edit.url = (args: { module: number | { id: number } } | [module: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { module: args }
    }

    if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
        args = { module: args.id }
    }

    if (Array.isArray(args)) {
        args = {
            module: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        module: typeof args.module === 'object'
        ? args.module.id
        : args.module,
    }

    return edit.definition.url
            .replace('{module}', parsedArgs.module.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\ModuleController::edit
* @see app/Http/Controllers/Admin/ModuleController.php:78
* @route '/admin/modules/{module}/edit'
*/
edit.get = (args: { module: number | { id: number } } | [module: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: edit.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Admin\ModuleController::edit
* @see app/Http/Controllers/Admin/ModuleController.php:78
* @route '/admin/modules/{module}/edit'
*/
edit.head = (args: { module: number | { id: number } } | [module: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: edit.url(args, options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Admin\ModuleController::edit
* @see app/Http/Controllers/Admin/ModuleController.php:78
* @route '/admin/modules/{module}/edit'
*/
const editForm = (args: { module: number | { id: number } } | [module: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: edit.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Admin\ModuleController::edit
* @see app/Http/Controllers/Admin/ModuleController.php:78
* @route '/admin/modules/{module}/edit'
*/
editForm.get = (args: { module: number | { id: number } } | [module: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: edit.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Admin\ModuleController::edit
* @see app/Http/Controllers/Admin/ModuleController.php:78
* @route '/admin/modules/{module}/edit'
*/
editForm.head = (args: { module: number | { id: number } } | [module: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: edit.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

edit.form = editForm

/**
* @see \App\Http\Controllers\Admin\ModuleController::update
* @see app/Http/Controllers/Admin/ModuleController.php:93
* @route '/admin/modules/{module}'
*/
export const update = (args: { module: number | { id: number } } | [module: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})

update.definition = {
    methods: ["put","patch"],
    url: '/admin/modules/{module}',
} satisfies RouteDefinition<["put","patch"]>

/**
* @see \App\Http\Controllers\Admin\ModuleController::update
* @see app/Http/Controllers/Admin/ModuleController.php:93
* @route '/admin/modules/{module}'
*/
update.url = (args: { module: number | { id: number } } | [module: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { module: args }
    }

    if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
        args = { module: args.id }
    }

    if (Array.isArray(args)) {
        args = {
            module: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        module: typeof args.module === 'object'
        ? args.module.id
        : args.module,
    }

    return update.definition.url
            .replace('{module}', parsedArgs.module.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\ModuleController::update
* @see app/Http/Controllers/Admin/ModuleController.php:93
* @route '/admin/modules/{module}'
*/
update.put = (args: { module: number | { id: number } } | [module: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})

/**
* @see \App\Http\Controllers\Admin\ModuleController::update
* @see app/Http/Controllers/Admin/ModuleController.php:93
* @route '/admin/modules/{module}'
*/
update.patch = (args: { module: number | { id: number } } | [module: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: update.url(args, options),
    method: 'patch',
})

/**
* @see \App\Http\Controllers\Admin\ModuleController::update
* @see app/Http/Controllers/Admin/ModuleController.php:93
* @route '/admin/modules/{module}'
*/
const updateForm = (args: { module: number | { id: number } } | [module: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: update.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'PUT',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Admin\ModuleController::update
* @see app/Http/Controllers/Admin/ModuleController.php:93
* @route '/admin/modules/{module}'
*/
updateForm.put = (args: { module: number | { id: number } } | [module: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: update.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'PUT',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Admin\ModuleController::update
* @see app/Http/Controllers/Admin/ModuleController.php:93
* @route '/admin/modules/{module}'
*/
updateForm.patch = (args: { module: number | { id: number } } | [module: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
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
* @see \App\Http\Controllers\Admin\ModuleController::destroy
* @see app/Http/Controllers/Admin/ModuleController.php:114
* @route '/admin/modules/{module}'
*/
export const destroy = (args: { module: number | { id: number } } | [module: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

destroy.definition = {
    methods: ["delete"],
    url: '/admin/modules/{module}',
} satisfies RouteDefinition<["delete"]>

/**
* @see \App\Http\Controllers\Admin\ModuleController::destroy
* @see app/Http/Controllers/Admin/ModuleController.php:114
* @route '/admin/modules/{module}'
*/
destroy.url = (args: { module: number | { id: number } } | [module: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { module: args }
    }

    if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
        args = { module: args.id }
    }

    if (Array.isArray(args)) {
        args = {
            module: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        module: typeof args.module === 'object'
        ? args.module.id
        : args.module,
    }

    return destroy.definition.url
            .replace('{module}', parsedArgs.module.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\ModuleController::destroy
* @see app/Http/Controllers/Admin/ModuleController.php:114
* @route '/admin/modules/{module}'
*/
destroy.delete = (args: { module: number | { id: number } } | [module: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

/**
* @see \App\Http\Controllers\Admin\ModuleController::destroy
* @see app/Http/Controllers/Admin/ModuleController.php:114
* @route '/admin/modules/{module}'
*/
const destroyForm = (args: { module: number | { id: number } } | [module: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: destroy.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'DELETE',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Admin\ModuleController::destroy
* @see app/Http/Controllers/Admin/ModuleController.php:114
* @route '/admin/modules/{module}'
*/
destroyForm.delete = (args: { module: number | { id: number } } | [module: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: destroy.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'DELETE',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'post',
})

destroy.form = destroyForm

const modules = {
    index: Object.assign(index, index),
    create: Object.assign(create, create),
    store: Object.assign(store, store),
    show: Object.assign(show, show),
    edit: Object.assign(edit, edit),
    update: Object.assign(update, update),
    destroy: Object.assign(destroy, destroy),
}

export default modules