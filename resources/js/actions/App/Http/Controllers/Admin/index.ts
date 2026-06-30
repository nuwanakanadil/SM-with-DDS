import AnalysisController from './AnalysisController'
import StudentController from './StudentController'
import AssessmentController from './AssessmentController'
import AssessmentResultController from './AssessmentResultController'
const Admin = {
    AnalysisController: Object.assign(AnalysisController, AnalysisController),
StudentController: Object.assign(StudentController, StudentController),
AssessmentController: Object.assign(AssessmentController, AssessmentController),
AssessmentResultController: Object.assign(AssessmentResultController, AssessmentResultController),
}

export default Admin