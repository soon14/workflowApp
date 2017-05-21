<div class="col-lg-12 pull-left">
    <?= $pagination ?> 
</div>

<div class="col-lg-12 pull-left">

    <div class="input-group col-lg-4" style="margin-bottom: 12px;">
        <input type="text" id="processSearchText" placeholder="Search client " class="input form-control">
    </div>

    <div class="client-detail col-lg-4 processesList" style="background-color: #FFF; padding-top: 10px;">
        <?php
        $requestId = null;
        foreach ($arrWorkflows as $arrWorkflow) {
            ?>

            <?php
            if ( $requestId === null || $requestId !== $arrWorkflow['request_id'] )
            {
                echo '</ul><ul class="list-group clear-list">'
                . '<strong>' . $arrWorkflow['PRO_CATEGORY_LABEL'] . '</strong>';

                $end = true;
            }
            ?>

            <li workflowid="<?= $arrWorkflow['workflow_id'] ?>" class="list-group-item fist-item" style="border-bottom: 1px dotted #CCC;">
                <span class="pull-right"> 
                    <button workflowid="<?= $arrWorkflow['workflow_id'] ?>" type="button" class="btn btn-primary btn-xs addNewCase">+</button> 
                </span>
                <?= $arrWorkflow['workflow_name'] ?>
            </li>

            <?php
            $requestId = $arrWorkflow['request_id'];
            $end = false;
        }
        ?>
    </div>

    <div class="col-lg-8 pull-left casesList">

    </div>
</div>




<script>

    $ ("#processSearchText").on ("keyup", function ()
    {
        var searchText = $(this).val();
        var workflowId = $(".selectedRow").attr("workflowid");
        
        $.ajax ({
            type: "POST",
            data: {"searchText": searchText},
            url: "/FormBuilder/inbox/filterProcesses/",
            success: function (response)
            {
                $ (".processesList").html (response);
                //rebind ();
            },
            error: function (request, status, error)
            {
                console.log ("critical errror occured");
            }
        });
    });


    $ (".addNewCase").on ("click", function ()
    {
        var workflowid = $ (this).attr ("workflowid");

        $.ajax ({
            type: "GET",
            url: "/FormBuilder/inbox/addNewCase/" + workflowid,
            success: function (response)
            {
                $ ("#myModal6").html (response).modal ("show");
            },
            error: function (request, status, error)
            {
                console.log ("critical errror occured");
            }
        });
    });

    $ (".list-group-item").on ("click", function ()
    {
        $ (".list-group-item").removeClass ("selectedRow");
        $ (this).addClass ("selectedRow");

        var workflowid = $ (this).attr ("workflowid");

        $.ajax ({
            type: "GET",
            url: "/FormBuilder/inbox/filterCases/" + workflowid,
            success: function (response)
            {
                $ (".casesList").html (response);

                $ (".caseRow").on ("click", function ()
                {
                    var projectId = $ (this).attr ("projectid");

                    getProjectDetails (projectId);
                });
            },
            error: function (request, status, error)
            {
                console.log ("critical errror occured");
            }
        });
    });

    function getProjectDetails (projectId)
    {
        alert (projectId);
        $ ("#createProject").html ("");
        $.ajax ({
            type: "GET",
            url: "/FormBuilder/index/getProject/" + projectId,
            success: function (response)
            {
                console.log (response);
                $ ("#myModal7").html (response).modal ("show");
            }, error: function (request, status, error)
            {
                alert (status + ' ' + error);
            }
        });
    }

</script>