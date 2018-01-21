<div class="panel panel-default">

   <div class="panel-heading">
      <h3 class="panel-title"><?= $data['form_header'] ?></h3>
   </div>

   <div class="panel-body">

      <?php echo Message::show(); ?>

      <form role="form" action="<?= DIR ?>user/auth" method="POST">
         <input class="form-control" type="text" name="login" value="<?= $data['login'] ?? '' ?>" placeholder="Login" required autofocus>
         <input class="form-control" type="password" name="passwd" placeholder="Password" required>
         <div class="row">
            <button type="submit" class="btn btn-primary btn-block">Sign in</button>
         </div>
      </form>

   </div> <!-- / .panel-body -->
</div> <!-- / .panel -->