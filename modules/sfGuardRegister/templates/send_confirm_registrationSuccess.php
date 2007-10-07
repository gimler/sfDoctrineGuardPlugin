Hello <?php echo $sfGuardUser->getUsername(); ?>,<br/><br/>

<?php echo link_to('Click here to confirm your registration', url_for('@sf_guard_register_confirm?key='.$sfGuardUser->getPassword().'&id='.$sfGuardUser->getId(), true)); ?>
<br/><br/>
Your login information can be found below:<br/><br/>

Username: <?php echo $sfGuardUser->getUsername(); ?><br/>
Password: <?php echo $sf_request->getParameter('user[password]'); ?>