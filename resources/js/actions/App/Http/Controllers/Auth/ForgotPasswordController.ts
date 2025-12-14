import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../../wayfinder'
/**
* @see \App\Http\Controllers\Auth\ForgotPasswordController::sendResetLinkEmail
* @see app/Http/Controllers/Auth/ForgotPasswordController.php:18
* @route '/forgot-password'
*/
export const sendResetLinkEmail = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: sendResetLinkEmail.url(options),
    method: 'post',
})

sendResetLinkEmail.definition = {
    methods: ["post"],
    url: '/forgot-password',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Auth\ForgotPasswordController::sendResetLinkEmail
* @see app/Http/Controllers/Auth/ForgotPasswordController.php:18
* @route '/forgot-password'
*/
sendResetLinkEmail.url = (options?: RouteQueryOptions) => {
    return sendResetLinkEmail.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Auth\ForgotPasswordController::sendResetLinkEmail
* @see app/Http/Controllers/Auth/ForgotPasswordController.php:18
* @route '/forgot-password'
*/
sendResetLinkEmail.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: sendResetLinkEmail.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Auth\ForgotPasswordController::sendResetLinkEmail
* @see app/Http/Controllers/Auth/ForgotPasswordController.php:18
* @route '/forgot-password'
*/
const sendResetLinkEmailForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: sendResetLinkEmail.url(options),
    method: 'post',
})

/**
* @see \App\Http\Controllers\Auth\ForgotPasswordController::sendResetLinkEmail
* @see app/Http/Controllers/Auth/ForgotPasswordController.php:18
* @route '/forgot-password'
*/
sendResetLinkEmailForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
    action: sendResetLinkEmail.url(options),
    method: 'post',
})

sendResetLinkEmail.form = sendResetLinkEmailForm

const ForgotPasswordController = { sendResetLinkEmail }

export default ForgotPasswordController