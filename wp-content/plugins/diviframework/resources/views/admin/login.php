<div class="pure-g" id="wrapper">
	<div class="pure-u-1-3">&nbsp;</div>
	<div class="pure-u-1-3 pure-u-md-1-3">
		<h2 class="pure-heading"><?php echo $this->container['provider'] ?>  Login</h2>
		<form class="pure-form pure-form-stacked" method="POST" action="<?php echo admin_url('/admin.php?page=diviframework-hub') ?>">
			<fieldset>

				<label for="email">Email</label>
				<input id="email" type="email" placeholder="<?php echo sprintf('Your %s Email', $this->container['provider']) ?>" class="pure-input-1" name='username'>

				<label for="password">Password</label>
				<input id="password" type="password" placeholder="<?php echo sprintf('Your %s Password', $this->container['provider']) ?>" class="pure-input-1" name='password'>

				<input type="submit" class="pure-button pure-input-1" value="Authenticate"></input>
			</fieldset>
		<?php if ($isPost && ($isPost['status'] == 'error')): ?>
			<p class='error-message'><?php echo $isPost['message']; ?></p>
		<?php endif;?>
		</form>

	</div>
	<div class="pure-u-1-3">&nbsp;</div>
</div>