import Auth from './Auth'
import Admin from './Admin'
import Teacher from './Teacher'
import Student from './Student'
import Settings from './Settings'

const Controllers = {
    Auth: Object.assign(Auth, Auth),
    Admin: Object.assign(Admin, Admin),
    Teacher: Object.assign(Teacher, Teacher),
    Student: Object.assign(Student, Student),
    Settings: Object.assign(Settings, Settings),
}

export default Controllers