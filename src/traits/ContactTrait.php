<?php

namespace shophy\tclexiang;

use shophy\tclexiang\helper\Utils;

trait ContactTrait
{
	// ============== 成员管理 ================

	public function createUser($options = [])
    {
    	Utils::checkArrayKeyAndNotEmptyStr($options, 'staff_id');
    	Utils::checkArrayKeyAndNotEmptyStr($options, 'name');
    	Utils::checkArrayKeyAndNotEmptyStr($options, 'phone');

        return $this->post('contact/user/create?access_token='.$this->getAccessToken(), $options);
    }

    public function updateUser($options = [])
    {
    	Utils::checkArrayKeyAndNotEmptyStr($options, 'staff_id');

        return $this->post('contact/user/update?access_token='.$this->getAccessToken(), $options);
    }

    public function resignUser($options = [])
    {
    	Utils::checkArrayKeyAndNotEmptyStr($options, 'staff_id');

        return $this->post('contact/user/resign?access_token='.$this->getAccessToken(), $options);
    }

    public function entryUser($options = [])
    {
    	Utils::checkArrayKeyAndNotEmptyStr($options, 'staff_id');

        return $this->post('contact/user/entry?access_token='.$this->getAccessToken(), $options);
    }

    public function forbiddenUsers($options = [])
    {
    	Utils::checkArrayKeyAndNotEmptyArray($options, 'staffs');

        return $this->post('contact/user/forbidden?access_token='.$this->getAccessToken(), $options);
    }

    public function activeUsers($options = [])
    {
    	Utils::checkArrayKeyAndNotEmptyArray($options, 'staffs');

        return $this->post('contact/user/active?access_token='.$this->getAccessToken(), $options);
    }

	public function addManageUser($options = [])
    {
    	Utils::checkArrayKeyAndNotEmptyStr($options, 'staff_id');

        return $this->post('contact/user/add-manager?access_token='.$this->getAccessToken(), $options);
    }

    public function getUserStatus($options = [])
    {
    	Utils::checkArrayKeyAndNotEmptyStr($options, 'staff_id');

        return $this->get('contact/user/status?access_token='.$this->getAccessToken().'&staff_id='.$options['staff_id']);
    }

    public function getUser($options = [])
    {
    	Utils::checkArrayKeyAndNotEmptyStr($options, 'staff_id');

        return $this->get('contact/user/get?access_token='.$this->getAccessToken().'&staff_id='.$options['staff_id']);
    }

    public function getManageUsers()
    {
        return $this->get('contact/user/managers?access_token='.$this->getAccessToken());
    }

    public function getDepartUsers($options = [])
    {
    	Utils::checkArrayKeyAndUInt($options, 'department_id');

        return $this->get('contact/user/list?access_token='.$this->getAccessToken(), $options);
    }

    public function getManageUsers()
    {
        return $this->get('contact/user/extra-attrs?access_token='.$this->getAccessToken());
    }

    // ============== 成员管理 ================

    // ============== 部门管理 ================

    public function createDepart($options = [])
    {
    	Utils::checkArrayKeyAndNotEmptyStr($options, 'name');
    	Utils::checkArrayKeyAndUInt($options, 'parent_id');

        return $this->post('contact/department/create?access_token='.$this->getAccessToken(), $options);
    }

    public function updateDepart($options = [])
    {
    	Utils::checkArrayKeyAndUInt($options, 'id');

        return $this->post('contact/department/update?access_token='.$this->getAccessToken(), $options);
    }

    public function deleteDepart($options = [])
    {
    	Utils::checkArrayKeyAndUInt($options, 'id');

        return $this->post('contact/department/delete?access_token='.$this->getAccessToken(), $options);
    }

    public function getDepart($options = [])
    {
    	Utils::checkArrayKeyAndUInt($options, 'id');

        return $this->get('contact/department/get?access_token='.$this->getAccessToken(), $options);
    }

    public function getDeparts($options = [])
    {
        return $this->get('contact/department/index?access_token='.$this->getAccessToken(), $options);
    }

    // ============== 部门管理 ================

    // ============== 标签管理 ================

    public function createTag($options = [])
    {
    	Utils::checkArrayKeyAndNotEmptyStr($options, 'name');

        return $this->post('contact/tag/create?access_token='.$this->getAccessToken(), $options);
    }

    public function updateTag($options = [])
    {
    	Utils::checkArrayKeyAndNotEmptyStr($options, 'id');

        return $this->post('contact/tag/update?access_token='.$this->getAccessToken(), $options);
    }

    public function deleteTag($options = [])
    {
    	Utils::checkArrayKeyAndNotEmptyStr($options, 'id');

        return $this->post('contact/tag/delete?access_token='.$this->getAccessToken(), $options);
    }

    public function getTags($options = [])
    {
    	Utils::checkArrayKeyAndUInt($options, 'offset');
    	Utils::checkArrayKeyAndUInt($options, 'limit');

        return $this->get('contact/tag/users?access_token='.$this->getAccessToken(), $options);
    }

    public function getTagUsers($options = [])
    {
    	Utils::checkArrayKeyAndNotEmptyStr($options, 'tag_id');

        return $this->get('contact/tag/users?access_token='.$this->getAccessToken(), $options);
    }

    public function addTagUsers($options = [])
    {
    	Utils::checkArrayKeyAndNotEmptyStr($options, 'id');

        return $this->post('contact/tag/add-users?access_token='.$this->getAccessToken(), $options);
    }

    public function delTagUsers($options = [])
    {
    	Utils::checkArrayKeyAndNotEmptyStr($options, 'id');

        return $this->post('contact/tag/del-users?access_token='.$this->getAccessToken(), $options);
    }

    // ============== 标签管理 ================
}
