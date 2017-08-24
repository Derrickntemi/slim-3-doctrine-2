<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Role
 *
 * @ORM\Table(name="role")
 * @ORM\Entity
 */
class Role
{
    /**
     * @var integer
     *
     * @ORM\Column(name="role_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $roleId;

    /**
     * @var string
     *
     * @ORM\Column(name="role_name", type="string", length=100, nullable=false)
     */
    private $roleName;

    /**
     * @var string
     *
     * @ORM\Column(name="role_description", type="text", length=16777215, nullable=false)
     */
    private $roleDescription;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="role_created_date", type="datetime", nullable=false)
     */
    private $roleCreatedDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="role_updated_date", type="datetime", nullable=false)
     */
    private $roleUpdatedDate;

    /**
     * @var boolean
     *
     * @ORM\Column(name="status", type="boolean", nullable=false)
     */
    private $status;

    public function getArrayCopy()
    {
        return get_object_vars($this);
    }

    /**
     * Get roleId
     *
     * @return integer
     */
    public function getRoleId()
    {
        return $this->roleId;
    }

    /**
     * Set roleName
     *
     * @param string $roleName
     *
     * @return Role
     */
    public function setRoleName($roleName)
    {
        $this->roleName = $roleName;

        return $this;
    }

    /**
     * Get roleName
     *
     * @return string
     */
    public function getRoleName()
    {
        return $this->roleName;
    }

    /**
     * Set roleDescription
     *
     * @param string $roleDescription
     *
     * @return Role
     */
    public function setRoleDescription($roleDescription)
    {
        $this->roleDescription = $roleDescription;

        return $this;
    }

    /**
     * Get roleDescription
     *
     * @return string
     */
    public function getRoleDescription()
    {
        return $this->roleDescription;
    }

    /**
     * Set roleCreatedDate
     *
     * @param \DateTime $roleCreatedDate
     *
     * @return Role
     */
    public function setRoleCreatedDate()
    {
        $this->roleCreatedDate = new \DateTime(date('Y-m-d H:i:s'), new \DateTimeZone('Africa/Nairobi'));

        return $this;
    }

    /**
     * Get roleCreatedDate
     *
     * @return \DateTime
     */
    public function getRoleCreatedDate()
    {
        return $this->roleCreatedDate;
    }

    /**
     * Set roleUpdatedDate
     *
     * @param \DateTime $roleUpdatedDate
     *
     * @return Role
     */
    public function setRoleUpdatedDate()
    {
        $this->roleUpdatedDate = new \DateTime(date('Y-m-d H:i:s'), new \DateTimeZone('Africa/Nairobi'));

        return $this;
    }

    /**
     * Get roleUpdatedDate
     *
     * @return \DateTime
     */
    public function getRoleUpdatedDate()
    {
        return $this->roleUpdatedDate;
    }

    /**
     * Set status
     *
     * @param boolean $status
     *
     * @return Role
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return boolean
     */
    public function getStatus()
    {
        return $this->status;
    }
}
