import { __ } from '@wordpress/i18n';
import { useBlockProps } from '@wordpress/block-editor';
import { useSelect } from '@wordpress/data';
import { RawHTML, useEffect, useLayoutEffect } from '@wordpress/element';

import './editor.scss';

export default function Edit( { attributes, setAttributes } ) {
	const { latestPosts } = attributes;
	const postId = useSelect( ( select ) => {
		return select( 'core/editor' ).getCurrentPostId();
	}, [] );

	const posts = useSelect(
		( select ) => {
			return select( 'core' ).getEntityRecords( 'postType', 'post', {
				per_page: 3,
				_embed: true,
				exclude: postId,
			} );
		},
		[ postId ]
	);

	useEffect( () => {
		if ( posts ) {
			const newPosts = posts.map( ( element ) => {
				return element.id;
			} );
			setAttributes( { latestPosts: newPosts } );
		}
	}, [ posts ] );

	return (
		<ul { ...useBlockProps() }>
			{ posts &&
				posts.map( ( post ) => {
					return (
						<li key={ post.id }>
							<h5>
								{ post.title.rendered ? (
									<RawHTML>{ post.title.rendered }</RawHTML>
								) : (
									__( '(No Title)', 'latest-posts' )
								) }
							</h5>
							{ post.excerpt.rendered && (
								<p>
									<RawHTML>{ post.excerpt.rendered }</RawHTML>
								</p>
							) }
						</li>
					);
				} ) }
		</ul>
	);
}
