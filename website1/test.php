<?php
/**
 * Created by PhpStorm.
 * User: liuchongyang
 * Date: 2017/12/13
 * Time: 22:34
 */

include "dal/rightMsg/TbPeopleBase.php";

use  dal\rightMsg\TbPeopleBase;

$IdNumber = "41232619910903575X";

echo json_encode(TbPeopleBase::getTbPeopleObjByIdNumber($IdNumber));

//12233