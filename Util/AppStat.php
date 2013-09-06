<?php

namespace Voltash\FbApplicationBundle\Util;

use Doctrine\ORM\EntityManager;
use Voltash\FbApplicationBundle\Entity\Stat;


class AppStat
{
    private $em;

    function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function addToStat($user, $action, array $info = array())
    {
        $record = $this->createNewRecord();
        $record->setUser($user);
        $record->setAction($action);
        if (count($info) > 0) {
            $record->setInfo(json_encode($info));
        }
        $this->em->persist($record);
        $this->em->flush();

    }

    protected function createNewRecord()
    {
        return new Stat();
    }


}