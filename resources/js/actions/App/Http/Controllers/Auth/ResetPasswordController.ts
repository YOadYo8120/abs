import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../../wayfinder'
/**
* @see \App\Http\Controllers\Auth\ResetPasswordController::showResetForm
* @see app/Http/Controllers/Auth/ResetPasswordController.php:18
* @route '/reset-password/{token}'
*/
export const showResetForm = (args: { token: string | number } | [token: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: showResetForm.url(args, options),
    method: 'get',
})

showResetForm.definition = {
    methods: ["get","head"],
    url: '/reset-password/{token}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Auth\ResetPasswordController::showResetForm
* @see app/Http/Controllers/Auth/ResetPasswordController.php:18
* @route '/reset-password/{token}'
*/
showResetForm.url = (args: { token: string | number } | [token: string | number ] | string | number, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { token: args }
    }

    if (Array.isArray(args)) {
        args = {
            token: args[0],
        }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
        token: args.token,
    }

    return showResetForm.definition.url
            .replace('{token}', parsedArgs.token.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Auth\ResetPasswordController::showResetForm
* @see app/Http/Controllers/Auth/ResetPasswordController.php:18
* @route '/reset-password/{token}'
*/
showResetForm.get = (args: { token: string | number } | [token: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: showResetForm.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Auth\ResetPasswordController::showResetForm
* @see app/Http/Controllers/Auth/ResetPasswordController.php:18
* @route '/reset-password/{token}'
*/
showResetForm.head = (args: { token: string | number } | [token: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: showResetForm.url(args, options),
    method: 'head',
})

/**
* @see \App\Http\Controllers\Auth\ResetPasswordController::showResetForm
* @see app/Http/Controllers/Auth/ResetPasswordController.php:18
* @route '/reset-password/{token}'
*/
const showResetFormForm = (args: { token: string | number } | [token: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: showResetForm.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Auth\ResetPasswordController::showResetForm
* @see app/Http/Controllers/Auth/ResetPasswordController.php:18
* @route '/reset-password/{token}'
*/
showResetFormForm.get = (args: { token: string | number } | [token: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: showResetForm.url(args, options),
    method: 'get',
})

/**
* @see \App\Http\Controllers\Auth\ResetPasswordController::showResetForm
* @see app/Http/Controllers/Auth/ResetPasswordController.php:18
* @route '/reset-password/{token}'
*/
showResetFormForm.head = (args: { token: string | number } | [token: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
    action: showResetForm.url(args, {
        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
            _method: 'HEAD',
            ...(options?.query ?? options?.mergeQuery ?? {}),
        }
    }),
    method: 'get',
})

showResetForm.form = showResetFormForm

/**
* @see \App\Http\Controllers\Auth\ResetPasswordController::reset
* @see app/Http/Controllers/Auth/ResetPasswordController.php:41
* @route '/reset-password'
*/
export const reset = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: reset.url(options),
    method: 'post',
})

reset.definition = {
    methods: ["post"],
    url: '/reset-password',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Auth\ResetPasswordController::reset
* @see app/Http/Controllers/Auth/ResetPasswordController.php:41
* @route '/reset-password'
*/
reset.url = (options?: RouteQueryOptions) => {
    return reset.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Auth\ResetPasswordController::reset
* @see app/Http/Controllers/Auth/ResetPasswordController.php:41
* @route '/reset-password'
*/
reset.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: reset.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Auth\ResetPasswordController::reset
* @see app/Http/Controllers/Auth/ResetPasswordController.php:41
* @route '/reset-password'
*/
const resetForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: reset.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Auth\ResetPasswordController::reset
* @see app/Http/Controllers/Auth/ResetPasswordController.php:41
* @route '/reset-password'
*/
resetForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: reset.url(options),
    method: 'post',
})

reset.form = resetForm

const ResetPasswordController = { showResetForm, reset }

export default ResetPasswordController