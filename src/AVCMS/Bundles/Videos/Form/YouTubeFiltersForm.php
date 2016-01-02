<?php
/**
 * User: Andy
 * Date: 26/12/2015
 * Time: 20:04
 */

namespace AVCMS\Bundles\Videos\Form;

use AV\Form\FormBlueprint;

class YouTubeFiltersForm extends FormBlueprint
{
    public function __construct()
    {
        $this->setName('filter_form');

        $this->add('q', 'text', [
            'label' => 'Search',
            'attr' => [
                'placeholder' => 'Search',
                'data-no-auto-search' => 1
            ],
            'field_template' => '@admin/form_fields/search_field_with_submit.twig'
        ]);


        $this->add('videoDuration', 'select', [
            'label' => 'Duration',
            'choices' => [
                'any' => 'Any Duration',
                'long' => 'Long (20+ mins)',
                'medium' => 'Medium (4-20 mins)',
                'short' => 'Short (< 4 mins)'
            ]
        ]);

        /*
        $this->add('relevanceLanguage', 'select', [
            'choices' => $this->getCountryCodes()
        ]);
        */

        $this->add('order', 'select', [
            'choices' => [
                'relevance' => 'Most Relevant',
                'date' => 'Newest',
                'rating' => 'Top Rated',
                'title' => 'Title A-Z',
                'viewCount' => 'Most Views'
            ]
        ]);

        $this->add('videoDefinition', 'checkbox', [
            'label' => 'HD Only',
            'checked_value' => 'high',
            'unchecked_value' => 'any'
        ]);
    }

    private function getCountryCodes()
    {
        return [
            0 => 'All',

            'aa' => 'Afar',

            'ab' => 'Abkhazian',

            'af' => 'Afrikaans',

            'am' => 'Amharic',

            'ar' => 'Arabic',

            'as' => 'Assamese',

            'ay' => 'Aymara',

            'az' => 'Azerbaijani',

            'ba' => 'Bashkir',

            'be' => 'Byelorussian',

            'bg' => 'Bulgarian',

            'bh' => 'Bihari',

            'bi' => 'Bislama',

            'bn' => 'Bengali; Bangla',

            'bo' => 'Tibetan',

            'br' => 'Breton',

            'ca' => 'Catalan',

            'co' => 'Corsican',

            'cs' => 'Czech',

            'cy' => 'Welsh',

            'da' => 'Danish',

            'de' => 'German',

            'dz' => 'Bhutani',

            'el' => 'Greek',

            'en' => 'English',

            'eo' => 'Esperanto',

            'es' => 'Spanish',

            'et' => 'Estonian',

            'eu' => 'Basque',

            'fa' => 'Persian',

            'fi' => 'Finnish',

            'fj' => 'Fiji',

            'fo' => 'Faeroese',

            'fr' => 'French',

            'fy' => 'Frisian',

            'ga' => 'Irish',

            'gd' => 'Scots Gaelic',

            'gl' => 'Galician',

            'gn' => 'Guarani',

            'gu' => 'Gujarati',

            'ha' => 'Hausa',

            'hi' => 'Hindi',

            'hr' => 'Croatian',

            'hu' => 'Hungarian',

            'hy' => 'Armenian',

            'ia' => 'Interlingua',

            'ie' => 'Interlingue',

            'ik' => 'Inupiak',

            'in' => 'Indonesian',

            'is' => 'Icelandic',

            'it' => 'Italian',

            'iw' => 'Hebrew',

            'ja' => 'Japanese',

            'ji' => 'Yiddish',

            'jw' => 'Javanese',

            'ka' => 'Georgian',

            'kk' => 'Kazakh',

            'kl' => 'Greenlandic',

            'km' => 'Cambodian',

            'kn' => 'Kannada',

            'ko' => 'Korean',

            'ks' => 'Kashmiri',

            'ku' => 'Kurdish',

            'ky' => 'Kirghiz',

            'la' => 'Latin',

            'ln' => 'Lingala',

            'lo' => 'Laothian',

            'lt' => 'Lithuanian',

            'lv' => 'Latvian, Lettish',

            'mg' => 'Malagasy',

            'mi' => 'Maori',

            'mk' => 'Macedonian',

            'ml' => 'Malayalam',

            'mn' => 'Mongolian',

            'mo' => 'Moldavian',

            'mr' => 'Marathi',

            'ms' => 'Malay',

            'mt' => 'Maltese',

            'my' => 'Burmese',

            'na' => 'Nauru',

            'ne' => 'Nepali',

            'nl' => 'Dutch',

            'no' => 'Norwegian',

            'oc' => 'Occitan',

            'om' => '(Afan) Oromo',

            'or' => 'Oriya',

            'pa' => 'Punjabi',

            'pl' => 'Polish',

            'ps' => 'Pashto, Pushto',

            'pt' => 'Portuguese',

            'qu' => 'Quechua',

            'rm' => 'Rhaeto-Romance',

            'rn' => 'Kirundi',

            'ro' => 'Romanian',

            'ru' => 'Russian',

            'rw' => 'Kinyarwanda',

            'sa' => 'Sanskrit',

            'sd' => 'Sindhi',

            'sg' => 'Sangro',

            'sh' => 'Serbo-Croatian',

            'si' => 'Singhalese',

            'sk' => 'Slovak',

            'sl' => 'Slovenian',

            'sm' => 'Samoan',

            'sn' => 'Shona',

            'so' => 'Somali',

            'sq' => 'Albanian',

            'sr' => 'Serbian',

            'ss' => 'Siswati',

            'st' => 'Sesotho',

            'su' => 'Sundanese',

            'sv' => 'Swedish',

            'sw' => 'Swahili',

            'ta' => 'Tamil',

            'te' => 'Tegulu',

            'tg' => 'Tajik',

            'th' => 'Thai',

            'ti' => 'Tigrinya',

            'tk' => 'Turkmen',

            'tl' => 'Tagalog',

            'tn' => 'Setswana',

            'to' => 'Tonga',

            'tr' => 'Turkish',

            'ts' => 'Tsonga',

            'tt' => 'Tatar',

            'tw' => 'Twi',

            'uk' => 'Ukrainian',

            'ur' => 'Urdu',

            'uz' => 'Uzbek',

            'vi' => 'Vietnamese',

            'vo' => 'Volapuk',

            'wo' => 'Wolof',

            'xh' => 'Xhosa',

            'yo' => 'Yoruba',

            'zh-Hans' => 'Chinese (Simplified)',

            'zh-Hant' => 'Chinese (Traditional)',

            'zu' => 'Zulu'
        ];
    }
}
