<style>
    .align-left {
        text-align: left;
    }
</style>


<?php
$labels = array(
    7 => "label label-primary",
    5 => "label label-success",
    4 => "label label-danger",
    3 => "label label-warning"
);

$statusId = 7;
$status = "NEW PROJECT";
$claimed = "";

if ( isset ($JSON['elements']) )
{
    foreach ($JSON['elements'] as $arrElements) {
        foreach ($arrElements as $arrElement) {
            if ( isset ($arrElement['steps']) && !empty ($arrElement['steps']) )
            {
                foreach ($arrElement['steps'] as $arrStep) {
                    if ( isset ($arrStep['claimed']) )
                    {
                        $claimed = $arrStep['claimed'];
                        $statusId = 3;
                        $status = "IN PROGRESS";
                    }
                }
            }
        }
    }
}

if ( isset ($JSON['job']['completed_by']) )
{
    $statusId = 3;
    $status = "COMPLETED";
}

if ( isset ($JSON['job']['date_rejected']) )
{
    $statusId = 4;
    $status = "REJECTED";
}
?>


<div class="modal-dialog modal-lg">
    <div class="modal-content animated bounceInRight">

        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">
                <span aria-hidden="true">×</span>
                <span class="sr-only">Close</span>
            </button>

            <input type="hidden" id="userId" value="1">
            <input type="hidden" id="projectId" value="<?= $projectId ?>">
            <h4 class="modal-title">
                #<?= str_pad ($_SESSION['selectedRequest'], 6, '0', STR_PAD_LEFT) ?>
<?= $JSON['job']['name'] ?>

            </h4>    

            <div style="clear:both; float: left; width: 100%; margin-bottom: 6%;">
                <span class="<?= $labels[$statusId] ?>"><?= $status ?></span>

                <span class="label <?= $arrPriorities[$JSON['job']['priority']]['style'] ?>"><?= $arrPriorities[$JSON['job']['priority']]['name'] ?></span>

                <div style="width: 100%; float:left;">
                    <small>Date Added: <?= $JSON['job']['date_created'] ?> Due Date: <?= $JSON['job']['dueDate'] ?></small>
                </div>

            </div>

            <div class="row col-md-8 text-center col-md-offset-2 pull-left" style="margin-top: 2%;">
                <div projectId="<?= $projectId ?>" class="col-md-3 openPoject pointer">
                    <div class="infont col-md-3 col-sm-4">
                        <i class="fa fa-th-large icon-medium"></i>
                    </div>
                </div>

                <div projectId="<?= $projectId ?>" class="col-md-3 openTasks pointer">
                    <div class="infont col-md-3 col-sm-4">
                        <i class="fa fa-tasks icon-medium"></i>
                    </div>
                </div>

                <div projectId="<?= $projectId ?>" class="col-md-3 openComments pointer">
                    <div class="infont col-md-3 col-sm-4">
                        <i class="fa fa-comment-o icon-medium"></i> 
                    </div>

                    <span class="label label-warning" style="position: relative; top: -9px; padding: 2px 4px;"><?= 0 ?></span>
                </div>

                <div projectId="<?= $projectId ?>" class="col-md-3 openAttachments pointer">
                    <div class="infont col-md-3 col-sm-4">
                        <i class="fa fa-paperclip icon-medium"></i> 
                    </div>

                    <span class="label label-warning" style="position: relative; top: -9px; padding: 2px 4px;"><?= $attachmentCount ?></span>
                </div>
            </div>

            <div id="reviewed" class="text-center" style="float: left; margin-top: 5%; background: #ebccd1 none repeat scroll 0 0; color: #a94442; padding: 4px 0; display: none;">
                <h4>This step does not meet the requirements and it will be reviewed. The participants have been notified.</h4>
            </div>
        </div>

        <div class="modal-body modal-panel modal-details">
            <div class="row">
                <div class="col-lg-12">

                    <div class="form-horizontal">
                        <div class="form-group">
                            <label class="col-lg-3">Created By</label>

                            <div class="col-lg-6 align-left">
<?= $JSON['job']['added_by'] ?>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-lg-3">Created</label>

                            <div class="col-lg-6 align-left">
<?= $JSON['job']['date_created'] ?>
                            </div>
                        </div>


                        <div class="form-group">
                            <label class="col-lg-3">Priority</label>

                            <div class="col-lg-6 align-left">
<?= $arrData[0]['priority_name'] ?>
                            </div>   
                        </div>

                        <div class="form-group">
                            <label class="col-lg-3 control-label">Description</label>

                            <div class="col-lg-8 align-left" style="line-height:32px;">
<?= $JSON['job']['description'] ?>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-lg-3 control-label">Department</label>

                            <div class="col-lg-8 align-left" style="line-height:32px;">
<?= $JSON['job']['deptId'] ?>
                            </div>
                        </div>

                        <!--<div class="form-group">
                            <label class="col-lg-3">Workflow</label>

                            <div class="col-lg-6 align-left">
<?= $arrData[0]['workflow_name'] ?>
                            </div>
                        </div>-->

<?php if ($JSON['job']['project_status'] == 0 ): ?>
                            <!--<div class="form-group">
                                <div class="col-lg-12">
                                    <textarea id="reason" class="form-control" placeholder="Reason For Deny"></textarea>
                                </div>

                                <div style="display:none; margin-top: 7px; float: left; width: 95%; margin-left: 2%;" class="alert alert-danger" id="reasonWarning">
                                    You must specify a reason for rejecting the project.
                                </div>
                            </div>-->
                        <?php endif; ?>

<?php if ( $JSON['job']['project_status'] == 5 ): ?>
                            <div class="form-group">
                                <label class="col-lg-3">Assign To</label>

                                <div class="col-lg-6">
                                    <select multiple="multiple" id="assignTo" name="assignTo" class="form-control">
                                        <!--<option value="">Select User</option>-->

                                        <?php
                                        foreach ($arrUsers as $arrUser):
                                            echo '<option value="' . $arrUser['username'] . '">' . $arrUser['username'] . '</option>';
                                        endforeach;
                                        ?>
                                    </select>
                                </div>

                                <div style="display:none; margin-top: 7px; float: left; width: 95%; margin-left: 2%;" class="alert alert-danger" id="assignWarning">
                                    You must assign a user to the project.
                                </div>
                            </div>
                        <?php endif; ?>

<?php if ( $JSON['job']['project_status'] == 1 || $arrData[0]['project_status'] == 3 ): ?>
                            <div class="form-group">
                                <label class="col-lg-3">Completed</label>

                                    <?php if ( in_array ($JSON['job']['project_status'], array(3, 1)) ): ?>
                                    <div class="col-lg-8 align-left">
                                        <?php
                                        if ( $JSON['job']['project_status'] == 3 )
                                        {
                                            $percent = 100;
                                        }
                                        else if ( $JSON['job']['project_status'] == 1 )
                                        {
                                            $percent = 60;
                                        }
                                        ?>
                                        <div class="progress progress-striped active m-b-sm">
                                            <div style="width: <?= $percent ?>%;" class="progress-bar"></div>
                                        </div>

                                        <small>Project completed in <strong><?= $percent ?>%</strong>. Remaining close the project, sign a contract and invoice.</small>

                                    </div>
                            <?php endif; ?>
                            </div>
<?php endif; ?>
                    </div>
                </div>
            </div>
        </div>  

        <div class="modal-body modal-panel modal-comments" style="display:none">
            These are the comments
        </div>

        <div class="modal-body modal-panel modal-attachments" style="display:none">
            These are the attachments
        </div>

        <div class="modal-body modal-panel modal-tasks" style="display:none;">
            These are the tasks
        </div>

        <div id="viewTasksContainer" class="ibox float-e-margins" style="display:none;">
            View Tasks
        </div>


        <div id="addNewTask" class="ibox float-e-margins" style="display:none;">

            <input type="hidden" id="stepId" name="stepId" value="<?= $currentStep ?>">

            <div class="ibox-title">
                <h5>Add New Task</h5>
            </div>

            <div class="ibox-content" style="float:left; width: 100%;">
                <form class="form-horizontal" id="newTask">
                    <div class="form-group" style="float:left; width: 100%;">
                        <label class="col-lg-2 control-label">Task</label>

                        <div class="col-lg-10">
                            <input type="text" name="form[taskName]" id="taskName" placeholder="Task Name" class="form-control"> 
                        </div>
                    </div>

                    <div class="form-group" style="float:left; width: 100%;">
                        <label class="col-lg-2 control-label">Task Summary</label>

                        <div class="col-lg-10">
                            <textarea id="taskSummary" name="form[taskSummary]" placeholder="Summary" class="form-control"></textarea>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-lg-2 control-label" for="date_added">Start Date</label>

                        <div class="input-group date">
                            <span class="input-group-addon">
                                <i class="fa fa-calendar"></i>
                            </span>
                            <input id="startDate" name="form[startDate]" type="text" class="form-control" value="03/04/2014">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-lg-2 control-label" for="date_added">End Date</label>

                        <div class="input-group date">
                            <span class="input-group-addon">
                                <i class="fa fa-calendar"></i>
                            </span>
                            <input id="endDate" name="form[endDate]" type="text" class="form-control" value="03/04/2014">
                        </div>
                    </div>

                    <a id="SaveNewTask" class="btn btn-primary" href="#">Save</a>
                </form>
            </div>
        </div>

        <div class="modal-footer footer-comments" style="display:none;">
            <button id="saveNewComment" type="button" class="btn btn-w-m btn-danger">Save Comment</button>
        </div>

        <div class="modal-footer footer-tasks" style="display:none;">

            <button style="display:none;" id="Previous" class="btn btn-primary">Previous</button>
            <button style="display:none;" id="Next" class="btn btn-primary">Next</button>
            <button style="display:none;" id="finish" class="btn btn-primary completeStep">Save & Complete</button>
            <button style="display:none;" id="Save" class="btn btn-primary saveStep">Save</button>
            <button style="display:none;" id="approveTask" class="btn btn-primary">Approve</button>
            <button style="display:none;" id="rejectTask" class="btn btn-primary">Reject</button>
        </div>

        <div class="modal-footer footer-attachments" style="display:none;">
            <input id="uploadfile" style="display: none; margin-top: 26px;" type="button" id="browsefile" value="Upload file "
                   class="btn btn-w-m btn-danger">

            <input onclick="getFile ()" style="<?= !$writePermission ? "display:none;" : ""; ?> width: 200px; height: 40px ; margin-top: 26px;" type="button" id="browsefile" value="Add File " class="btn btn-w-m btn-danger">
        </div>

        <div class="modal-footer footer-details">                  
            <button type="button" class="btn btn-white" data-dismiss="modal">Close</button>  

            <button style="display:none;" id="reject" moveTo="4" type="button" class="btn btn-w-m btn-danger changeStatus">Reject</button>
            <button style="display:none;" id="accept" moveTo="5" type="button" class="btn btn-w-m btn-primary changeStatus">Accept</button>
        </div>       
    </div>

    <div id="taskDetails">

    </div>


    <script src="/core/public/js/upload.js" type="text/javascript"></script>
    <script src="/FormBuilder/public/js/getProject.js" type="text/javascript"></script>

    <!-- Sweet alert -->




