;<?php die() ?>
; SVN FILE: $Id$
;/**
; * Test App Ini Based Acl Config File
; *
; *
; * PHP versions 4 and 5
; *
; * CakePHP(tm) :  Rapid Development Framework (http://www.cakephp.org)
; * Copyright 2005-2008, Cake Software Foundation, Inc. (http://www.cakefoundation.org)
; *
; *  Licensed under The MIT License
; *  Redistributions of files must retain the above copyright notice.
; *
; * @filesource
; * @copyright     Copyright 2005-2008, Cake Software Foundation, Inc. (http://www.cakefoundation.org)
; * @link          http://www.cakefoundation.org/projects/info/cakephp CakePHP(tm) Project
; * @package       cake
; * @subpackage    cake.app.config
; * @since         CakePHP(tm) v 0.10.0.1076
; * @version       $Revision$
; * @modifiedby    $LastChangedBy$
; * @lastmodified  $Date$
; * @license       http://www.opensource.org/licenses/mit-license.php The MIT License
; */

;-------------------------------------
;Users
;-------------------------------------

[admin]
groups = administrators
allow =
deny = ads

[paul]
groups = users
allow =
deny =

[jenny]
groups = users
allow = ads
deny = images, files

[nobody]
groups = anonymous
allow =
deny =

;-------------------------------------
;Groups
;-------------------------------------

[administrators]
deny =
allow = posts, comments, images, files, stats, ads

[users]
allow = posts, comments, images, files
deny = stats, ads

[anonymous]
allow =
deny = posts, comments, images, files, stats, ads