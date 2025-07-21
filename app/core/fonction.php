<?php
function base_url($path = '') {
    return BASE_URL . '/' . ltrim($path, '/');
}
