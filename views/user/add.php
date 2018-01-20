<div class="panel panel-default">

   <div class="panel-heading">
      <h3 class="panel-title"><?= $data['form_header'] ?></h3>
   </div>

   <div class="panel-body">

      <?php echo Message::show(); ?>

      <form role="form" action="<?= DIR ?>user/add" method="POST">
         <input class="form-control" type="text" name="login" value="<?= $data['login'] ?? '' ?>" placeholder="Login" required1>
         <input class="form-control" type="password" name="passwd" placeholder="Password" required1>
         <div class="row">
            <button type="submit" class="btn btn-primary btn-block">Register</button>
         </div>
      </form>

   </div> <!-- / .panel-body -->
</div> <!-- / .panel -->