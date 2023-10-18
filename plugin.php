<?php
/**
 * Plugin Name:       Boilerplate
 * Description:       Example block scaffolded with Create Block tool.
 * Requires at least: 6.1
 * Requires PHP:      7.0
 * Version:           0.1.0
 * Author:            Jessica Prinner
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       boilerplate
 *
 * @package           create-block
 */

/**
 * Registers the block using the metadata loaded from the `block.json` file.
 * Behind the scenes, it registers also all assets so they can be enqueued
 * through the block editor in the corresponding context.
 *
 * @see https://developer.wordpress.org/reference/functions/register_block_type/
 */
function create_block_boilerplate_block_init() {
	register_block_type( __DIR__ . '/build', array(
		'render_callback' => 'render_latest_posts_block'
	) );
}
add_action( 'init', 'create_block_boilerplate_block_init' );

function render_latest_posts_block($attributes){

	$args = [
		'posts_per_page'=> 3,
		'post_status'=>'publish',
		'post__in' => $attributes['latestPosts']
	];
	$recent_posts = get_posts($args);

	$posts = '<ul '.get_block_wrapper_attributes().'>';
	foreach ($recent_posts as $post){

		$title = $post->post_title;
		$title = ($title) ? $title : __('(no title)', 'latest-posts');
		$permalink = get_permalink($post);
		$excerpt = get_the_excerpt($post);
		$posts.= '<li>';
		$posts.= '<h5><a href="'.esc_url($permalink).'">'.$title.'</a></h5>';
		if (!empty($excerpt)){
			$posts.= '<p>'.$excerpt.'</p>';
		}
		$posts.='</li>';
	}
	$posts .= '</ul>';

	return $posts;

}