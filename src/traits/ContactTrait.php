<?php

namespace shophy\tclexiang\traits;

use shophy\tclexiang\helpers\Utils;

trait ContactTrait
{
	// ============== 成员管理 ================

	public function createUser($options = [])
    {
    	Utils::checkArrayKeyAndNotEmptyStr($options, 'staff_id');
    	Utils::checkArrayKeyAndNotEmptyStr($options, 'name');
    	Utils::checkArrayKeyAndNotEmptyStr($options, 'phone');

        return $this->post('contact/user/create', $options);
    }

    public function updateUser($options = [])
    {
    	Utils::checkArrayKeyAndNotEmptyStr($options, 'staff_id');

        return $this->post('contact/user/update', $options);
    }

    public function resignUser($options = [])
    {
    	Utils::checkArrayKeyAndNotEmptyStr($options, 'staff_id');

        return $this->post('contact/user/resign', $options);
    }

    public function entryUser($options = [])
    {
    	Utils::checkArrayKeyAndNotEmptyStr($options, 'staff_id');

        return $this->post('contact/user/entry', $options);
    }

    public function forbiddenUsers($options = [])
    {
    	Utils::checkArrayKeyAndNotEmptyArray($options, 'staffs');

        return $this->post('contact/user/forbidden', $options);
    }

    public function activeUsers($options = [])
    {
    	Utils::checkArrayKeyAndNotEmptyArray($options, 'staffs');

        return $this->post('contact/user/active', $options);
    }

	public function addManageUser($options = [])
    {
    	Utils::checkArrayKeyAndNotEmptyStr($options, 'staff_id');

        return $this->post('contact/user/add-manager', $options);
    }

    public function getUserStatus($options = [])
    {
    	Utils::checkArrayKeyAndNotEmptyStr($options, 'staff_id');

        return $this->get('contact/user/status', $options);
    }

    public function getUser($options = [])
    {
    	Utils::checkArrayKeyAndNotEmptyStr($options, 'staff_id');

        return $this->get('contact/user/get', $options);
    }

    public function getManageUsers()
    {
        return $this->get('contact/user/managers');
    }

    public function getDepartUsers($options = [])
    {
    	Utils::checkArrayKeyAndUInt($options, 'department_id');

        return $this->get('contact/user/list', $options);
    }

    public function getUserExtraAttrs()
    {
        return $this->get('contact/user/extra-attrs');
    }

    // ============== 成员管理 ================

    // ============== 部门管理 ================

    public function createDepart($options = [])
    {
    	Utils::checkArrayKeyAndNotEmptyStr($options, 'name');
    	Utils::checkArrayKeyAndUInt($options, 'parent_id');

        return $this->post('contact/department/create', $options);
    }

    public function updateDepart($options = [])
    {
    	Utils::checkArrayKeyAndUInt($options, 'id');

        return $this->post('contact/department/update', $options);
    }

    public function deleteDepart($options = [])
    {
    	Utils::checkArrayKeyAndUInt($options, 'id');

        return $this->post('contact/department/delete', $options);
    }

    public function getDepart($options = [])
    {
    	Utils::checkArrayKeyAndUInt($options, 'id');

        return $this->get('contact/department/get', $options);
    }

    public function getDeparts($options = [])
    {
        return $this->get('contact/department/index', $options);
    }

    // ============== 部门管理 ================

    // ============== 标签管理 ================

    public function createTag($options = [])
    {
    	Utils::checkArrayKeyAndNotEmptyStr($options, 'name');

        return $this->post('contact/tag/create', $options);
    }

    public function updateTag($options = [])
    {
    	Utils::checkArrayKeyAndNotEmptyStr($options, 'id');

        return $this->post('contact/tag/update', $options);
    }

    public function deleteTag($options = [])
    {
    	Utils::checkArrayKeyAndNotEmptyStr($options, 'id');

        return $this->post('contact/tag/delete', $options);
    }

    public function getTags($options = [])
    {
    	Utils::checkArrayKeyAndUInt($options, 'offset');
    	Utils::checkArrayKeyAndUInt($options, 'limit');

        return $this->get('contact/tag/users', $options);
    }

    public function getTagUsers($options = [])
    {
    	Utils::checkArrayKeyAndNotEmptyStr($options, 'tag_id');

        return $this->get('contact/tag/users', $options);
    }

    public function addTagUsers($options = [])
    {
    	Utils::checkArrayKeyAndNotEmptyStr($options, 'id');

        return $this->post('contact/tag/add-users', $options);
    }

    public function delTagUsers($options = [])
    {
    	Utils::checkArrayKeyAndNotEmptyStr($options, 'id');

        return $this->post('contact/tag/del-users', $options);
    }

    // ============== 标签管理 ================
}
