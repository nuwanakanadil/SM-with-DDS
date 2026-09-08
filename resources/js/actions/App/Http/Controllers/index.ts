import PublicResultController from './PublicResultController'
import ProfileController from './ProfileController'
import Auth from './Auth'
import Admin from './Admin'
const Controllers = {
    PublicResultController: Object.assign(PublicResultController, PublicResultController),
ProfileController: Object.assign(ProfileController, ProfileController),
Auth: Object.assign(Auth, Auth),
Admin: Object.assign(Admin, Admin),
}

export default Controllers