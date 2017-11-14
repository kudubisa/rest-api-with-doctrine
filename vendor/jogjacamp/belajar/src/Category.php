<?php
namespace Jogjacamp\Belajar;
use \Jogjacamp\Belajar\Utama;
use Pagerfanta\Adapter\DoctrineDbalAdapter;
use Doctrine\DBAL\Query\QueryBuilder;


Class Category extends Utama
{
    public function retrieve()
    {
        // echo "Hello this is belajar";
        return 1;
    }

    public function retrieve_all()
    {
        $conn = $this->getConnection();

        // $res = $conn->fetch_all("SELECT * FROM news_cat ORDER BY id_cat ASC");

        $qb = $conn->createQueryBuilder();

        $qb->select('*')
            ->from('news_cat')
            ->orderBy('id_cat','asc')
        ;

        $countQueryBuilderModifier = function ($qb) {
            $qb->select('COUNT(DISTINCT id_cat) AS total_results')
               ->setMaxResults(1)
            ;
        };

        $adapter = new DoctrineDbalAdapter($qb, $countQueryBuilderModifier);

        return $adapter;
    }

}
