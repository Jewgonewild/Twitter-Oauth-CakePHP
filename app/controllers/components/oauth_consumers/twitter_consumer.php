<?php
/**
 * Twitter Consumer Component
 *
 * PHP 5
 *
 * @category Component
 * @package  EP
 * @version  1.0
 * @author   Emmanuel P <hello@pozo.me>
 * @license  http://www.opensource.org/licenses/mit-license.php The MIT License
 * @link     github
 */
class TwitterConsumer extends AbstractConsumer {
    public function __construct() {
        parent::__construct('yourKey','yourSecret');
    }
}
?>