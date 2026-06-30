import DashboardController from './DashboardController'
import MyResultController from './MyResultController'
import PlacementController from './PlacementController'
const Student = {
    DashboardController: Object.assign(DashboardController, DashboardController),
MyResultController: Object.assign(MyResultController, MyResultController),
PlacementController: Object.assign(PlacementController, PlacementController),
}

export default Student