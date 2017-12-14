<?php

namespace dal\rightMsg;

include "dal/tool/SqlHelper.php";

use  dal\tool\SqlHelper;


class TbPeopleBase
{
    static $sqlStr = " select * from TbPeople ";

    static function getTbPeopleObjByIdNumber($IdNumber)
    {
        $sql = self::$sqlStr . "  where IdNumber=? ";
        $pars = array($IdNumber);
        return SqlHelper::query($sql, $pars);
    }

    static function getALLTbPeopleObj()
    {
        return SqlHelper::query(self::$sqlStr);
    }

}