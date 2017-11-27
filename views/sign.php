<div class="panel panel-primary">
    <!-- Default panel contents -->
    <div class="panel-heading" style="padding: 0;">
        <ul class="nav nav-tabs nav-justified" role="tablist">
            <li role="presentation" class="active"><a class="bg-success text-muted" href="#signin" aria-controls="signin" role="tab" data-toggle="tab">SignIn</a></li>
            <li role="presentation"><a class="bg-primary text-muted" href="#signup" aria-controls="signup" role="tab" data-toggle="tab">SignUp</a></li>
        </ul>
    </div>
    <div class="panel-body container">
        <div class="col-xs-6 col-xs-offset-3">

            <!-- Nav tabs -->


            <!-- Tab panes -->
            <div class="tab-content" style="padding-top: 2rem;">
                <!--SIGNIN-->
                <div role="tabpanel" class="tab-pane active" id="signin">
                    <form class="form-horizontal">
                        <div class="form-group">
                            <label for="inputEmail3" class="col-sm-2 control-label">Email</label>
                            <div class="col-sm-10">
                                <input type="email" class="form-control" id="inputEmail3" placeholder="Email">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputPassword3" class="col-sm-2 control-label">Password</label>
                            <div class="col-sm-10">
                                <input type="password" class="form-control" id="inputPassword3" placeholder="Password">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-10">
                                <button type="submit" class="btn btn-success btn-lg btn-block">SignIn</button>
                            </div>
                        </div>
                    </form>
                </div>

                <!--SIGNUP-->
                <div role="tabpanel" class="tab-pane" id="signup">
                    <form class="form-horizontal">
                        <div class="form-group">
                            <label for="inputEmail3" class="col-sm-2 control-label">Email</label>
                            <div class="col-sm-10">
                                <input type="email" class="form-control" id="inputEmail3" placeholder="Email">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="password" class="col-sm-2 control-label">Password</label>
                            <div class="col-sm-10">
                                <input  name="password" type="password" class="form-control" id="password" placeholder="Password">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="passwordConfirmation" class="col-sm-2 control-label">Password</label>
                            <div class="col-sm-10">
                                <input name="passwordConfirmation" type="password" class="form-control" id="passwordConfirmation" placeholder="Password">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-10">
                                <button type="submit" class="btn btn-primary btn-lg btn-block">SignUp</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>