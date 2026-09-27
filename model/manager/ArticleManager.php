<?php
// path: model/manager/ArticleManager.php
// typage strict
declare(strict_types=1);

namespace model\manager;

use model\interface\ManagerInterface;
use model\MyPDO;

class ArticleManager implements ManagerInterface
{

    public function __construct(MyPDO $connect)
    {
    }
}