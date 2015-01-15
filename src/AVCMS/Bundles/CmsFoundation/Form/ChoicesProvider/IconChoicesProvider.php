<?php
/**
 * User: Andy
 * Date: 15/01/15
 * Time: 13:47
 */

namespace AVCMS\Bundles\CmsFoundation\Form\ChoicesProvider;

use AV\Form\ChoicesProviderInterface;

class IconChoicesProvider implements ChoicesProviderInterface
{
    public function getChoices()
    {
        return [
            'glyphicon glyphicon-asterisk' => 'glyphicon glyphicon-asterisk',
            'glyphicon glyphicon-plus' => 'glyphicon glyphicon-plus',
            'glyphicon glyphicon-euro' => 'glyphicon glyphicon-euro',
            'glyphicon glyphicon-minus' => 'glyphicon glyphicon-minus',
            'glyphicon glyphicon-cloud' => 'glyphicon glyphicon-cloud',
            'glyphicon glyphicon-envelope' => 'glyphicon glyphicon-envelope',
            'glyphicon glyphicon-pencil' => 'glyphicon glyphicon-pencil',
            'glyphicon glyphicon-glass' => 'glyphicon glyphicon-glass',
            'glyphicon glyphicon-music' => 'glyphicon glyphicon-music',
            'glyphicon glyphicon-search' => 'glyphicon glyphicon-search',
            'glyphicon glyphicon-heart' => 'glyphicon glyphicon-heart',
            'glyphicon glyphicon-star' => 'glyphicon glyphicon-star',
            'glyphicon glyphicon-star-empty' => 'glyphicon glyphicon-star-empty',
            'glyphicon glyphicon-user' => 'glyphicon glyphicon-user',
            'glyphicon glyphicon-film' => 'glyphicon glyphicon-film',
            'glyphicon glyphicon-th-large' => 'glyphicon glyphicon-th-large',
            'glyphicon glyphicon-th' => 'glyphicon glyphicon-th',
            'glyphicon glyphicon-th-list' => 'glyphicon glyphicon-th-list',
            'glyphicon glyphicon-ok' => 'glyphicon glyphicon-ok',
            'glyphicon glyphicon-remove' => 'glyphicon glyphicon-remove',
            'glyphicon glyphicon-zoom-in' => 'glyphicon glyphicon-zoom-in',
            'glyphicon glyphicon-zoom-out' => 'glyphicon glyphicon-zoom-out',
            'glyphicon glyphicon-off' => 'glyphicon glyphicon-off',
            'glyphicon glyphicon-signal' => 'glyphicon glyphicon-signal',
            'glyphicon glyphicon-cog' => 'glyphicon glyphicon-cog',
            'glyphicon glyphicon-trash' => 'glyphicon glyphicon-trash',
            'glyphicon glyphicon-home' => 'glyphicon glyphicon-home',
            'glyphicon glyphicon-file' => 'glyphicon glyphicon-file',
            'glyphicon glyphicon-time' => 'glyphicon glyphicon-time',
            'glyphicon glyphicon-road' => 'glyphicon glyphicon-road',
            'glyphicon glyphicon-download-alt' => 'glyphicon glyphicon-download-alt',
            'glyphicon glyphicon-download' => 'glyphicon glyphicon-download',
            'glyphicon glyphicon-upload' => 'glyphicon glyphicon-upload',
            'glyphicon glyphicon-inbox' => 'glyphicon glyphicon-inbox',
            'glyphicon glyphicon-play-circle' => 'glyphicon glyphicon-play-circle',
            'glyphicon glyphicon-repeat' => 'glyphicon glyphicon-repeat',
            'glyphicon glyphicon-refresh' => 'glyphicon glyphicon-refresh',
            'glyphicon glyphicon-list-alt' => 'glyphicon glyphicon-list-alt',
            'glyphicon glyphicon-lock' => 'glyphicon glyphicon-lock',
            'glyphicon glyphicon-flag' => 'glyphicon glyphicon-flag',
            'glyphicon glyphicon-headphones' => 'glyphicon glyphicon-headphones',
            'glyphicon glyphicon-volume-off' => 'glyphicon glyphicon-volume-off',
            'glyphicon glyphicon-volume-down' => 'glyphicon glyphicon-volume-down',
            'glyphicon glyphicon-volume-up' => 'glyphicon glyphicon-volume-up',
            'glyphicon glyphicon-qrcode' => 'glyphicon glyphicon-qrcode',
            'glyphicon glyphicon-barcode' => 'glyphicon glyphicon-barcode',
            'glyphicon glyphicon-tag' => 'glyphicon glyphicon-tag',
            'glyphicon glyphicon-tags' => 'glyphicon glyphicon-tags',
            'glyphicon glyphicon-book' => 'glyphicon glyphicon-book',
            'glyphicon glyphicon-bookmark' => 'glyphicon glyphicon-bookmark',
            'glyphicon glyphicon-print' => 'glyphicon glyphicon-print',
            'glyphicon glyphicon-camera' => 'glyphicon glyphicon-camera',
            'glyphicon glyphicon-font' => 'glyphicon glyphicon-font',
            'glyphicon glyphicon-bold' => 'glyphicon glyphicon-bold',
            'glyphicon glyphicon-italic' => 'glyphicon glyphicon-italic',
            'glyphicon glyphicon-text-height' => 'glyphicon glyphicon-text-height',
            'glyphicon glyphicon-text-width' => 'glyphicon glyphicon-text-width',
            'glyphicon glyphicon-align-left' => 'glyphicon glyphicon-align-left',
            'glyphicon glyphicon-align-center' => 'glyphicon glyphicon-align-center',
            'glyphicon glyphicon-align-right' => 'glyphicon glyphicon-align-right',
            'glyphicon glyphicon-align-justify' => 'glyphicon glyphicon-align-justify',
            'glyphicon glyphicon-list' => 'glyphicon glyphicon-list',
            'glyphicon glyphicon-indent-left' => 'glyphicon glyphicon-indent-left',
            'glyphicon glyphicon-indent-right' => 'glyphicon glyphicon-indent-right',
            'glyphicon glyphicon-facetime-video' => 'glyphicon glyphicon-facetime-video',
            'glyphicon glyphicon-picture' => 'glyphicon glyphicon-picture',
            'glyphicon glyphicon-map-marker' => 'glyphicon glyphicon-map-marker',
            'glyphicon glyphicon-adjust' => 'glyphicon glyphicon-adjust',
            'glyphicon glyphicon-tint' => 'glyphicon glyphicon-tint',
            'glyphicon glyphicon-edit' => 'glyphicon glyphicon-edit',
            'glyphicon glyphicon-share' => 'glyphicon glyphicon-share',
            'glyphicon glyphicon-check' => 'glyphicon glyphicon-check',
            'glyphicon glyphicon-move' => 'glyphicon glyphicon-move',
            'glyphicon glyphicon-step-backward' => 'glyphicon glyphicon-step-backward',
            'glyphicon glyphicon-fast-backward' => 'glyphicon glyphicon-fast-backward',
            'glyphicon glyphicon-backward' => 'glyphicon glyphicon-backward',
            'glyphicon glyphicon-play' => 'glyphicon glyphicon-play',
            'glyphicon glyphicon-pause' => 'glyphicon glyphicon-pause',
            'glyphicon glyphicon-stop' => 'glyphicon glyphicon-stop',
            'glyphicon glyphicon-forward' => 'glyphicon glyphicon-forward',
            'glyphicon glyphicon-fast-forward' => 'glyphicon glyphicon-fast-forward',
            'glyphicon glyphicon-step-forward' => 'glyphicon glyphicon-step-forward',
            'glyphicon glyphicon-eject' => 'glyphicon glyphicon-eject',
            'glyphicon glyphicon-chevron-left' => 'glyphicon glyphicon-chevron-left',
            'glyphicon glyphicon-chevron-right' => 'glyphicon glyphicon-chevron-right',
            'glyphicon glyphicon-plus-sign' => 'glyphicon glyphicon-plus-sign',
            'glyphicon glyphicon-minus-sign' => 'glyphicon glyphicon-minus-sign',
            'glyphicon glyphicon-remove-sign' => 'glyphicon glyphicon-remove-sign',
            'glyphicon glyphicon-ok-sign' => 'glyphicon glyphicon-ok-sign',
            'glyphicon glyphicon-question-sign' => 'glyphicon glyphicon-question-sign',
            'glyphicon glyphicon-info-sign' => 'glyphicon glyphicon-info-sign',
            'glyphicon glyphicon-screenshot' => 'glyphicon glyphicon-screenshot',
            'glyphicon glyphicon-remove-circle' => 'glyphicon glyphicon-remove-circle',
            'glyphicon glyphicon-ok-circle' => 'glyphicon glyphicon-ok-circle',
            'glyphicon glyphicon-ban-circle' => 'glyphicon glyphicon-ban-circle',
            'glyphicon glyphicon-arrow-left' => 'glyphicon glyphicon-arrow-left',
            'glyphicon glyphicon-arrow-right' => 'glyphicon glyphicon-arrow-right',
            'glyphicon glyphicon-arrow-up' => 'glyphicon glyphicon-arrow-up',
            'glyphicon glyphicon-arrow-down' => 'glyphicon glyphicon-arrow-down',
            'glyphicon glyphicon-share-alt' => 'glyphicon glyphicon-share-alt',
            'glyphicon glyphicon-resize-full' => 'glyphicon glyphicon-resize-full',
            'glyphicon glyphicon-resize-small' => 'glyphicon glyphicon-resize-small',
            'glyphicon glyphicon-exclamation-sign' => 'glyphicon glyphicon-exclamation-sign',
            'glyphicon glyphicon-gift' => 'glyphicon glyphicon-gift',
            'glyphicon glyphicon-leaf' => 'glyphicon glyphicon-leaf',
            'glyphicon glyphicon-fire' => 'glyphicon glyphicon-fire',
            'glyphicon glyphicon-eye-open' => 'glyphicon glyphicon-eye-open',
            'glyphicon glyphicon-eye-close' => 'glyphicon glyphicon-eye-close',
            'glyphicon glyphicon-warning-sign' => 'glyphicon glyphicon-warning-sign',
            'glyphicon glyphicon-plane' => 'glyphicon glyphicon-plane',
            'glyphicon glyphicon-calendar' => 'glyphicon glyphicon-calendar',
            'glyphicon glyphicon-random' => 'glyphicon glyphicon-random',
            'glyphicon glyphicon-comment' => 'glyphicon glyphicon-comment',
            'glyphicon glyphicon-magnet' => 'glyphicon glyphicon-magnet',
            'glyphicon glyphicon-chevron-up' => 'glyphicon glyphicon-chevron-up',
            'glyphicon glyphicon-chevron-down' => 'glyphicon glyphicon-chevron-down',
            'glyphicon glyphicon-retweet' => 'glyphicon glyphicon-retweet',
            'glyphicon glyphicon-shopping-cart' => 'glyphicon glyphicon-shopping-cart',
            'glyphicon glyphicon-folder-close' => 'glyphicon glyphicon-folder-close',
            'glyphicon glyphicon-folder-open' => 'glyphicon glyphicon-folder-open',
            'glyphicon glyphicon-resize-vertical' => 'glyphicon glyphicon-resize-vertical',
            'glyphicon glyphicon-resize-horizontal' => 'glyphicon glyphicon-resize-horizontal',
            'glyphicon glyphicon-hdd' => 'glyphicon glyphicon-hdd',
            'glyphicon glyphicon-bullhorn' => 'glyphicon glyphicon-bullhorn',
            'glyphicon glyphicon-bell' => 'glyphicon glyphicon-bell',
            'glyphicon glyphicon-certificate' => 'glyphicon glyphicon-certificate',
            'glyphicon glyphicon-thumbs-up' => 'glyphicon glyphicon-thumbs-up',
            'glyphicon glyphicon-thumbs-down' => 'glyphicon glyphicon-thumbs-down',
            'glyphicon glyphicon-hand-right' => 'glyphicon glyphicon-hand-right',
            'glyphicon glyphicon-hand-left' => 'glyphicon glyphicon-hand-left',
            'glyphicon glyphicon-hand-up' => 'glyphicon glyphicon-hand-up',
            'glyphicon glyphicon-hand-down' => 'glyphicon glyphicon-hand-down',
            'glyphicon glyphicon-circle-arrow-right' => 'glyphicon glyphicon-circle-arrow-right',
            'glyphicon glyphicon-circle-arrow-left' => 'glyphicon glyphicon-circle-arrow-left',
            'glyphicon glyphicon-circle-arrow-up' => 'glyphicon glyphicon-circle-arrow-up',
            'glyphicon glyphicon-circle-arrow-down' => 'glyphicon glyphicon-circle-arrow-down',
            'glyphicon glyphicon-globe' => 'glyphicon glyphicon-globe',
            'glyphicon glyphicon-wrench' => 'glyphicon glyphicon-wrench',
            'glyphicon glyphicon-tasks' => 'glyphicon glyphicon-tasks',
            'glyphicon glyphicon-filter' => 'glyphicon glyphicon-filter',
            'glyphicon glyphicon-briefcase' => 'glyphicon glyphicon-briefcase',
            'glyphicon glyphicon-fullscreen' => 'glyphicon glyphicon-fullscreen',
            'glyphicon glyphicon-dashboard' => 'glyphicon glyphicon-dashboard',
            'glyphicon glyphicon-paperclip' => 'glyphicon glyphicon-paperclip',
            'glyphicon glyphicon-heart-empty' => 'glyphicon glyphicon-heart-empty',
            'glyphicon glyphicon-link' => 'glyphicon glyphicon-link',
            'glyphicon glyphicon-phone' => 'glyphicon glyphicon-phone',
            'glyphicon glyphicon-pushpin' => 'glyphicon glyphicon-pushpin',
            'glyphicon glyphicon-usd' => 'glyphicon glyphicon-usd',
            'glyphicon glyphicon-gbp' => 'glyphicon glyphicon-gbp',
            'glyphicon glyphicon-sort' => 'glyphicon glyphicon-sort',
            'glyphicon glyphicon-sort-by-alphabet' => 'glyphicon glyphicon-sort-by-alphabet',
            'glyphicon glyphicon-sort-by-alphabet-alt' => 'glyphicon glyphicon-sort-by-alphabet-alt',
            'glyphicon glyphicon-sort-by-order' => 'glyphicon glyphicon-sort-by-order',
            'glyphicon glyphicon-sort-by-order-alt' => 'glyphicon glyphicon-sort-by-order-alt',
            'glyphicon glyphicon-sort-by-attributes' => 'glyphicon glyphicon-sort-by-attributes',
            'glyphicon glyphicon-sort-by-attributes-alt' => 'glyphicon glyphicon-sort-by-attributes-alt',
            'glyphicon glyphicon-unchecked' => 'glyphicon glyphicon-unchecked',
            'glyphicon glyphicon-expand' => 'glyphicon glyphicon-expand',
            'glyphicon glyphicon-collapse-down' => 'glyphicon glyphicon-collapse-down',
            'glyphicon glyphicon-collapse-up' => 'glyphicon glyphicon-collapse-up',
            'glyphicon glyphicon-log-in' => 'glyphicon glyphicon-log-in',
            'glyphicon glyphicon-flash' => 'glyphicon glyphicon-flash',
            'glyphicon glyphicon-log-out' => 'glyphicon glyphicon-log-out',
            'glyphicon glyphicon-new-window' => 'glyphicon glyphicon-new-window',
            'glyphicon glyphicon-record' => 'glyphicon glyphicon-record',
            'glyphicon glyphicon-save' => 'glyphicon glyphicon-save',
            'glyphicon glyphicon-open' => 'glyphicon glyphicon-open',
            'glyphicon glyphicon-saved' => 'glyphicon glyphicon-saved',
            'glyphicon glyphicon-import' => 'glyphicon glyphicon-import',
            'glyphicon glyphicon-export' => 'glyphicon glyphicon-export',
            'glyphicon glyphicon-send' => 'glyphicon glyphicon-send',
            'glyphicon glyphicon-floppy-disk' => 'glyphicon glyphicon-floppy-disk',
            'glyphicon glyphicon-floppy-saved' => 'glyphicon glyphicon-floppy-saved',
            'glyphicon glyphicon-floppy-remove' => 'glyphicon glyphicon-floppy-remove',
            'glyphicon glyphicon-floppy-save' => 'glyphicon glyphicon-floppy-save',
            'glyphicon glyphicon-floppy-open' => 'glyphicon glyphicon-floppy-open',
            'glyphicon glyphicon-credit-card' => 'glyphicon glyphicon-credit-card',
            'glyphicon glyphicon-transfer' => 'glyphicon glyphicon-transfer',
            'glyphicon glyphicon-cutlery' => 'glyphicon glyphicon-cutlery',
            'glyphicon glyphicon-header' => 'glyphicon glyphicon-header',
            'glyphicon glyphicon-compressed' => 'glyphicon glyphicon-compressed',
            'glyphicon glyphicon-earphone' => 'glyphicon glyphicon-earphone',
            'glyphicon glyphicon-phone-alt' => 'glyphicon glyphicon-phone-alt',
            'glyphicon glyphicon-tower' => 'glyphicon glyphicon-tower',
            'glyphicon glyphicon-stats' => 'glyphicon glyphicon-stats',
            'glyphicon glyphicon-sd-video' => 'glyphicon glyphicon-sd-video',
            'glyphicon glyphicon-hd-video' => 'glyphicon glyphicon-hd-video',
            'glyphicon glyphicon-subtitles' => 'glyphicon glyphicon-subtitles',
            'glyphicon glyphicon-sound-stereo' => 'glyphicon glyphicon-sound-stereo',
            'glyphicon glyphicon-sound-dolby' => 'glyphicon glyphicon-sound-dolby',
            'glyphicon glyphicon-sound-5-1' => 'glyphicon glyphicon-sound-5-1',
            'glyphicon glyphicon-sound-6-1' => 'glyphicon glyphicon-sound-6-1',
            'glyphicon glyphicon-sound-7-1' => 'glyphicon glyphicon-sound-7-1',
            'glyphicon glyphicon-copyright-mark' => 'glyphicon glyphicon-copyright-mark',
            'glyphicon glyphicon-registration-mark' => 'glyphicon glyphicon-registration-mark',
            'glyphicon glyphicon-cloud-download' => 'glyphicon glyphicon-cloud-download',
            'glyphicon glyphicon-cloud-upload' => 'glyphicon glyphicon-cloud-upload',
            'glyphicon glyphicon-tree-conifer' => 'glyphicon glyphicon-tree-conifer',
            'glyphicon glyphicon-tree-deciduous' => 'glyphicon glyphicon-tree-deciduous',
        ];
    }
}
