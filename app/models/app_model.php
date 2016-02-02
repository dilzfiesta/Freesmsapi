<?php
/* SVN FILE: $Id$ */
/**
 * Application model for Cake.
 *
 * This file is application-wide model file. You can put all
 * application-wide model-related methods here.
 *
 * PHP versions 4 and 5
 *
 * CakePHP(tm) :  Rapid Development Framework (http://www.cakephp.org)
 * Copyright 2005-2008, Cake Software Foundation, Inc. (http://www.cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @filesource
 * @copyright     Copyright 2005-2008, Cake Software Foundation, Inc. (http://www.cakefoundation.org)
 * @link          http://www.cakefoundation.org/projects/info/cakephp CakePHP(tm) Project
 * @package       cake
 * @subpackage    cake.cake.libs.model
 * @since         CakePHP(tm) v 0.2.9
 * @version       $Revision$
 * @modifiedby    $LastChangedBy$
 * @lastmodified  $Date$
 * @license       http://www.opensource.org/licenses/mit-license.php The MIT License
 */
/**
 * Application model for Cake.
 *
 * This is a placeholder class.
 * Create the same file in app/app_model.php
 * Add your application-wide methods to the class, your models will inherit them.
 *
 * @package       cake
 * @subpackage    cake.cake.libs.model
 */
//App::import('Lib', 'LazyModel.LazyModel');
//class AppModel extends LazyModel  {

class AppModel extends Model {

	  function unbindAll($params = array())
	  {
	    foreach($this->__associations as $ass)
	    {
	      if(!empty($this->{$ass}))
	      {
	        $this->__backAssociation[$ass] = $this->{$ass};
	        if(isset($params[$ass]))
	        {
	          foreach($this->{$ass} as $model => $detail)
	          {
	            if(!in_array($model,$params[$ass]))
	            {
	              $this->__backAssociation = array_merge($this->__backAssociation, $this->{$ass});
	              unset($this->{$ass}[$model]);
	            }
	          }
	        }else
	        {
	          $this->__backAssociation = array_merge($this->__backAssociation, $this->{$ass});
	          $this->{$ass} = array();
	        }
	
	      }
	    }
	    return true;
	  } 

}

?>