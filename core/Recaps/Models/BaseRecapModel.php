<?php

namespace Core\Recaps\Models;

use CodeIgniter\Database\RawSql;
use Core\Voters\Models\BaseVotersModel;

class BaseRecapModel extends BaseVotersModel
{
    public const QUERY_TOTAL = 'count(id) as total';

    protected $returnType = 'array';

    public function getTotalPerTps()
    {
        return $this->select([
                    new RawSql(self::QUERY_TOTAL),
                    'gender',
                    'tps'
                ])
                ->where('m_villages_id', $this->villageId)
                ->groupBy(['tps', 'gender'])
                ->get()
                ->getResult('array');
    }

    public function getTotalNewVoter()
    {
        return $this->select([
                    new RawSql(self::QUERY_TOTAL),
                    'gender',
                    'tps'
                ])
                ->where('m_villages_id', $this->villageId)
                ->where('is_new_voter', 1)
                ->groupBy(['tps', 'gender'])
                ->get()
                ->getResult('array');
    }

    public function getTotalNoviceVoter()
    {
        return $this->select([
                    new RawSql(self::QUERY_TOTAL),
                    'gender',
                    'tps'
                ])
                ->where('m_villages_id', $this->villageId)
                ->where('is_novice_voter', 1)
                ->groupBy(['tps', 'gender'])
                ->get()
                ->getResult('array');
    }

    public function getTotalKtpEl()
    {
        return $this->select([
                    new RawSql(self::QUERY_TOTAL),
                    'gender',
                    'tps'
                ])
                ->where('m_villages_id', $this->villageId)
                ->where('is_ktp_el', 0)
                ->groupBy(['tps', 'gender'])
                ->get()
                ->getResult('array');
    }

    public function getTotalProfileUpdated()
    {
        return $this->select([
                    new RawSql(self::QUERY_TOTAL),
                    'gender',
                    'tps'
                ])
                ->where('m_villages_id', $this->villageId)
                ->where('is_profile_updated', 1)
                ->groupBy(['tps', 'gender'])
                ->get()
                ->getResult('array');
    }

    public function getTotalDisabilities()
    {
        return $this->select([
                    new RawSql(self::QUERY_TOTAL),
                    'gender',
                    'tps'
                ])
                ->where('m_villages_id', $this->villageId)
                ->where('disabilities !=', 0)
                ->groupBy(['tps', 'gender'])
                ->get()
                ->getResult('array');
    }

    public function getTotalTms()
    {
        return $this->select([
                    new RawSql(self::QUERY_TOTAL),
                    'gender',
                    'tps',
                    'tms'
                ])
                ->where('m_villages_id', $this->villageId)
                ->groupBy(['tps', 'tms', 'gender'])
                ->get()
                ->getResult('array');
    }
}
