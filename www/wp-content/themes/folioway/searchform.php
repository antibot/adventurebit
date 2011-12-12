<form id="searchform" class="blog-search" method="get" action="<?php echo HOME_URL ?>">
    <input id="s" name="s" type="text" onfocus="if (this.value == 'Search here...') {this.value = '';}" onblur="if (this.value == '') {this.value = 'Search here...';}" value="Search here..." tabindex="1" />
    <button id="searchsubmit" name="submit" type="submit" class="button" tabindex="2"><span></span></button>
</form>