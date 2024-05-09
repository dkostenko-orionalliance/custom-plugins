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
namespace OrionAlliance\NewModule\Api;

/**
 * Controllers CRUD interface.
 */
interface ControllersRepositoryInterface
{
    /**
     * Retrieve controller by id.
     *
     * @api
     * @param string $controllersId
     * @return \OrionAlliance\NewModule\Api\Data\ControllersInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getById($controllersId);

    /**
     * Retrieve all controllers.
     *
     * @api
     * @param int $moduleName
     * @return \OrionAlliance\NewModule\Api\Data\ControllersInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getByModuleName($moduleName);

    /**
     * Retrieve all controllers.
     *
     * @api
     * @param int $controllerPath
     * @return \OrionAlliance\NewModule\Api\Data\ControllersInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getByPath($controllerPath);

    /**
     * Retrieve all controllers.
     *
     * @api
     * @return \OrionAlliance\NewModule\Api\Data\ControllersInterface
     */
    public function getList();
}
