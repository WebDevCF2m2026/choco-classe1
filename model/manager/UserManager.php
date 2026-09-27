<?php
// path: model/manager/UserManager.php
// typage strict
declare(strict_types=1);

namespace model\manager;

use model\interface\ManagerInterface;
use model\MyPDO;

class UserManager implements ManagerInterface
{

    public function __construct(MyPDO $connect)
    {
    }
}