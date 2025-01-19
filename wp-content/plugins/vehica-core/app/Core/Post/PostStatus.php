<?php

namespace Vehica\Core\Post;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Class PostStatus
 * @package Vehica\Core\Post
 */
class PostStatus
{
    const PUBLISH = 'publish';
    const DRAFT = 'draft';
    const PENDING = 'pending';
    const TRASH = 'trash';
    const ANY = 'any';
    const AUTO_DRAFT = 'auto-draft';
}