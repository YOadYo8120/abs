import ForgotPasswordController from './ForgotPasswordController'
import ResetPasswordController from './ResetPasswordController'

const Auth = {
    ForgotPasswordController: Object.assign(ForgotPasswordController, ForgotPasswordController),
    ResetPasswordController: Object.assign(ResetPasswordController, ResetPasswordController),
}

export default Auth