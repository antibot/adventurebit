<div id="iwak-admin" class="iwak-page <?php echo $bricks['class']; ?>">
    
    <div class="header">
        <?php echo $bricks['heading']; echo $bricks['message']; ?>
        <a class="fr" href="<?php echo 'admin.php?page='. THEME_SLUG. '/help-center.php' ?>"><img src="<?php echo ADMIN_URL. '/images/helpcenter.png'; ?>"/></a>
        <div id="iwak-versions">
            <span><?php _e('Theme Version', THEME_NAME); ?></span>
            <span class="version_number"><?php echo THEME_VERSION; ?></span>
            <span><?php _e('Framework', THEME_NAME); ?></span>
            <span class="version_number"><?php echo FRAMEWORK_VERSION; ?></span>
        </div>
    <?php //echo $bricks['message']; ?>
    </div>

    <?php echo $bricks['navigation']; ?>
    <div class="clear"></div>
    
    <form method="post">
        <div class="groups">
            <?php foreach($bricks['tables'] as $name=>$table): $i++;?>
            <div class="group group-<?php echo $name; ?>">
                <?php echo $table; ?>
                <p class="submit"><?php echo $bricks['submit']; ?></p>
            </div>
            <?php endforeach; ?>
        </div>
    </form>
    
</div>
