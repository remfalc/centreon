<?php
/*
 * Copyright 2005-2014 MERETHIS
 * Centreon is developped by : Julien Mathis and Romain Le Merlus under
 * GPL Licence 2.0.
 *
 * This program is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License as published by the Free Software
 * Foundation ; either version 2 of the License.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT ANY
 * WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A
 * PARTICULAR PURPOSE. See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with
 * this program; if not, see <http://www.gnu.org/licenses>.
 *
 * Linking this program statically or dynamically with other modules is making a
 * combined work based on this program. Thus, the terms and conditions of the GNU
 * General Public License cover the whole combination.
 *
 * As a special exception, the copyright holders of this program give MERETHIS
 * permission to link this program with independent modules to produce an executable,
 * regardless of the license terms of these independent modules, and to copy and
 * distribute the resulting executable under terms of MERETHIS choice, provided that
 * MERETHIS also meet, for each linked independent module, the terms  and conditions
 * of the license of that module. An independent module is a module which is not
 * derived from this program. If you modify this program, you may extend this
 * exception to your version of the program, but you are not obliged to do so. If you
 * do not wish to do so, delete this exception statement from your version.
 *
 * For more information : contact@centreon.com
 *
 */

namespace CentreonConfiguration\Events;

class EngineProcess
{
    /**
     * Refers to the poller id
     * @var int
     */
    private $pollerId;

    /**
     * Refers to the action to perform: restart, reload, forcereload
     * @var string
     */
    private $action;

    /**
     * Array of output - should be the output of the process after 
     * performing the action
     * @var array 
     */
    private $output;

    /**
     * @param int $pollerId
     * @param string $action
     */
    public function __construct($pollerId, $action)
    {
        $this->pollerId = $pollerId;
        $this->action = $action;
        $this->output = array();
        $this->status = 0;
    }

    /**
     * @return int
     */
    public function getPollerId()
    {
        return $this->pollerId;
    }

    /**
     * @return string
     */
    public function getAction()
    {
        return $this->action();
    }

    /**
     * @return array
     */
    public function getOutput()
    {
        return $this->output;
    }

    /**
     * @return int
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param array $output
     */
    public function setOutput(array $output)
    {
        $this->output = $output;
    }

    public function setStatus(int $status)
    {
        $this->status = $status;
    }
}