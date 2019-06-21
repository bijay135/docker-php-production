<div id="site_content">
    <?php include 'Sidebar.php' ?>
    <div id="content">
		<?php
		if($this->session->userdata('registered') == 1){
			echo '<div style="color:green"><h2>Registered Successfully. You may login now.</h2></div><br>';
			$this->session->set_userdata('registered', 0);
        }
		?>
        <?php 
		$validate_token = $this->session->userdata('user_token');
		$is_valid_token = $this->authorization_token->validateTokenPost($validate_token);
		if($is_valid_token['status'] == TRUE){ ?>
			<h2><a style="color:green" href="<?=base_url()?>Blog/newPost/"><span class="glyphicon glyphicon-pencil"></span>Create a new post</a></h2>
        <?php } ?>
		
    <!-- insert the page content here -->
    <?php foreach($posts as $post){ ?>
		<h2><a style="color:red;" href="<?=base_url()?>Blog/post/<?=$post['post_id']?>"><?=$post['post_title'];?></a></h2>
		<?php if($is_valid_token['status'] == TRUE){ ?>
			<p>
				<a href="<?=base_url()?>Blog/editpost/<?=$post['post_id']?>"><span class="glyphicon glyphicon-edit" title="Edit post"></span></a> | 
				<a href="<?=base_url()?>Blog/deletepost/<?=$post['post_id']?>"><span style="color:#f77;" class="glyphicon glyphicon-remove-circle" title="Delete post"></span></a>
			</p>
        <?php }?>
        <p><?= substr(strip_tags($post['post']),0,200).'...';?></p>
        <p><a href="<?=base_url()?>Blog/post/<?=$post['post_id']?>">Read More</a></p>
    <?php } ?>
    <?=$pages?>
    </div>
</div>