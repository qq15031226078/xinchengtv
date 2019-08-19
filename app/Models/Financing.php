<?php
namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class Financing extends BaseModel
{
    use SoftDeletes;
    protected $table = 'data_financing';
    protected $dates = ['deleted_at'];


    public function getMaxOrder(){
        return $this->max('order');
    }

    public function getFinancingData($id, $limit = 15)
    {
        $where = isset($id) ? [$this->table . '.id' => $id] : [];
        return $this->where($where)
            ->select('data_financing.*', 'sys_keyword.name_en as keyword_name', 'sys_industry.name_en as industry_name',
                'type_code_degree.name as type_code_degree_name', 'type_code_unit.name as type_code_unit_name', 'xc_news.title as news', 'type_code_phase.name as type_code_phase_name')
            ->leftjoin('sys_keyword', 'data_financing.keyword_code', '=', 'sys_keyword.code')
            ->leftjoin('sys_industry', 'data_financing.industry_code', '=', 'sys_industry.code')
            ->leftjoin('sys_type as type_code_degree', 'data_financing.type_code_degree', '=', 'type_code_degree.code')
            ->leftjoin('sys_type as type_code_unit', 'data_financing.type_code_unit', '=', 'type_code_unit.code')
            ->leftjoin('sys_type as type_code_phase', 'data_financing.type_code_phase', '=', 'type_code_phase.code')
            ->leftjoin('xc_news', 'data_financing.news_id', '=', 'xc_news.id')
            ->orderBy('data_financing.order', 'DESC')
            ->paginate($limit);
    }

    public function updateFinancingData($where, $data)
    {
        return $this->where($where)->update($data);
    }


    public function deleteFinancingData($where)
    {
        return $this->where($where)->delete();
    }

    /* 统计 */
    public function getFinancingCount($where = [])
    {
        return $this->where($where)->count();
    }


    public function getOrderList($code)
    {
        return $this->where('data_financing.keyword_code', $code)
            ->select('data_financing.id','data_financing.amount', 'type_code_degree.name as type_code_degree_name', 'type_code_unit.name as type_code_unit_name', 'type_code_phase.name as type_code_phase_name')
            ->leftjoin('sys_type as type_code_degree', 'data_financing.type_code_degree', '=', 'type_code_degree.code')
            ->leftjoin('sys_type as type_code_unit', 'data_financing.type_code_unit', '=', 'type_code_unit.code')
            ->leftjoin('sys_type as type_code_phase', 'data_financing.type_code_phase', '=', 'type_code_phase.code')
            ->orderBy('data_financing.order', 'DESC')
            ->get();
    }
}
