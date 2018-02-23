<?php include("header.php"); ?>
<h2>Login</h2>
<form class="myform" id="login" action="login_chk.php" method="post">


<div class="form-group">
    <label for="exampleInputLogin1">Login</label>
    <input type="text" name="login" class="form-control" id="exampleInputLogin1"  placeholder="Login">
  </div>
  <div class="form-group">
    <label for="exampleInputPassword1">Password</label>
    <input type="password" name="password" class="form-control" id="exampleInputPassword1" placeholder="Password">
  </div>
  <button type="submit" class="btn btn-primary">Submit</button>
</form>

<?php include("footer.php"); ?>
