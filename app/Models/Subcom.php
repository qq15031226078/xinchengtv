<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
class Subcom extends BaseModel
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];
	protected $table = 'xc_subcom';

	 public function updateSubcom($where,$data){
	     return $this->where($where)->update($data);
     }

     public function getSubcomOne($where=[],$field=['*']){
	     return $this->where($where)->select($field)->first();
     }

     /* 统计 */
	public function getSubcomCount($where=[]){
		return $this->where($where)->count();
	}
}