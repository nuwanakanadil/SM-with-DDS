import PublicResultController from './PublicResultController'
import PublicTimetableController from './PublicTimetableController'
import ProfileController from './ProfileController'
import Auth from './Auth'
import Admin from './Admin'
import Student from './Student'
const Controllers = {
    PublicResultController: Object.assign(PublicResultController, PublicResultController),
PublicTimetableController: Object.assign(PublicTimetableController, PublicTimetableController),
ProfileController: Object.assign(ProfileController, ProfileController),
Auth: Object.assign(Auth, Auth),
Admin: Object.assign(Admin, Admin),
Student: Object.assign(Student, Student),
}

export default Controllers