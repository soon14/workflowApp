<?php

use Phalcon\Mvc\View;

class IndexController extends BaseController
{

    public $workflow = 3;
    public $step = 7;
    public $completeStatuses = array("complete", "abandoned");

    public function getProjectHeaderAction ($projectId)
    {
        $this->view->setRenderLevel (View::LEVEL_ACTION_VIEW);
        $objSave = new Save ($_SESSION['selectedRequest']);
        $objUser = (new \BusinessModel\UsersFactory())->getUser ($_SESSION['user']['usrid']);

        $workflowId = $objSave->object['workflow_data']['elements'][$projectId]['workflow_id'];

        $objWorkflow = new Workflow (null, $objSave);

        //echo $objWorkflow->getProDynaforms();

        $objStep = $objWorkflow->getNextStep ();

        $this->view->steps = $objWorkflow->getStepsForWorkflow ();

        if ( !empty ($objStep) && is_numeric ($objStep->getStepId ()) )
        {
            $this->view->statusId = $objStep->getStepId ();
        }

        $objSave = new Save ($projectId);

        $objForm = new \BusinessModel\Form();
        $html = $objForm->buildFormForStep ($objStep, $objUser, $projectId);

        $this->view->html = $html;
        $this->view->blComplete = false;
    }

    public function checkToMove ()
    {
        $objSave = new Save ($_SESSION['selectedRequest']);

        if ( isset ($objSave->object['audit_data']) )
        {
            $arrElements = $objSave->object['audit_data']['elements'];
            $blIncomplete = 0;

            $count = count ($arrElements);

            foreach ($arrElements as $elementId => $arrElement) {

                $currentStep = $objSave->object['workflow_data']['elements'][$elementId]['current_step'];

                $objSteps = new WorkflowStep ($currentStep);

                if ( $objSteps->getNextStepId () == 0 )
                {
                    $blIncomplete++;
                }
            }

            if ( $blIncomplete != $count || $count == 0 )
            {
                return false;
            }
            else
            {
                return true;
            }
        }
        else
        {
            return false;
        }
    }

    public function changeStatusAction ($projectId, $moveTo)
    {
        $this->view->disable ();
        $objSave = new Save ($projectId);
        $arrData = array();

        $status = '';

        $arrErrors = array();

        if ( $moveTo == 4 )
        {
            if ( empty ($this->request->getPost ("reason", "string")) )
            {
                $arrErrors[] = "reasonWarning";
            }
        }

//        if($moveTo == 5) {
//            if(empty($_REQUEST["priority"]) || $_REQUEST['priority'] == "null") {
//                 $arrErrors[] = "priorityWarning";
//            }
//        }

        if ( $moveTo == 6 )
        {
            if ( empty ($_REQUEST['assignedTo']) )
            {
                $arrErrors[] = "assignWarning";
            }
        }

        if ( !empty ($arrErrors) )
        {
            echo json_encode ($arrErrors);
            return FALSE;
        }

        if ( $moveTo == 5 )
        {
            $arrData['completed_by'] = $_SESSION['user']['username'];
            $arrData['completed_date'] = date ("Y-m-d H:i:s");
            $arrData['project_status'] = 3;
            $status = "COMPLETED";
        }

        if ( $moveTo == 4 )
        {
            $arrData['rejection_reason'] = $this->request->getPost ("reason", "string");
            $arrData['project_status'] = 4;
            $arrData['date_rejected'] = date ("Y-m-d H:i:s");
            $arrData['rejected_by'] = $_SESSION['user']['username'];
            $status = "REJECTED";
        }

        if ( $moveTo == 6 )
        {
            $arrData['assigned_for'] = implode (",", $_REQUEST['assignedTo']);
        }

        $arrData['name'] = $objSave->object['step_data']['job']['name'];
        $arrData['priority'] = $objSave->object['step_data']['job']['priority'];
        $arrData['dueDate'] = $objSave->object['step_data']['job']['dueDate'];

        $objUser = (new \BusinessModel\UsersFactory())->getUser ($_SESSION['user']['usrid']);

        $objStep = new WorkflowStep (null, $objSave);
        $objStep->save ($objSave, $arrData, $objUser);
        $objStep->complete ($objSave, array(
            "dateCompleted" => date ("Y-m-d H:i:s"),
            "status" => $status,
            "claimed" => $_SESSION['user']['username']
                ), $objUser
        );
    }

    public function getProjectAction ($projectId, $openTab = 'project')
    {
        $this->view->setRenderLevel (View::LEVEL_ACTION_VIEW);
        $objProjects = new Save ($projectId);
        $arrPriorities = $objProjects->getPriorities ();
        $priorites = array();

        foreach ($arrPriorities as $arrPriority) {
            $priorites[$arrPriority['id']]['style'] = $arrPriority['style'];
            $priorites[$arrPriority['id']]['name'] = $arrPriority['name'];
        }

        $objAttachments = new \BusinessModel\Attachment();
        $this->view->attachmentCount = count ($objAttachments->getAllAttachments ($projectId));

        $this->view->arrPriorities = $priorites;

        $_SESSION['selectedRequest'] = $projectId;

        $this->view->JSON = $objProjects->object;

        $this->view->projectId = $projectId;
        $this->view->openTab = $openTab;

        $this->view->blComplete = $this->checkToMove ();

        if ( isset ($objProjects->object['job']['completed_by']) )
        {
            $statusId = 3;
            $this->view->blComplete = false;
        }

        if ( isset ($objProjects->object['job']['date_rejected']) )
        {
            $statusId = 4;
            $this->view->blComplete = false;
        }
    }

    public function getAuditAction ($projectId, $caseId = 1)
    {
        $this->view->disable ();
        $objHistry = new Audit();
        $arrHistory = $objHistry->getHistory ($projectId, $caseId);
        
        echo $arrHistory['FIELDS'];
    }

}
