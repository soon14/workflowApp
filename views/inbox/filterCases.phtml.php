<div class="ibox">
    <div class="ibox-content">
        <h2>Cases</h2>

        <div class="input-group">
            <input type="text" placeholder="Search client " class="input form-control">
            <span class="input-group-btn">
                <button type="button" class="btn btn btn-primary"> <i class="fa fa-search"></i> Search</button>
            </span>
        </div>
        <div class="clients-list">
            <!--            <ul class="nav nav-tabs">
                            <span class="pull-right small text-muted">1406 Elements</span>
                            <li class="active"><a data-toggle="tab" href="#tab-1"><i class="fa fa-user"></i> Contacts</a></li>
                            <li class=""><a data-toggle="tab" href="#tab-2"><i class="fa fa-briefcase"></i> Companies</a></li>
                        </ul>-->
            <div class="tab-content">
                <div id="tab-1" class="tab-pane active">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">

                            <thead>
                            <th>Current User</th>
                            <th>Case Name</th>
                            <th>Current Step</th>
                              <th>Project Id</th>
                            </thead>

                            <tbody>
                                <?php foreach ($arrLists['data'] as $objCase): ?>
                                <tr class="caseRow" projectid="<?= $objCase->getParentId () ?>">
                                        <td><a data-toggle="tab" href="#contact-1" class="client-link"><?= $objCase->getCurrent_user () ?></a></td>
                                        <td> <?= $objCase->getName () ?></td>
                                        <td> <?= $objCase->getCurrent_step () ?></td>
                                         <td> <?= $objCase->getParentId () ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div id="tab-2" class="tab-pane">
            </div>
        </div>

    </div>
</div>
</div>