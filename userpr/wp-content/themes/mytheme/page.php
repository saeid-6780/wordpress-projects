<?php

get_header();
get_template_part('views/partials/header');
while ( have_posts() ) {
    the_post();
    the_content();
}
get_footer();

?>