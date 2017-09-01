<?php
/*
* This file is part of EC-CUBE
*
* Copyright(c) 2000-2015 LOCKON CO.,LTD. All Rights Reserved.
* http://www.lockon.co.jp/
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace Plugin\SSMakeLP\Entity;

class SSMakeLP extends \Eccube\Entity\AbstractEntity
{

    /**
     * @var integer
     */
    private $lp_id;
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $path;
	/**
     * @var string
     */
    private $product_id;

    /**
     * @var integer
     */
    private $rank;

    /**
     * @var integer
     */
    private $del_flg;

    /**
     * @var \DateTime
     */
    private $create_date;

    /**
     * @var \DateTime
     */
    private $update_date;

    /**
     * Constructor
     */
    public function __construct()
    {
    }

    /**
     * Set maker id
     *
     * @param  string $lp_id
     * @return Maker
     */
/*    public function setLpId($lp_id)
    {
        $this->lp_id = $lp_id;

        return $this;
    }

    public function getLpId()
    {
        return $this->lp_id;
    }*/
    public function setId($id)
    {
        $this->lp_id = $id;

        return $this;
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->lp_id;
    }
	
    public function setlpId($id)
    {
        $this->lp_id = $id;

        return $this;
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getlpId()
    {
        return $this->lp_id;
    }
	
	
    /**
     * Get name
     *
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Set name
     *
     * @param  string $name
     * @return Maker
     */
    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }
    public function getProductid()
    {
        return $this->product_id;
    }

    /**
     * Set name
     *
     * @param  string $name
     * @return Maker
     */
    public function setProductid($product_id)
    {
        $this->product_id = $product_id;

        return $this;
    }

    /**
     * Get rank
     *
     * @return integer
     */
    public function getRank()
    {
        return $this->rank;
    }

    /**
     * Set rank
     *
     * @param  integer $rank
     * @return Maker
     */
    public function setRank($rank)
    {
        $this->rank = $rank;

        return $this;
    }

    /**
     * Set del_flg
     *
     * @param  integer $delFlg
     * @return Payment
     */
    public function setDelFlg($delFlg)
    {
        $this->del_flg = $delFlg;

        return $this;
    }

    /**
     * Get del_flg
     *
     * @return integer
     */
    public function getDelFlg()
    {
        return $this->del_flg;
    }

    /**
     * Set create_date
     *
     * @param  \DateTime $createDate
     * @return Payment
     */
    public function setCreateDate($createDate)
    {
        $this->create_date = $createDate;

        return $this;
    }

    /**
     * Get create_date
     *
     * @return \DateTime
     */
    public function getCreateDate()
    {
        return $this->create_date;
    }

    /**
     * Set update_date
     *
     * @param  \DateTime $updateDate
     * @return Payment
     */
    public function setUpdateDate($updateDate)
    {
        $this->update_date = $updateDate;

        return $this;
    }

    /**
     * Get update_date
     *
     * @return \DateTime
     */
    public function getUpdateDate()
    {
        return $this->update_date;
    }

}
