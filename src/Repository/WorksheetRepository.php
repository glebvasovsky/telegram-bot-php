<?php

namespace App\Repository;

use App\Entity\Field;
use App\Entity\Worksheet;
use Doctrine\ORM\EntityRepository;

class WorksheetRepository extends EntityRepository
{
    /**
     * @param Worksheet $worksheet
     * 
     * @return Field
     */
    public function findCurrentField(Worksheet $worksheet): Field
    {
        $fields = $this->createQueryBuilder()
                ->select('f')
                ->from(Field::class, 'f')
                ->where("f.worksheet = $worksheet AND f.value is null")
                ->orderBy('f.id')
                ->getQuery()
                ->getResult();
        
        dump($fields);
        die('grg');
        
        return $fields[0];
    }
    
}
