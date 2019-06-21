<div id="site_content">
    <div id="content">
	
        <!-- insert the page content here -->
        <h1>Login</h1>
        <form action="<?=base_url()?>api/user/login" method="post">
            <div class="form_settings">
                <?php 
				if($error){
					echo '<div style="color:red" >Hmm, we don\'t recognize you. Please try again.</div><br>';
                }
				?>
                <p><span>Username</span><input class="" type="text" name="username" value="" /></p>
                <p><span>Password</span><input class="" type="password" name="password" value="" /></p>
                <p>	
					<p style="padding-top: 15px"><span>&nbsp;</span><input class="submit" type="submit" name="add" value="Login" />
				</p>
            </div>
        </form>
    </div>
</div>