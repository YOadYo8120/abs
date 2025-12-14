import DashboardController from './DashboardController'
import ScheduleController from './ScheduleController'
import AttendanceController from './AttendanceController'
import AnnouncementController from './AnnouncementController'
import ResourceController from './ResourceController'
import AttendanceListController from './AttendanceListController'

const Teacher = {
    DashboardController: Object.assign(DashboardController, DashboardController),
    ScheduleController: Object.assign(ScheduleController, ScheduleController),
    AttendanceController: Object.assign(AttendanceController, AttendanceController),
    AnnouncementController: Object.assign(AnnouncementController, AnnouncementController),
    ResourceController: Object.assign(ResourceController, ResourceController),
    AttendanceListController: Object.assign(AttendanceListController, AttendanceListController),
}

export default Teacher