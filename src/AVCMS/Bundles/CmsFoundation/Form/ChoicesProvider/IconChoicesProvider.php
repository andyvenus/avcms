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
            'glyphicon glyphicon-asterisk' => 'asterisk',
            'glyphicon glyphicon-plus' => 'plus',
            'glyphicon glyphicon-euro' => 'euro',
            'glyphicon glyphicon-eur' => 'eur',
            'glyphicon glyphicon-minus' => 'minus',
            'glyphicon glyphicon-cloud' => 'cloud',
            'glyphicon glyphicon-envelope' => 'envelope',
            'glyphicon glyphicon-pencil' => 'pencil',
            'glyphicon glyphicon-glass' => 'glass',
            'glyphicon glyphicon-music' => 'music',
            'glyphicon glyphicon-search' => 'search',
            'glyphicon glyphicon-heart' => 'heart',
            'glyphicon glyphicon-star' => 'star',
            'glyphicon glyphicon-star-empty' => 'star-empty',
            'glyphicon glyphicon-user' => 'user',
            'glyphicon glyphicon-film' => 'film',
            'glyphicon glyphicon-th-large' => 'th-large',
            'glyphicon glyphicon-th' => 'th',
            'glyphicon glyphicon-th-list' => 'th-list',
            'glyphicon glyphicon-ok' => 'ok',
            'glyphicon glyphicon-remove' => 'remove',
            'glyphicon glyphicon-zoom-in' => 'zoom-in',
            'glyphicon glyphicon-zoom-out' => 'zoom-out',
            'glyphicon glyphicon-off' => 'off',
            'glyphicon glyphicon-signal' => 'signal',
            'glyphicon glyphicon-cog' => 'cog',
            'glyphicon glyphicon-trash' => 'trash',
            'glyphicon glyphicon-home' => 'home',
            'glyphicon glyphicon-file' => 'file',
            'glyphicon glyphicon-time' => 'time',
            'glyphicon glyphicon-road' => 'road',
            'glyphicon glyphicon-download-alt' => 'download-alt',
            'glyphicon glyphicon-download' => 'download',
            'glyphicon glyphicon-upload' => 'upload',
            'glyphicon glyphicon-inbox' => 'inbox',
            'glyphicon glyphicon-play-circle' => 'play-circle',
            'glyphicon glyphicon-repeat' => 'repeat',
            'glyphicon glyphicon-refresh' => 'refresh',
            'glyphicon glyphicon-list-alt' => 'list-alt',
            'glyphicon glyphicon-lock' => 'lock',
            'glyphicon glyphicon-flag' => 'flag',
            'glyphicon glyphicon-headphones' => 'headphones',
            'glyphicon glyphicon-volume-off' => 'volume-off',
            'glyphicon glyphicon-volume-down' => 'volume-down',
            'glyphicon glyphicon-volume-up' => 'volume-up',
            'glyphicon glyphicon-qrcode' => 'qrcode',
            'glyphicon glyphicon-barcode' => 'barcode',
            'glyphicon glyphicon-tag' => 'tag',
            'glyphicon glyphicon-tags' => 'tags',
            'glyphicon glyphicon-book' => 'book',
            'glyphicon glyphicon-bookmark' => 'bookmark',
            'glyphicon glyphicon-print' => 'print',
            'glyphicon glyphicon-camera' => 'camera',
            'glyphicon glyphicon-font' => 'font',
            'glyphicon glyphicon-bold' => 'bold',
            'glyphicon glyphicon-italic' => 'italic',
            'glyphicon glyphicon-text-height' => 'text-height',
            'glyphicon glyphicon-text-width' => 'text-width',
            'glyphicon glyphicon-align-left' => 'align-left',
            'glyphicon glyphicon-align-center' => 'align-center',
            'glyphicon glyphicon-align-right' => 'align-right',
            'glyphicon glyphicon-align-justify' => 'align-justify',
            'glyphicon glyphicon-list' => 'list',
            'glyphicon glyphicon-indent-left' => 'indent-left',
            'glyphicon glyphicon-indent-right' => 'indent-right',
            'glyphicon glyphicon-facetime-video' => 'facetime-video',
            'glyphicon glyphicon-picture' => 'picture',
            'glyphicon glyphicon-map-marker' => 'map-marker',
            'glyphicon glyphicon-adjust' => 'adjust',
            'glyphicon glyphicon-tint' => 'tint',
            'glyphicon glyphicon-edit' => 'edit',
            'glyphicon glyphicon-share' => 'share',
            'glyphicon glyphicon-check' => 'check',
            'glyphicon glyphicon-move' => 'move',
            'glyphicon glyphicon-step-backward' => 'step-backward',
            'glyphicon glyphicon-fast-backward' => 'fast-backward',
            'glyphicon glyphicon-backward' => 'backward',
            'glyphicon glyphicon-play' => 'play',
            'glyphicon glyphicon-pause' => 'pause',
            'glyphicon glyphicon-stop' => 'stop',
            'glyphicon glyphicon-forward' => 'forward',
            'glyphicon glyphicon-fast-forward' => 'fast-forward',
            'glyphicon glyphicon-step-forward' => 'step-forward',
            'glyphicon glyphicon-eject' => 'eject',
            'glyphicon glyphicon-chevron-left' => 'chevron-left',
            'glyphicon glyphicon-chevron-right' => 'chevron-right',
            'glyphicon glyphicon-plus-sign' => 'plus-sign',
            'glyphicon glyphicon-minus-sign' => 'minus-sign',
            'glyphicon glyphicon-remove-sign' => 'remove-sign',
            'glyphicon glyphicon-ok-sign' => 'ok-sign',
            'glyphicon glyphicon-question-sign' => 'question-sign',
            'glyphicon glyphicon-info-sign' => 'info-sign',
            'glyphicon glyphicon-screenshot' => 'screenshot',
            'glyphicon glyphicon-remove-circle' => 'remove-circle',
            'glyphicon glyphicon-ok-circle' => 'ok-circle',
            'glyphicon glyphicon-ban-circle' => 'ban-circle',
            'glyphicon glyphicon-arrow-left' => 'arrow-left',
            'glyphicon glyphicon-arrow-right' => 'arrow-right',
            'glyphicon glyphicon-arrow-up' => 'arrow-up',
            'glyphicon glyphicon-arrow-down' => 'arrow-down',
            'glyphicon glyphicon-share-alt' => 'share-alt',
            'glyphicon glyphicon-resize-full' => 'resize-full',
            'glyphicon glyphicon-resize-small' => 'resize-small',
            'glyphicon glyphicon-exclamation-sign' => 'exclamation-sign',
            'glyphicon glyphicon-gift' => 'gift',
            'glyphicon glyphicon-leaf' => 'leaf',
            'glyphicon glyphicon-fire' => 'fire',
            'glyphicon glyphicon-eye-open' => 'eye-open',
            'glyphicon glyphicon-eye-close' => 'eye-close',
            'glyphicon glyphicon-warning-sign' => 'warning-sign',
            'glyphicon glyphicon-plane' => 'plane',
            'glyphicon glyphicon-calendar' => 'calendar',
            'glyphicon glyphicon-random' => 'random',
            'glyphicon glyphicon-comment' => 'comment',
            'glyphicon glyphicon-magnet' => 'magnet',
            'glyphicon glyphicon-chevron-up' => 'chevron-up',
            'glyphicon glyphicon-chevron-down' => 'chevron-down',
            'glyphicon glyphicon-retweet' => 'retweet',
            'glyphicon glyphicon-shopping-cart' => 'shopping-cart',
            'glyphicon glyphicon-folder-close' => 'folder-close',
            'glyphicon glyphicon-folder-open' => 'folder-open',
            'glyphicon glyphicon-resize-vertical' => 'resize-vertical',
            'glyphicon glyphicon-resize-horizontal' => 'resize-horizontal',
            'glyphicon glyphicon-hdd' => 'hdd',
            'glyphicon glyphicon-bullhorn' => 'bullhorn',
            'glyphicon glyphicon-bell' => 'bell',
            'glyphicon glyphicon-certificate' => 'certificate',
            'glyphicon glyphicon-thumbs-up' => 'thumbs-up',
            'glyphicon glyphicon-thumbs-down' => 'thumbs-down',
            'glyphicon glyphicon-hand-right' => 'hand-right',
            'glyphicon glyphicon-hand-left' => 'hand-left',
            'glyphicon glyphicon-hand-up' => 'hand-up',
            'glyphicon glyphicon-hand-down' => 'hand-down',
            'glyphicon glyphicon-circle-arrow-right' => 'circle-arrow-right',
            'glyphicon glyphicon-circle-arrow-left' => 'circle-arrow-left',
            'glyphicon glyphicon-circle-arrow-up' => 'circle-arrow-up',
            'glyphicon glyphicon-circle-arrow-down' => 'circle-arrow-down',
            'glyphicon glyphicon-globe' => 'globe',
            'glyphicon glyphicon-wrench' => 'wrench',
            'glyphicon glyphicon-tasks' => 'tasks',
            'glyphicon glyphicon-filter' => 'filter',
            'glyphicon glyphicon-briefcase' => 'briefcase',
            'glyphicon glyphicon-fullscreen' => 'fullscreen',
            'glyphicon glyphicon-dashboard' => 'dashboard',
            'glyphicon glyphicon-paperclip' => 'paperclip',
            'glyphicon glyphicon-heart-empty' => 'heart-empty',
            'glyphicon glyphicon-link' => 'link',
            'glyphicon glyphicon-phone' => 'phone',
            'glyphicon glyphicon-pushpin' => 'pushpin',
            'glyphicon glyphicon-usd' => 'usd',
            'glyphicon glyphicon-gbp' => 'gbp',
            'glyphicon glyphicon-sort' => 'sort',
            'glyphicon glyphicon-sort-by-alphabet' => 'sort-by-alphabet',
            'glyphicon glyphicon-sort-by-alphabet-alt' => 'sort-by-alphabet-alt',
            'glyphicon glyphicon-sort-by-order' => 'sort-by-order',
            'glyphicon glyphicon-sort-by-order-alt' => 'sort-by-order-alt',
            'glyphicon glyphicon-sort-by-attributes' => 'sort-by-attributes',
            'glyphicon glyphicon-sort-by-attributes-alt' => 'sort-by-attributes-alt',
            'glyphicon glyphicon-unchecked' => 'unchecked',
            'glyphicon glyphicon-expand' => 'expand',
            'glyphicon glyphicon-collapse-down' => 'collapse-down',
            'glyphicon glyphicon-collapse-up' => 'collapse-up',
            'glyphicon glyphicon-log-in' => 'log-in',
            'glyphicon glyphicon-flash' => 'flash',
            'glyphicon glyphicon-log-out' => 'log-out',
            'glyphicon glyphicon-new-window' => 'new-window',
            'glyphicon glyphicon-record' => 'record',
            'glyphicon glyphicon-save' => 'save',
            'glyphicon glyphicon-open' => 'open',
            'glyphicon glyphicon-saved' => 'saved',
            'glyphicon glyphicon-import' => 'import',
            'glyphicon glyphicon-export' => 'export',
            'glyphicon glyphicon-send' => 'send',
            'glyphicon glyphicon-floppy-disk' => 'floppy-disk',
            'glyphicon glyphicon-floppy-saved' => 'floppy-saved',
            'glyphicon glyphicon-floppy-remove' => 'floppy-remove',
            'glyphicon glyphicon-floppy-save' => 'floppy-save',
            'glyphicon glyphicon-floppy-open' => 'floppy-open',
            'glyphicon glyphicon-credit-card' => 'credit-card',
            'glyphicon glyphicon-transfer' => 'transfer',
            'glyphicon glyphicon-cutlery' => 'cutlery',
            'glyphicon glyphicon-header' => 'header',
            'glyphicon glyphicon-compressed' => 'compressed',
            'glyphicon glyphicon-earphone' => 'earphone',
            'glyphicon glyphicon-phone-alt' => 'phone-alt',
            'glyphicon glyphicon-tower' => 'tower',
            'glyphicon glyphicon-stats' => 'stats',
            'glyphicon glyphicon-sd-video' => 'sd-video',
            'glyphicon glyphicon-hd-video' => 'hd-video',
            'glyphicon glyphicon-subtitles' => 'subtitles',
            'glyphicon glyphicon-sound-stereo' => 'sound-stereo',
            'glyphicon glyphicon-sound-dolby' => 'sound-dolby',
            'glyphicon glyphicon-sound-5-1' => 'sound-5-1',
            'glyphicon glyphicon-sound-6-1' => 'sound-6-1',
            'glyphicon glyphicon-sound-7-1' => 'sound-7-1',
            'glyphicon glyphicon-copyright-mark' => 'copyright-mark',
            'glyphicon glyphicon-registration-mark' => 'registration-mark',
            'glyphicon glyphicon-cloud-download' => 'cloud-download',
            'glyphicon glyphicon-cloud-upload' => 'cloud-upload',
            'glyphicon glyphicon-tree-conifer' => 'tree-conifer',
            'glyphicon glyphicon-tree-deciduous' => 'tree-deciduous',
            'glyphicon glyphicon-cd' => 'cd',
            'glyphicon glyphicon-save-file' => 'save-file',
            'glyphicon glyphicon-open-file' => 'open-file',
            'glyphicon glyphicon-level-up' => 'level-up',
            'glyphicon glyphicon-copy' => 'copy',
            'glyphicon glyphicon-paste' => 'paste',
            'glyphicon glyphicon-alert' => 'alert',
            'glyphicon glyphicon-equalizer' => 'equalizer',
            'glyphicon glyphicon-king' => 'king',
            'glyphicon glyphicon-queen' => 'queen',
            'glyphicon glyphicon-pawn' => 'pawn',
            'glyphicon glyphicon-bishop' => 'bishop',
            'glyphicon glyphicon-knight' => 'knight',
            'glyphicon glyphicon-baby-formula' => 'baby-formula',
            'glyphicon glyphicon-tent' => 'tent',
            'glyphicon glyphicon-blackboard' => 'blackboard',
            'glyphicon glyphicon-bed' => 'bed',
            'glyphicon glyphicon-apple' => 'apple',
            'glyphicon glyphicon-erase' => 'erase',
            'glyphicon glyphicon-hourglass' => 'hourglass',
            'glyphicon glyphicon-lamp' => 'lamp',
            'glyphicon glyphicon-duplicate' => 'duplicate',
            'glyphicon glyphicon-piggy-bank' => 'piggy-bank',
            'glyphicon glyphicon-scissors' => 'scissors',
            'glyphicon glyphicon-bitcoin' => 'bitcoin',
            'glyphicon glyphicon-yen' => 'yen',
            'glyphicon glyphicon-ruble' => 'ruble',
            'glyphicon glyphicon-scale' => 'scale',
            'glyphicon glyphicon-ice-lolly' => 'ice-lolly',
            'glyphicon glyphicon-ice-lolly-tasted' => 'ice-lolly-tasted',
            'glyphicon glyphicon-education' => 'education',
            'glyphicon glyphicon-option-horizontal' => 'option-horizontal',
            'glyphicon glyphicon-option-vertical' => 'option-vertical',
            'glyphicon glyphicon-menu-hamburger' => 'menu-hamburger',
            'glyphicon glyphicon-modal-window' => 'modal-window',
            'glyphicon glyphicon-oil' => 'oil',
            'glyphicon glyphicon-grain' => 'grain',
            'glyphicon glyphicon-sunglasses' => 'sunglasses',
            'glyphicon glyphicon-text-size' => 'text-size',
            'glyphicon glyphicon-text-color' => 'text-color',
            'glyphicon glyphicon-text-background' => 'text-background',
            'glyphicon glyphicon-object-align-top' => 'object-align-top',
            'glyphicon glyphicon-object-align-bottom' => 'object-align-bottom',
            'glyphicon glyphicon-object-align-horizontal' => 'object-align-horizontal',
            'glyphicon glyphicon-object-align-left' => 'object-align-left',
            'glyphicon glyphicon-object-align-vertical' => 'object-align-vertical',
            'glyphicon glyphicon-object-align-right' => 'object-align-right',
            'glyphicon glyphicon-triangle-right' => 'triangle-right',
            'glyphicon glyphicon-triangle-left' => 'triangle-left',
            'glyphicon glyphicon-triangle-bottom' => 'triangle-bottom',
            'glyphicon glyphicon-triangle-top' => 'triangle-top',
            'glyphicon glyphicon-console' => 'console',
            'glyphicon glyphicon-superscript' => 'superscript',
            'glyphicon glyphicon-subscript' => 'subscript',
            'glyphicon glyphicon-menu-left' => 'menu-left',
            'glyphicon glyphicon-menu-right' => 'menu-right',
            'glyphicon glyphicon-menu-down' => 'menu-down',
            'glyphicon glyphicon-menu-up' => 'menu-up',
        ];
    }
}
