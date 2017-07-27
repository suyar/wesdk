<?php
/**
 *                          ____
 *  _      _____  _________/ / /__
 * | | /| / / _ \/ ___/ __  / //_/
 * | |/ |/ /  __(__  ) /_/ / ,<
 * |__/|__/\___/____/\__,_/_/|_|
 * @author carolkey <carolkey@wesdk.org>
 * @link https://github.com/carolkey/wesdk
 * @copyright 2017 wesdk
 * @license MIT
 */

namespace wesdk\service;

/**
 * Class Mbuilder
 * @package wesdk\service
 */
class Mbuilder
{
    /**
     * @param array $attr
     * @return message\Article
     */
    public function article($attr = [])
    {
        return new message\Article($attr);
    }

    /**
     * @param array $attr
     * @return message\Card
     */
    public function card($attr = [])
    {
        return new message\Card($attr);
    }

    /**
     * @param array $attr
     * @return message\Image
     */
    public function image($attr = [])
    {
        return new message\Image($attr);
    }

    /**
     * @param array $attr
     * @return message\Matchrule
     */
    public function matchrule($attr = [])
    {
        return new message\Matchrule($attr);
    }

    /**
     * @param array $attr
     * @return message\Menu
     */
    public function menu($attr = [])
    {
        return new message\Menu($attr);
    }

    /**
     * @param array $attr
     * @return message\Mpnews
     */
    public function mpnews($attr = [])
    {
        return new message\Mpnews($attr);
    }

    /**
     * @param array $attr
     * @return message\Music
     */
    public function music($attr = [])
    {
        return new message\Music($attr);
    }

    /**
     * @param array $attr
     * @return message\News
     */
    public function news($attr = [])
    {
        return new message\News($attr);
    }

    /**
     * @param array $attr
     * @return message\Paybiz
     */
    public function paybiz($attr = [])
    {
        return new message\Paybiz($attr);
    }

    /**
     * @param array $attr
     * @return message\Payorder
     */
    public function payorder($attr = [])
    {
        return new message\Payorder($attr);
    }

    /**
     * @param array $attr
     * @return message\Payred
     */
    public function payred($attr = [])
    {
        return new message\Payred($attr);
    }

    /**
     * @param array $attr
     * @return message\Payrefund
     */
    public function payrefund($attr = [])
    {
        return new message\Payrefund($attr);
    }

    /**
     * @param array $attr
     * @return message\Poi
     */
    public function poi($attr = [])
    {
        return new message\Poi($attr);
    }

    /**
     * @param array $attr
     * @return message\Raw
     */
    public function raw($attr = [])
    {
        return new message\Raw($attr);
    }

    /**
     * @param array $attr
     * @return message\Text
     */
    public function text($attr = [])
    {
        return new message\Text($attr);
    }

    /**
     * @param array $attr
     * @return message\Tpl
     */
    public function tpl($attr = [])
    {
        return new message\Tpl($attr);
    }

    /**
     * @param array $attr
     * @return message\Transfer
     */
    public function transfer($attr = [])
    {
        return new message\Transfer($attr);
    }

    /**
     * @param array $attr
     * @return message\Video
     */
    public function video($attr = [])
    {
        return new message\Video($attr);
    }

    /**
     * @param array $attr
     * @return message\Voice
     */
    public function voice($attr = [])
    {
        return new message\Voice($attr);
    }
}
