<div class="wrapper wrapper-content animated fadeInRight ecommerce">
    <div class="ibox-content m-b-sm border-bottom">
        <form id="casesSearchForm">
            <div class="row">
                <div class="col-sm-4">
                    <div class="form-group">
                        <label class="control-label" for="order_id">Process</label>
                        <select id="process" name="process" class="form-control">
                            <option value="">Select value</option>
                            <?php foreach ($arrWorkflows as $arrWorkflow): ?>
                                <option value="<?= $arrWorkflow['workflow_id'] ?>"><?= $arrWorkflow['workflow_name'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group">
                        <label class="control-label" for="status">Category</label>
                        <select id="category" name="category" class="form-control">
                            <option value="">Select value</option>
                            <?php foreach ($arrCategories as $arrCategory): ?>
                                <option value="<?= $arrCategory->getRequestId () ?>"><?= $arrCategory->getName () ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group">
                        <label class="control-label" for="customer">User</label>
                        <select id="user" name="user" class="form-control">
                            <option value="">Select value</option>
                            <?php foreach ($arrUsers as $arrUser): ?>
                                <option value="<?= $arrUser->getUsername () ?>"><?= $arrUser->getUsername () ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-4">
                    <div class="form-group">
                        <label class="control-label" for="date_added">Date From</label>
                        <div class="input-group date">
                            <span class="input-group-addon"><i class="fa fa-calendar"></i></span><input id="date_added" type="text" class="form-control" value="03/04/2014">
                        </div>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group">
                        <label class="control-label" for="date_modified">Date To</label>
                        <div class="input-group date">
                            <span class="input-group-addon"><i class="fa fa-calendar"></i></span><input id="date_modified" type="text" class="form-control" value="03/06/2014">
                        </div>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group">
                        <label class="control-label" for="amount">Status</label>
                        <select id="status" name="status" class="form-control">
                            <option value="">Select value</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="pull-right">
                    <button id="SearchCase" type="button" class="btn btn-w-m btn-primary">Search</button>
                </div>
            </div> 
        </form>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="ibox">
                <div class="ibox-content searchFrame">



                </div>
            </div>
        </div>
    </div>


</div>

<script>

    var page = 0;
    var orderBy = 'element_id';
    var orderDir = 'ASC';

    $ (document).ready (function ()
    {

        $ ("#SearchCase").off ();
        $ ("#SearchCase").on ("click", function ()
        {
            searchCases ();
        });

        searchCases ();
    });

    function searchCases ()
    {
        var formData = $ ("#casesSearchForm").serialize ();

        $.ajax ({
            type: "POST",
            url: "/FormBuilder/inbox/searchCases/" + page + "/" + orderBy + "/" + orderDir,
            data: formData,
            success: function (response)
            {
                $ (".searchFrame").html (response);

                $ (".caseRow").on ("click", function ()
                {
                    var projectId = $ (this).attr ("projectid");

                    getProjectDetails (projectId);
                });
            }
            ,
            error: function (request, status, error)
            {
                console.log ("critical errror occured");
            }
        }
        )
    }

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