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

namespace CentreonConfiguration\Controllers;

use Centreon\Form;
use Centreon\Controllers\FormController;

class ServiceCategoryController extends FormController
{
    protected $objectDisplayName = 'Servicecategory';
    public static $objectName = 'servicecategory';
    public static $enableDisableFieldName = 'sc_activate';
    protected $objectBaseUrl = '/centreon-configuration/servicecategory';
    protected $datatableObject = '\CentreonConfiguration\Internal\ServiceCategoryDatatable';
    protected $objectClass = '\CentreonConfiguration\Models\Servicecategory';
    protected $repository = '\CentreonConfiguration\Repository\ServicecategoryRepository';
    public static $relationMap = array(
        'sc_services' => '\CentreonConfiguration\Models\Relation\Service\Servicecategory',
        'sc_servicetemplates' => '\CentreonConfiguration\Models\Relation\Servicetemplate\Servicecategory'
    );
    
    public static $isDisableable = true;

    /**
     * Update a servicecategory
     *
     *
     * @method post
     * @route /servicecategory/update
     */
    public function updateAction()
    {
        $givenParameters = $this->getParams('post');
        
        if (Form::validateSecurity($givenParameters['token'])) {
            $servicecategory = array(
                'sc_name' => $givenParameters['sc_name'],
                'sc_description' => $givenParameters['sc_description'],
                'sc_activate' => $givenParameters['sc_activate'],
            );
            
            $connObj = new \CentreonConfiguration\Models\Servicecategory();
            try {
                $connObj->update($givenParameters['sc_id'], $servicecategory);
            } catch (Exception $e) {
                echo "fail";
            }
            echo 'success';
        } else {
            echo "fail";
        }
    }
    
    /**
     * Services for a specific category
     *
     * @method get
     * @route /servicecategory/[i:id]/service
     */
    public function serviceForServicecategoryAction()
    {
        parent::getRelations(static::$relationMap['sc_services']);
    }

    /**
     * Service templates for a specific category
     *
     * @method get
     * @route /servicecategory/[i:id]/servicetemplate
     */
    public function servicetemplateForServicecategoryAction()
    {
        parent::getRelations(static::$relationMap['sc_servicetemplates']);
    }
}
