<?php

namespace shophy\tclexiang\traits;

trait ContactTrait
{
	// ============== 成员管理 ================

	public function createUser($options = [])
    {
    	helpers\Utils::checkArrayKeyAndNotEmptyStr($options, 'staff_id');
    	helpers\Utils::checkArrayKeyAndNotEmptyStr($options, 'name');
    	helpers\Utils::checkArrayKeyAndNotEmptyStr($options, 'phone');

        return $this->post('contact/user/create?access_token='.$this->getAccessToken(), $options);
    }

    public function updateUser($options = [])
    {
    	helpers\Utils::checkArrayKeyAndNotEmptyStr($options, 'staff_id');

        return $this->post('contact/user/update?access_token='.$this->getAccessToken(), $options);
    }

    public function resignUser($options = [])
    {
    	helpers\Utils::checkArrayKeyAndNotEmptyStr($options, 'staff_id');

        return $this->post('contact/user/resign?access_token='.$this->getAccessToken(), $options);
    }

    public function entryUser($options = [])
    {
    	helpers\Utils::checkArrayKeyAndNotEmptyStr($options, 'staff_id');

        return $this->post('contact/user/entry?access_token='.$this->getAccessToken(), $options);
    }

    public function forbiddenUsers($options = [])
    {
    	helpers\Utils::checkArrayKeyAndNotEmptyArray($options, 'staffs');

        return $this->post('contact/user/forbidden?access_token='.$this->getAccessToken(), $options);
    }

    public function activeUsers($options = [])
    {
    	helpers\Utils::checkArrayKeyAndNotEmptyArray($options, 'staffs');

        return $this->post('contact/user/active?access_token='.$this->getAccessToken(), $options);
    }

	public function addManageUser($options = [])
    {
    	helpers\Utils::checkArrayKeyAndNotEmptyStr($options, 'staff_id');

        return $this->post('contact/user/add-manager?access_token='.$this->getAccessToken(), $options);
    }

    public function getUserStatus($options = [])
    {
    	helpers\Utils::checkArrayKeyAndNotEmptyStr($options, 'staff_id');

        return $this->get('contact/user/status?access_token='.$this->getAccessToken().'&staff_id='.$options['staff_id']);
    }

    public function getUser($options = [])
    {
    	helpers\Utils::checkArrayKeyAndNotEmptyStr($options, 'staff_id');

        return $this->get('contact/user/get?access_token='.$this->getAccessToken().'&staff_id='.$options['staff_id']);
    }

    public function getManageUsers()
    {
        return $this->get('contact/user/managers?access_token='.$this->getAccessToken());
    }

    public function getDepartUsers($options = [])
    {
    	helpers\Utils::checkArrayKeyAndUInt($options, 'department_id');

        return $this->get('contact/user/list?access_token='.$this->getAccessToken(), $options);
    }

    public function getUserExtraAttrs()
    {
        return $this->get('contact/user/extra-attrs?access_token='.$this->getAccessToken());
    }

    // ============== 成员管理 ================

    // ============== 部门管理 ================

    public function createDepart($options = [])
    {
    	helpers\Utils::checkArrayKeyAndNotEmptyStr($options, 'name');
    	helpers\Utils::checkArrayKeyAndUInt($options, 'parent_id');

        return $this->post('contact/department/create?access_token='.$this->getAccessToken(), $options);
    }

    public function updateDepart($options = [])
    {
    	helpers\Utils::checkArrayKeyAndUInt($options, 'id');

        return $this->post('contact/department/update?access_token='.$this->getAccessToken(), $options);
    }

    public function deleteDepart($options = [])
    {
    	helpers\Utils::checkArrayKeyAndUInt($options, 'id');

        return $this->post('contact/department/delete?access_token='.$this->getAccessToken(), $options);
    }

    public function getDepart($options = [])
    {
    	helpers\Utils::checkArrayKeyAndUInt($options, 'id');

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
    	helpers\Utils::checkArrayKeyAndNotEmptyStr($options, 'name');

        return $this->post('contact/tag/create?access_token='.$this->getAccessToken(), $options);
    }

    public function updateTag($options = [])
    {
    	helpers\Utils::checkArrayKeyAndNotEmptyStr($options, 'id');

        return $this->post('contact/tag/update?access_token='.$this->getAccessToken(), $options);
    }

    public function deleteTag($options = [])
    {
    	helpers\Utils::checkArrayKeyAndNotEmptyStr($options, 'id');

        return $this->post('contact/tag/delete?access_token='.$this->getAccessToken(), $options);
    }

    public function getTags($options = [])
    {
    	helpers\Utils::checkArrayKeyAndUInt($options, 'offset');
    	helpers\Utils::checkArrayKeyAndUInt($options, 'limit');

        return $this->get('contact/tag/users?access_token='.$this->getAccessToken(), $options);
    }

    public function getTagUsers($options = [])
    {
    	helpers\Utils::checkArrayKeyAndNotEmptyStr($options, 'tag_id');

        return $this->get('contact/tag/users?access_token='.$this->getAccessToken(), $options);
    }

    public function addTagUsers($options = [])
    {
    	helpers\Utils::checkArrayKeyAndNotEmptyStr($options, 'id');

        return $this->post('contact/tag/add-users?access_token='.$this->getAccessToken(), $options);
    }

    public function delTagUsers($options = [])
    {
    	helpers\Utils::checkArrayKeyAndNotEmptyStr($options, 'id');

        return $this->post('contact/tag/del-users?access_token='.$this->getAccessToken(), $options);
    }

    // ============== 标签管理 ================
}
