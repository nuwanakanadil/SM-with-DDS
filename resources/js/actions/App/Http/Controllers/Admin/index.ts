import DashboardController from './DashboardController'
import AnalysisController from './AnalysisController'
import DataQualityController from './DataQualityController'
import StudentController from './StudentController'
import AssessmentController from './AssessmentController'
import AssessmentResultController from './AssessmentResultController'
import StaffController from './StaffController'
const Admin = {
    DashboardController: Object.assign(DashboardController, DashboardController),
AnalysisController: Object.assign(AnalysisController, AnalysisController),
DataQualityController: Object.assign(DataQualityController, DataQualityController),
StudentController: Object.assign(StudentController, StudentController),
AssessmentController: Object.assign(AssessmentController, AssessmentController),
AssessmentResultController: Object.assign(AssessmentResultController, AssessmentResultController),
StaffController: Object.assign(StaffController, StaffController),
}

export default Admin