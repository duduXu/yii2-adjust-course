<?php
namespace common\widgets;

use common\models\Course;
use yii\base\Widget;

/**
 * 课表小部件
 *
 * @property Course[] $courses
 * @property array $tbody
 */
class CoursesWidget extends Widget
{
    public $courses;

    protected $tbody;

    /**
     * 数据初始化
     */
    public function init()
    {
        parent::init();
        // 构建12*7空表格
        for ($i=1;$i<=12;$i++) {
            for ($j=0;$j<=7;$j++) {
                if ($j==0) {
                    $this->tbody[$i][$j] = '<td style="vertical-align: middle;text-align: center;">'.$i.'</td>';
                } else {
                    $this->tbody[$i][$j] = '<td></td>';
                }
            }
        }
    }

    /**
     * 逻辑处理
     * @return string 视图代码
     */
    public function run()
    {
        $thead = <<<EOT
        <div class="table-responsive">
        <table class="table table-bordered">
            <thead>
                <colgroup span="1"><col/></colgroup>
                <colgroup span="7">
                    <col width="14%" />
                    <col width="14%" />
                    <col width="14%" />
                    <col width="14%" />
                    <col width="14%" />
                    <col width="14%" />
                    <col width="14%" />
                </colgroup>
                
                <tr class="text-center">
                    <th class="text-center">#</th>
                    <th class="text-center">周一</th>
                    <th class="text-center">周二</th>
                    <th class="text-center">周三</th>
                    <th class="text-center">周四</th>
                    <th class="text-center">周五</th>
                    <th class="text-center">周六</th>
                    <th class="text-center">周日</th>
                </tr>
            </thead>
EOT;

        foreach ($this->courses as $course) {
            // 格式化sec属性
            $course->sec = $this->filterSec($course->sec);
            foreach ($course->sec as $sec => $item) {
                foreach ($item as $key=>$value) {
                    // 填充课程对应单元格的内容
                    if ($key == 0) {
                        $teacher = $course->user->getAttribute('nickname');
                        $classroom = $course->classroom->getAttribute('name');
                        $this->tbody[$value][$course->day] = '<td style="vertical-align:middle" class="info" rowspan="'.count($item).'">'.$course->name.$classroom.$teacher.'</td>';
                    } else {
                        $this->tbody[$value][$course->day] = null;
                    }
                }
            }
        }

        foreach ($this->tbody as $k => $td) {
            $this->tbody[$k] = implode('', $td);
        }

        $tbody = '<tbody><tr>'.implode('</tr><tr>', $this->tbody).'</tr></tbody>';
        $table = $thead.$tbody.'</table></div>';
        return $table;
    }

    /**
     * 处理sec属性,连续的节数为一个数组且对应的键为起始节
     * @param $sec
     * @return array
     */
    private function filterSec($sec)
    {
        $sections = explode(',', $sec);
        $result = array();
        $i = $j = 0;
        foreach ($sections as $section) {
            if ($section == ++$j) {
                $result[$i][] = $section;
            } else {
                $result[$section][] = $section;
                $i = $j = $section;
            }
        }
        return $result;
    }
}
