import DashboardController from './DashboardController'
import TeacherController from './TeacherController'
import ModuleController from './ModuleController'
import StudentController from './StudentController'
import ScheduleController from './ScheduleController'
import AnnouncementController from './AnnouncementController'
import JustificationController from './JustificationController'
import AttendanceListController from './AttendanceListController'

const Admin = {
    DashboardController: Object.assign(DashboardController, DashboardController),
    TeacherController: Object.assign(TeacherController, TeacherController),
    ModuleController: Object.assign(ModuleController, ModuleController),
    StudentController: Object.assign(StudentController, StudentController),
    ScheduleController: Object.assign(ScheduleController, ScheduleController),
    AnnouncementController: Object.assign(AnnouncementController, AnnouncementController),
    JustificationController: Object.assign(JustificationController, JustificationController),
    AttendanceListController: Object.assign(AttendanceListController, AttendanceListController),
}

export default Admin