<?php

use WPML\Element\API\PostTranslations;

/**
 * Class WPML_Media_Image_Translate
 * Allows getting translated images in a give language from an attachment
 */
class WPML_Media_Image_Translate {

	/**
	 * @var SitePress
	 */
	private $sitepress;

	/**
	 * @var WPML_Media_Attachment_By_URL_Factory
	 */
	private $attachment_by_url_factory;

	/**
	 * @var \WPML\Media\Classes\WPML_Media_Attachment_By_URL_Query
	 */
	private $media_attachment_by_url_query;

	/**
	 * WPML_Media_Image_Translate constructor.
	 *
	 * @param SitePress                                                        $sitepress
	 * @param WPML_Media_Attachment_By_URL_Factory                             $attachment_by_url_factory
	 * @param \WPML\Media\Factories\WPML_Media_Attachment_By_URL_Query_Factory $media_attachment_by_url_query_factory
	 */
	public function __construct(
		SitePress $sitepress,
		WPML_Media_Attachment_By_URL_Factory $attachment_by_url_factory,
		\WPML\Media\Factories\WPML_Media_Attachment_By_URL_Query_Factory $media_attachment_by_url_query_factory
	) {
		$this->sitepress                     = $sitepress;
		$this->attachment_by_url_factory     = $attachment_by_url_factory;
		$this->media_attachment_by_url_query = $media_attachment_by_url_query_factory->create();
	}

	/**
	 * @param string $source_language
	 * @param array  $items_to_translate
	 */
	public function prefetchDataForFutureGetTranslatedImageCalls( $source_language, $items_to_translate ) {
		$this->media_attachment_by_url_query->prefetchAllIdsFromGuids(
			$source_language,
			array_merge(
				array_map(
					function( $item ) {
						return WPML_Media_Attachment_By_URL::getUrl( $item['url'] );
					},
					$items_to_translate
				),
				array_map(
					function( $item ) {
						return WPML_Media_Attachment_By_URL::getUrlNotScaled( $item['url'] );
					},
					$items_to_translate
				)
			)
		);
		$this->media_attachment_by_url_query->prefetchAllIdsFromMetas(
			$source_language,
			array_merge(
				array_map(
					function( $item ) {
						return WPML_Media_Attachment_By_URL::getUrlRelativePath( $item['url'] );
					},
					$items_to_translate
				),
				array_map(
					function( $item ) {
						return WPML_Media_Attachment_By_URL::getUrlRelativePathOriginal(
							WPML_Media_Attachment_By_URL::getUrlRelativePath( $item['url'] )
						);
					},
					$items_to_translate
				),
				array_map(
					function( $item ) {
						return WPML_Media_Attachment_By_URL::getUrlRelativePathScaled( $item['url'] );
					},
					$items_to_translate
				)
			)
		);
	}

	/**
	 * @param int    $attachment_id
	 * @param string $language
	 * @param string $size
	 *
	 * @return string
	 */
	public function get_translated_image( $attachment_id, $language, $size = null ) {
		$image_url              = '';
		$attachment             = new WPML_Post_Element( $attachment_id, $this->sitepress );
		$attachment_translation = $attachment->get_translation( $language );

		if ( $attachment_translation ) {
			$uploads_dir   = wp_get_upload_dir();
			$attachment_id = $attachment_translation->get_id();
			if ( null === $size ) {
				$image_url = $uploads_dir['baseurl'] . '/' . get_post_meta( $attachment_id, '_wp_attached_file', true );
			} else {
				$image_url = $this->get_sized_image_url( $attachment_id, $size, $uploads_dir );
			}
		}

		return $image_url;
	}

	/**
	 * @param string $img_src
	 * @param string $source_language
	 * @param string $target_language
	 *
	 * @return string|bool
	 */
	public function get_translated_image_by_url( $img_src, $source_language, $target_language ) {

		$attachment_id = $this->get_attachment_id_by_url( $img_src, $source_language );

		if ( $attachment_id ) {
			$size = $this->get_image_size_from_url( $img_src, $attachment_id );
			try {
				$img_src = $this->get_translated_image( $attachment_id, $target_language, $size );
			} catch ( Exception $e ) {
				$img_src = false;
			}
		} else {
			$img_src = false;
		}

		return $img_src;
	}

	/**
	 * @param string $img_src
	 * @param string $source_language
	 *
	 * @return int
	 */
	public function get_attachment_id_by_url( $img_src, $source_language ) {
		$attachment_by_url = $this->attachment_by_url_factory->create( $img_src, $source_language, $this->media_attachment_by_url_query );

		return (int) $attachment_by_url->get_id();
	}

	/**
	 * @param string $url
	 * @param int    $attachment_id
	 *
	 * @return string
	 */
	private function get_image_size_from_url( $url, $attachment_id ) {
		$media_sizes = new WPML_Media_Sizes();

		return $media_sizes->get_image_size_from_url( $url, $attachment_id );
	}

	/**
	 * @param int    $attachment_id
	 * @param string $size
	 * @param string $uploads_dir
	 *
	 * @return string
	 */
	private function get_sized_image_url( $attachment_id, $size, $uploads_dir ) {
		$image_url       = '';
		$meta_data       = wp_get_attachment_metadata( $attachment_id );
		$image_url_parts = array( $uploads_dir['baseurl'] );

		if ( is_array( $meta_data ) && array_key_exists( 'file', $meta_data ) ) {
			$file_subdirectory       = $meta_data['file'];
			$file_subdirectory_parts = explode( '/', $file_subdirectory );

			$filename          = array_pop( $file_subdirectory_parts );
			$image_url_parts[] = implode( '/', $file_subdirectory_parts );

			if ( array_key_exists( $size, $meta_data['sizes'] ) ) {
				$image_url_parts[] = $meta_data['sizes'][ $size ]['file'];
			} else {
				$image_url_parts[] = $filename;
			}

			$image_url = implode( '/', $image_url_parts );
		}

		return $image_url;
	}
}
