<?php
/**
 * Webkul Software.
 *
 * @category  Webkul
 * @package   OrionAlliance_NewModule
 * @author    Webkul
 * @copyright Copyright (c) Webkul Software Private Limited (https://webkul.com)
 * @license   https://store.webkul.com/license.html
 */
namespace OrionAlliance\NewModule\Api\Data;

/**
 * Marketplace Controllers interface.
 * @api
 */
interface ControllersInterface
{
    /**#@+
     * Constants for keys of data array. Identical to the name of the getter in snake case
     */
    public const ENTITY_ID         = 'entity_id';
    /**#@-*/

    public const MODULE_NAME       = 'module_name';

    public const CONTROLLER_PATH   = 'controller_path';
    
    public const LABEL             = 'label';
    
    public const IS_CHILD          = 'is_child';
    
    public const PARENT_ID         = 'parent_id';
    
    public const CREATED_AT        = 'created_at';
    
    public const UPDATED_AT        = 'updated_at';

    /**
     * Get ID
     *
     * @return int|null
     */
    public function getId();

    /**
     * Set ID
     *
     * @param int $id
     * @return \OrionAlliance\NewModule\Api\Data\ControllersInterface
     */
    public function setId($id);

    /**
     * Get Module Name
     *
     * @return int|null
     */
    public function getModuleName();

    /**
     * Set Module Name
     *
     * @param int $modulename
     * @return \OrionAlliance\NewModule\Api\Data\ControllersInterface
     */
    public function setModuleName($modulename);

    /**
     * Get controller path
     *
     * @return int|null
     */
    public function getControllerPath();

    /**
     * Set controller path
     *
     * @param int $controllerPath
     * @return \OrionAlliance\NewModule\Api\Data\ControllersInterface
     */
    public function setControllerPath($controllerPath);

    /**
     * Get Label
     *
     * @return int|null
     */
    public function getLabel();

    /**
     * Set Label
     *
     * @param int $label
     * @return \OrionAlliance\NewModule\Api\Data\ControllersInterface
     */
    public function setLabel($label);

    /**
     * Get Is Child value
     *
     * @return int|null
     */
    public function getIsChild();

    /**
     * Set Is Child value
     *
     * @param int $isChild
     * @return \OrionAlliance\NewModule\Api\Data\ControllersInterface
     */
    public function setIsChild($isChild);

    /**
     * Get Parent Id
     *
     * @return int|null
     */
    public function getParentId();

    /**
     * Set Parent Id
     *
     * @param int $parentId
     * @return \OrionAlliance\NewModule\Api\Data\ControllersInterface
     */
    public function setParentId($parentId);

    /**
     * Get Created Time
     *
     * @return int|null
     */
    public function getCreatedAt();

    /**
     * Set Created Time
     *
     * @param string $createdAt
     * @return \OrionAlliance\NewModule\Api\Data\ControllersInterface
     */
    public function setCreatedAt($createdAt);

    /**
     * Get Updated Time
     *
     * @return int|null
     */
    public function getUpdatedAt();

    /**
     * Set Updated Time
     *
     * @param int $updatedAt
     * @return \OrionAlliance\NewModule\Api\Data\ControllersInterface
     */
    public function setUpdatedAt($updatedAt);
}
