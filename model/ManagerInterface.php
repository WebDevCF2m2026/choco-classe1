<?php
// path: model/ManagerInterface.php

namespace model;

USE model\MyPDO;

interface ManagerInterface
{
    public function __construct(MyPDO $connect);

}