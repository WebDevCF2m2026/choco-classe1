<?php
// path: model/interface/ManagerInterface.php

namespace model\interface;

use model\MyPDO;

interface ManagerInterface
{
    public function __construct(MyPDO $connect);

}