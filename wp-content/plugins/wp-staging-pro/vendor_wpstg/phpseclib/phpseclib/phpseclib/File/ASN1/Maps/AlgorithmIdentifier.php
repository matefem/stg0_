<?php

/**
 * AlgorithmIdentifier
 *
 * PHP version 5
 *
 * @author    Jim Wigginton <terrafrost@php.net>
 * @copyright 2016 Jim Wigginton
 * @license   http://www.opensource.org/licenses/mit-license.html  MIT License
 * @link      http://phpseclib.sourceforge.net
 */
namespace WPStaging\Vendor\phpseclib3\File\ASN1\Maps;

use WPStaging\Vendor\phpseclib3\File\ASN1;
/**
 * AlgorithmIdentifier
 *
 * @author  Jim Wigginton <terrafrost@php.net>
 */
abstract class AlgorithmIdentifier
{
    const MAP = ['type' => \WPStaging\Vendor\phpseclib3\File\ASN1::TYPE_SEQUENCE, 'children' => ['algorithm' => ['type' => \WPStaging\Vendor\phpseclib3\File\ASN1::TYPE_OBJECT_IDENTIFIER], 'parameters' => ['type' => \WPStaging\Vendor\phpseclib3\File\ASN1::TYPE_ANY, 'optional' => \true]]];
}