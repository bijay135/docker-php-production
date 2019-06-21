<div id="sidebar_container">
    <img class="paperclip" src="<?=base_url()?>public/images/paperclip.png" alt="paperclip" />
    <img class="paperclip" src="<?=base_url()?>public/images/paperclip.png" alt="paperclip" />
    <div class="sidebar">
        <h3>Latest Blog</h3>
        <h4><?=$posts[0]["post_title"];?></h4>
        <h5><?=date('d-m-Y h:i A',strtotime($posts[0]['date_added']))?></h5>
        <p><?=substr(strip_tags($posts[0]['post']), 0, 200).'...';?></p>
        <p><a href="<?=base_url()?>Blog/post/<?=$posts[0]['post_id']?>">Read More</a></p>
    </div>
</div>
