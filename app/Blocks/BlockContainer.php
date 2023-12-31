<?php

namespace App\Blocks;

use Log1x\AcfComposer\Block;
use StoutLogic\AcfBuilder\FieldsBuilder;

class BlockContainer extends Block
{
	/**
	 * The block name.
	 *
	 * @var string
	 */
	public $name = 'Block Container';
	
	/**
	 * The block slug.
	 *
	 * @var string
	 */
	public $slug = 'block-container';

	/**
	 * The block description.
	 *
	 * @var string
	 */
	public $description = 'A Block Container block.';

	/**
	 * The block category.
	 *
	 * @var string
	 */
	public $category = 'fr-page-builder-layout-blocks';

	/**
	 * The block icon.
	 *
	 * @var string|array
	 */
	public $icon = ' fricon fricon--fr-block-container';

	/**
	 * The block keywords.
	 *
	 * @var array
	 */
	public $keywords = [];

	/**
	 * The block post type allow list.
	 *
	 * @var array
	 */
	public $post_types = [];

	/**
	 * The parent block type allow list.
	 *
	 * @var array
	 */
	public $parent = ['acf/fr-page-builder'];

	/**
	 * The default block mode.
	 *
	 * @var string
	 */
	public $mode = 'preview';

	/**
	 * The default block alignment.
	 *
	 * @var string
	 */
	public $align = '';

	/**
	 * The default block text alignment.
	 *
	 * @var string
	 */
	public $align_text = '';

	/**
	 * The default block content alignment.
	 *
	 * @var string
	 */
	public $align_content = '';

	/**
	 * The supported block features.
	 *
	 * @var array
	 */
	public $supports = [
		'align' => false,
		'align_text' => false,
		'align_content' => false,
		'full_height' => false,
		'anchor' => true,
		'mode' => false,
		'multiple' => true,
		'jsx' => true,
	];


	/**
	 * Data to be passed to the block before rendering.
	 *
	 * @return array
	 */
	public function with()
	{
		$registered_blocks = \WP_Block_Type_Registry::get_instance()->get_all_registered();

		//allowed blocks
		$prefix = 'acf/fr-page-builder-module-';
		$allowed_blocks = [];
		foreach ($registered_blocks as $b) {
			if(substr( $b->name, 0, strlen($prefix) ) === $prefix) {
				$allowed_blocks[] = $b;
			}
		}

		return array_merge($this->items(), [
			'allowed_blocks' => json_encode($allowed_blocks)
		]);
	}

	/**
	 * The block field group.
	 *
	 * @return array
	 */
	public function fields()
	{
		$contentSection = new FieldsBuilder('content_section');

		$contentSection
			->addRadio('background_color', [
				'choices' => [
					'white' => 'White',
					'fade-to-white' => 'Fade to white',
					'fade-to-color' => 'Fade to color',
				],
				'wpml_cf_preferences' => 0,
				'return_format' => 'value',
				'default' => 'white'
			])
			->addTrueFalse('show_top_wave', [
				'label' => 'Show top wave',
				'default' => 0
			])
				->conditional('background_color', '!=', 'white')
			->addRadio('content_max_width', [
				'label' => 'Content\'s Max Width',
				'layout' => 'horizontal',
				'wpml_cf_preferences' => 0,
				'choices' => [
					'default' => 'Default',
					'full_width' => 'Full Screen Width',
					'custom' => 'Custom'
				],
				'default_value' => 'default'
			])
			->addRange('custom_content_max_width', [
				'label' => 'Content\'s Max Width: Custom',
				'min' => '0',
				'max' => '100',
				'append' => '%',
				'default_value' => '100'
			])
				->conditional('content_max_width', '==', 'custom')
			->addAccordion('background_image')
				->addImage('background_image', [
					'wrapper' => [
						'class' => 'acf-field-fr-bordered'
					],
				])
				->addRadio('background_image_size', [
					'label' => 'Background Image: Desktop Size',
					'choices' => [
						'contain' => 'Cover (Covers available space)',
						'auto' => 'Auto (Image\'s real size)'
					],
					'default_value' => 'contain',
					'wrapper' => [
						'class' => 'acf-field-fr-bordered'
					],
				])
				->addImage('background_image_mobile', [
					'wrapper' => [
						'class' => 'acf-field-fr-bordered'
					],
				])
				->addRadio('background_image_size_mobile', [
					'label' => 'Background Image: Mobile Size',
					'choices' => [
						'contain' => 'Cover (Covers available space)',
						'auto' => 'Auto (image\'s real size)'
					],
					'default_value' => 'contain',
					'wrapper' => [
						'class' => 'acf-field-fr-bordered'
					],
				])
				->addField('background_image_dimensions', 'dimensions', [
					'label' => 'Background Image: Margins',
					'wrapper' => [
						'class' => 'acf-field-fr-bordered'
					],
					'return_format' => 'array'
				])
					->conditional('background_image_size', '==', 'auto')
						->or('background_image_size_mobile', '==', 'auto')
				->addTrueFalse('background_color_overlay', [
					'label' => 'Enable Color Overlay?'
				])
				->addTrueFalse('enable_custom_color_overlay', [
					'label' => 'Select Custom Color'
				])
					->conditional('background_color_overlay', '==', '1')
				->addColorPicker('custom_overlay_color', [
					'label' => 'Custom Overlay Color'
				])
					->conditional('background_color_overlay', '==', '1')
					->and('enable_custom_color_overlay', '==', '1')
				->addRange('background_color_overlay_opacity', [
					'label' => 'Overlay Opacity',
					'min' => '0',
					'max' => '100',
					'append' => '%',
					'default_value' => '50'
				])
					->conditional('background_color_overlay', '==', '1')				
			->addAccordion('background_image_end')->endpoint()
			->addAccordion('height')
				->addRadio('min_height', [
					'wpml_cf_preferences' => 0,
					'choices' => [
						'default' => 'Default',
						'custom' => 'Custom'
					]
				])
				->addNumber('min_height_value', [
					'label' => 'Add Min Height',
					'min' => 0,
					'append' => 'px'
				])
					->conditional('min_height', '!=', 'default')
			->addAccordion('height_end')->endpoint()
			->addAccordion('padding')
				->addRadio('vertical_padding', [
					'wpml_cf_preferences' => 0,
					'choices' => [
						'default' => 'Default',
						'none' => 'none'
					]
				])
			->addAccordion('padding_end')->endpoint()
			->addAccordion('content')
				->addRadio('vertically_stack_content', [
					'wpml_cf_preferences' => 0,
					'choices' => [
						'top' => 'top',
						'middle' => 'middle',
						'bottom' => 'bottom'
					],
					'default_value' => 'middle'
				])
			->addAccordion('content_end')->endpoint();

		return $contentSection->build();
	}

	public function getContentMaxWidth()
	{
		$result = '';
		$content_max_width = get_field('content_max_width');
		
		switch ($content_max_width) {
			case 'default':
				$result = '';
				break;
			case 'full_width':
				$result = 'full-width';
				break;
			case 'custom':
				$result = 'custom';
				break;
			default:
				break;
		}

		return $result;
	}

	public function getCustomContentMaxWidthClass(){
		$content_max_width = $this->getContentMaxWidth();
		return $content_max_width == 'custom' ? 'fr-container--max-'.get_field('custom_content_max_width') : false;
	}

	public function getColorOverlayAttr(){
		$result = [];
		$enabled = get_field('background_color_overlay');
		$opacity = get_field('background_color_overlay_opacity');
		
		if($enabled){
			$custom_color = get_field('enable_custom_color_overlay') ? get_field('custom_overlay_color') : '#FFF';

			$result = array_filter([
				$custom_color ? 'background-color:'.$custom_color : false,
				$opacity ? 'opacity:' .floatval($opacity / 100) : false
			]);
		}

		return 'style="' . implode(';', $result) . '"';
	}

	public function getMinHeight(){
		$result = [];
		$min_height = get_field('min_height');
		if($min_height && $min_height !== 'default'){
			$result = [';min-height:'.get_field('min_height_value').'px;'];
		}

		return $result;
	}

	public function getContainerAtts(){
		$result = '';

		$acc = [];

		$acc = array_merge( $acc, array_filter( $this->getMinHeight() ) );

		$result = 'style="' . implode(';', $acc) . '"';

		return $result;
	}

	/**
	 * Return the items field.
	 *
	 * @return array
	 */
	public function items()
	{
		
		return [
			'background_color' => get_field('background_color') ? 'section-bg-' . get_field('background_color') : 'section-bg-white',
			'show_top_wave' => get_field('show_top_wave')?'has-top-wave':'',
			'background_image' => get_field('background_image'),
			'background_image_size' => get_field('background_image_size'),
			'background_image_dimensions' => $this->createCssRules('desktop', get_field('background_image_dimensions')),
			'content_max_width' => $this->getContentMaxWidth(),
			'container_atts' => $this->getContainerAtts(),
			'background_color_overlay' => $this->getColorOverlayAttr(),
			'custom_max_width_class' => $this->getCustomContentMaxWidthClass(),
			'background_image_mobile' =>  get_field('background_image_mobile') ?: get_field('background_image'),
			'background_image_mobile_size' => get_field('background_image_size_mobile'),
			'background_image_dimensions_mobile' => $this->createCssRules('mobile', get_field('background_image_dimensions')),
			'vertical_padding' => get_field('vertical_padding'),
			'vertically_stack_content' => get_field('vertically_stack_content'),
		];
	}

	public function createCssRules($breakpoint, $margins) {
		if(!$margins) return '';

		$css = [
			($margins[$breakpoint]['top'] === 'auto' ? $margins[$breakpoint]['top'] : $margins[$breakpoint]['top'].$margins[$breakpoint]['unit']),
            ($margins[$breakpoint]['right'] === 'auto' ? $margins[$breakpoint]['right'] : $margins[$breakpoint]['right'].$margins[$breakpoint]['unit']),
            ($margins[$breakpoint]['bottom'] === 'auto' ? $margins[$breakpoint]['bottom'] : $margins[$breakpoint]['bottom'].$margins[$breakpoint]['unit']),
            ($margins[$breakpoint]['left'] === 'auto' ? $margins[$breakpoint]['left'] : $margins[$breakpoint]['left'].$margins[$breakpoint]['unit']),
        ];

		return implode(' ', $css);
	}
	
	/**
	 * Assets to be enqueued when rendering the block.
	 *
	 * @return void
	 */
	public function enqueue()
	{
		//
	}
}
