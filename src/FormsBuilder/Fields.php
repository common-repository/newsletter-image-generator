<?php

namespace Oaattia\WoocommerceGenerator\FormsBuilder;

use  Oaattia\WoocommerceGenerator\Subscription ;
class Fields
{
    const  FREE_POSITIONS = array(
        'Left area top'    => 'topLeft',
        'Left area middle' => 'left',
    ) ;
    const  PREMIUM_POSITIONS = array(
        'Left area bottom'  => 'bottomLeft',
        'Right area top'    => 'topRight',
        'Right area middle' => 'right',
        'Right area bottom' => 'bottomRight',
    ) ;
    const  POSITIONS = self::FREE_POSITIONS + self::PREMIUM_POSITIONS ;
    /**
     * @var array
     */
    private  $fields ;
    /**
     * Create fields
     *
     * @return Fields
     */
    public static function create()
    {
        $class = new self();
        foreach ( self::POSITIONS as $key => $position ) {
            $class->fields[] = $class->createFields( $key, $position );
        }
        return $class;
    }
    
    /**
     * @return array
     */
    public function getFields()
    {
        return $this->fields;
    }
    
    /**
     * @param string $sectionTitle
     * @param string $fieldName
     *
     * @return array
     */
    private function createFields( $sectionTitle, $fieldName )
    {
        $freeFields = [
            'sectionTitle'                               => $sectionTitle,
            $fieldName . '[showProductTitle]'            => [
            'title'    => 'Enable',
            'type'     => 'checkbox',
            'desc'     => 'Show product title',
            'default'  => 'no',
            'css'      => 'max-width:300px;',
            'desc_tip' => 'If you want to enable this part, show the product title, just enable it first and add the position below',
        ],
            $fieldName . '[productTitleXoffset]'         => [
            'title'    => 'Product title X position',
            'type'     => 'number',
            'desc'     => 'The product title x position in the page in pixels',
            'css'      => 'max-width:500px;',
            'desc_tip' => 'The product title x position in the page in pixels',
        ],
            $fieldName . '[productTitleYoffset]'         => [
            'title'    => 'Product title Y position',
            'type'     => 'number',
            'desc'     => 'The product title y position in the page in pixels',
            'css'      => 'max-width:500px;',
            'desc_tip' => 'The product title y position in the page in pixels',
        ],
            $fieldName . '[productTitleColor]'           => [
            'title'    => 'Text color (hex)',
            'type'     => 'color',
            'desc'     => 'Add the color in hex format.',
            'css'      => 'max-width:300px;',
            'default'  => 'no',
            'desc_tip' => 'Add the color in hex format.',
        ],
            $fieldName . '[productTitleSize]'            => [
            'title'    => 'Text size (pixels)',
            'type'     => 'number',
            'desc'     => 'Add the size of the text here in pixels.',
            'css'      => 'max-width:300px;',
            'default'  => 'no',
            'desc_tip' => 'Add the size of the text here in pixels',
        ],
            $fieldName . '[productTitleBackgroundColor]' => [
            'title'       => 'Product title Background Color',
            'type'        => 'color',
            'description' => 'Add the color in hex format',
            'desc'        => 'Add the color background in hex format.',
            'css'         => 'max-width:300px;',
            'desc_tip'    => 'Add the color background in hex format.',
        ],
        ];
        $premiumFields = [];
        return array_merge( $freeFields, $premiumFields );
    }
    
    private function __construct()
    {
    }

}