<?php
/**
 * @copyright Copyright (c) 2021 勾股工作室
 * @license https://opensource.org/licenses/Apache-2.0
 * @link https://blog.gougucms.com
 */

declare (strict_types = 1);

namespace app\admin\validate;

use think\Validate;

class BookCheck extends Validate
{
    protected $rule = [
        'title' => 'require|unique:book',
        'id' => 'require',
    ];

    protected $message = [
        'title.require' => '名称不能为空',
        'title.unique' => '同样的名称已经存在',
        'id.require' => '缺少更新条件',
    ];

    protected $scene = [
        'add' => ['title'],
        'edit' => ['id', 'title'],
    ];
}
