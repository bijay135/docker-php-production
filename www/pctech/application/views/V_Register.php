<div id="site_content">
    <div id="content">
	
        <!-- insert the page content here -->
        <h1>Register as a new user</h1>
        <?php if($error){
			echo '<div style="color:red;">'.$error.'</div>'; 
		}?>
        
        <form action="<?=base_url()?>api/user/register" method="post">
            <div class="form_settings">
				<p><span>Full Name</span><input class="" type="text" name="fullname" value="" /></p>
                <p><span>Username</span><input class="" type="text" name="username" value="" /></p>
                <p><span>E-Mail</span><input class="" type="email" name="email" value="" /></p>
                <p><span>Password</span><input class="" type="password" name="password" value="" /></p>
                <p><span>Retype Password</span><input class="" type="password" name="passconf" value="" /></p> 
				<p>
					<p style="padding-top: 15px"><span>&nbsp;</span><input class="submit" type="submit" name="add" value="Register" />
				</p>
            </div>
        </form>
    </div>
</div>