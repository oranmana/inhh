<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Leave extends Model
{
    protected $table = "commons";
    protected $primaryKey = "id";

    public function parent() {
        return $this->belongsTo('App\Models\Common','par');
    }

    public function getIsLeaveAttribute() {
        return ($this->par == 3689 ? 1 : 0);
    }
    public function getIsOutdoorAttribute() {
        return ($this->par == 9196 ? 1 : 0);
    }
    public function getIsTrainingAttribute() {
        return ($this->par == 9192 ? 1 : 0);
    }
    public function getConsecutiveAttribute() {
        return $this->gl;
    }

    public function getDaysPaidAttribute() {
        return $this->dir;
    }
    public function getDaysLimitAttribute() {
        return $this->sub;
    }
    public function getDaysNoticeAttribute() {
        return ($this->des > "" ? $this->des * 1 : 0);
    }
    public function getReasonedAttribute() {
        $reasons = array(
            3685 => 'ธุระเรื่องอะไร/ที่ใด',
            3686 => 'ป่วยเป็นอะไร/อาการเป็นอย่างไร',
            3687 => 'ปีที่คาดว่าจะคลอด',
            3688 => 'วัดใด/บวชวันที่เท่าใด',
            3691 => 'เลขที่หมายเรียก/วันที่หมาย/สถานที่รายงานตัว',
            3692 => 'ชื่อหลักสูตรอบรม/สถานที่อบรม',
            3693 => 'ชื่อสถานพยาบาล',
            9259 => 'ธุระเรื่องอะไร/ที่ใด',
            9197 => 'ธุระเรื่องอะไร/สถานที่ใด/คาดว่าจะกลับเวลาใด',
            9198 => 'ธุระเรื่องอะไร/ประเทศใด/PP No.',
            9234 => 'PP No.'
        );
        $reason = (in_array($this->id, array(3685,3686,3687,3688,3691,3692,3693,9259,9197,9198,9234) )
            ? $reasons[$this->id] : "");
        return $reason;
    }
    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('par', function (Builder $builder) {
            $leavepars = array(3689,9192,9196);
            $builder->whereIn('par', $leavepars);
        });
    }

}
