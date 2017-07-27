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

namespace wesdk\service\message;

/**
 * Class MsgBase
 * @package wesdk\service\message
 */
class MsgBase
{
    /**
     * MsgBase constructor.
     * @param array $attr
     */
    public function __construct($attr = [])
    {
        foreach ($attr as $k => $v) {
            $this->$k = $v;
        }
    }
}
