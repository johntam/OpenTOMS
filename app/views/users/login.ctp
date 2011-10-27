<table style="width: 50%;margin-left:25%;margin-right:25%;">

<tr><td><h1>Login</h1></td></tr>

<tr><td><?php echo $this->Form->create('User', array('url' => array('controller' => 'users', 'action' =>'login'))); ?></td></tr>
<tr><td><?php echo $this->Form->input('User.username'); ?></td></tr>
<tr><td><?php echo $this->Form->input('User.password'); ?></td></tr>
<tr><td><?php echo $this->Form->end('Login'); ?></td></tr>

</table>

