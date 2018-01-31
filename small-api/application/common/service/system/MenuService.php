<?php

/*
 * Author: PunkVv <punkv@qq.com>
 */

namespace app\common\service\system;

use app\common\model\system\facade\Menu;
use app\common\util\Tree;
use app\common\VService;

class MenuService extends VService
{
    public function getList()
    {
        $items = Menu::getList();
        // 组合成树结构
        $this->data = Tree::listToTree($items->toArray(), 'id', 'parent_id');

        return $this->result();
    }

    public function createData($param)
    {
        if ($this->validate($param, 'menu', 'create')) {
            $data = Menu::create([
                'name' => $param['name'],
                'menu_name' => $param['menu_name'],
                'parent_id' => empty($param['parent_id']) ? null : $param['parent_id'],
                'parent_name' => $param['parent_name'],
                'router' => $param['router'],
            ]);
            $this->data = $data;
        }

        return $this->result();
    }

    public function updateData($param)
    {
        if ($this->validate($param, 'menu', 'update')) {
            $data = Menu::update([
                'id' => $param['id'],
                'name' => $param['name'],
                'menu_name' => $param['menu_name'],
                'parent_id' => empty($param['parent_id']) ? null : $param['parent_id'],
                'parent_name' => $param['parent_name'],
                'router' => $param['router'],
            ]);
            $this->data = $data;
        }

        return $this->result();
    }

    public function deleteData($id)
    {
        Menu::get($id)->delete();

        return $this->result();
    }

    public function countFiled($param)
    {
        $filed = $param['filed'];
        $val = $param['value'];
        $id = $param['id'];
        $query = Menu::where($filed, $val);
        if ($id) {
            $query->where('id', '<>', $id);
        }
        $this->data['count'] = $query->count();

        return $this->result();
    }

}