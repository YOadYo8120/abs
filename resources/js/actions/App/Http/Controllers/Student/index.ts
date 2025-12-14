import DashboardController from './DashboardController'
import JustificationController from './JustificationController'

const Student = {
    DashboardController: Object.assign(DashboardController, DashboardController),
    JustificationController: Object.assign(JustificationController, JustificationController),
}

export default Student