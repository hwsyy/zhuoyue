<?php
/**
 * @copyright (C)2016-2099 Hnaoyun Inc.
 * @author XingMeng
 * @email hnxsh@foxmail.com
 * @date 2017年12月15日
 *  列表文章模型类
 */
namespace app\admin\model\content;

use core\basic\Model;

class ContentListModel extends Model
{

    protected $scodes = array();

    // 获取文章列表
    public function getList($mcode)
    {
        $field = array(
            'a.id',
           /* 'b.name as sortname',*/
            'a.scode',
            'a.lcode',
           /* 'c.name as subsortname',*/
            'a.subscode',
            'a.title',
            'a.subtitle',
            'a.date',
            'a.sorting',
            'a.status',
            'a.istop',
            'a.isrecommend',
            'a.isheadline',
            'a.visits',
            'a.ico',
            'a.pics',
            'a.filename',
            'a.outlink',
           /* 'd.urlname',*/

           /* 'b.filename as sortfilename'*/
        );
        /*$join = array(
            array(
                'ay_content_sort b',
                'a.scode=b.scode',
                'LEFT'
            ),
            array(
                'ay_content_sort c',
                'a.subscode=c.scode',
                'LEFT'
            ),
            array(
                'ay_model d',
                'b.mcode=d.mcode',
                'LEFT'
            )
        );*/
        return parent::table('ay_content a')->field($field)
            ->where("a.bcode='$mcode'")
            ->where("a.lcode!=''")
            ->where('a.status_recycle=1')
           /* ->where('d.type=2 OR d.type is null ')*/
            ->where("a.acode='" . session('acode') . "'")
           
            ->order('a.sorting ASC,a.id DESC')
            ->page()
            ->select();
    }

    // 获取文章列表
   // 获取文章列表
    public function getList2($mcode,$scode=0)
    {
        $field = array(
            'a.id',
            'a.lcode',
            'b.name as sortname',
            'a.scode',
            'c.name as subsortname',
            'a.subscode',
            'a.title',
            'a.subtitle',
            'a.date',
            'a.sorting',
            'a.status',
            'a.istop',
            'a.isrecommend',
            'a.isheadline',
            'a.visits',
            'a.ico',
            'a.pics',
            'a.filename',
            'a.outlink',
            'd.urlname',

            'b.filename as sortfilename'
        );
        
        $scode_arr = array();
        if ($scode) {
            // 获取所有子类分类编码
            $this->scodes = array(); // 先清空
            $arr = explode(',', $scode); // 传递有多个分类时进行遍历
            foreach ($arr as $value) {
                $scodes = $this->getSubScodes(trim($value));
            }
            // 拼接条件
            $scode_arr = array(
                "a.scode in (" . implode_quot(',', $scodes) . ")",
                "a.subscode='$scode'"
            );
        }
        $join = array(
            array(
                'ay_content_sort b',
                'a.scode=b.scode',
                'LEFT'
            ),
            array(
                'ay_content_sort c',
                'a.subscode=c.scode',
                'LEFT'
            ),
            array(
                'ay_model d',
                'b.mcode=d.mcode',
                'LEFT'
            )
        );
        return parent::table('ay_content a')->field($field)
            ->where($scode_arr, 'OR')
            ->where("b.mcode='$mcode'")
            ->where("a.lcode=''")
            ->where('d.type=2 OR d.type is null ')
            ->where("a.acode='" . session('acode') . "'")
            ->join($join)
            ->order('a.sorting ASC,a.id DESC')
            
            ->select();
    }

    // 获取列表x选项
    public function getListBelong($scode)
    {
        $field = array(
            'a.id',
            'b.name as sortname',
            'a.scode',
            'c.name as subsortname',
            'a.subscode',
            'a.title',
            'a.subtitle',
            'a.date',
            'a.sorting',
            'a.status',
            'a.istop',
            'a.isrecommend',
            'a.isheadline',
            'a.visits',
            'a.ico',
            'a.pics',
            'a.filename',
            'a.outlink',
            'd.urlname',
            
            'b.filename as sortfilename'
        );
        $join = array(
            array(
                'ay_content_sort b',
                'a.scode=b.scode',
                'LEFT'
            ),
            array(
                'ay_content_sort c',
                'a.subscode=c.scode',
                'LEFT'
            ),
            array(
                'ay_model d',
                'b.mcode=d.mcode',
                'LEFT'
            )
        );
        return parent::table('ay_content a')->field($field)
            ->where("a.scode='$scode'")
            ->where('d.type=2 OR d.type is null ')
            ->where("a.acode='" . session('acode') . "'")
            ->join($join)
            ->order('a.sorting ASC,a.id DESC')
            
            ->select();
    }

    // 查找指定分类及子类文章
    public function findContent($mcode, $lcode, $keyword)
    {
       $field = array(
            'a.id',
           /* 'b.name as sortname',*/
            'a.scode',
            'a.lcode',
           /* 'c.name as subsortname',*/
            'a.subscode',
            'a.title',
            'a.subtitle',
            'a.date',
            'a.sorting',
            'a.status',
            'a.istop',
            'a.isrecommend',
            'a.isheadline',
            'a.visits',
            'a.ico',
            'a.pics',
            'a.filename',
            'a.outlink',
           /* 'd.urlname',*/

           /* 'b.filename as sortfilename'*/
        );
        /*$join = array(
            array(
                'ay_content_sort b',
                'a.scode=b.scode',
                'LEFT'
            ),
            array(
                'ay_content_sort c',
                'a.subscode=c.scode',
                'LEFT'
            ),
            array(
                'ay_model d',
                'b.mcode=d.mcode',
                'LEFT'
            )
        );*/
        return parent::table('ay_content a')->field($field)
            ->where("a.bcode='$mcode'")
            ->where('a.status_recycle=1')
            ->where("a.lcode!=''")
           /* ->where('d.type=2 OR d.type is null ')*/
            ->where("a.acode='" . session('acode') . "'")
            ->where("a.lcode='$lcode'")
            ->like('a.title', $keyword)
            ->order('a.sorting ASC,a.id DESC')
            ->page()
            ->select();
    }

    // 在全部栏目查找文章
    public function findContentAll($mcode, $keyword)
    {
       $field = array(
            'a.id',
           /* 'b.name as sortname',*/
            'a.scode',
            'a.lcode',
           /* 'c.name as subsortname',*/
            'a.subscode',
            'a.title',
            'a.subtitle',
            'a.date',
            'a.sorting',
            'a.status',
            'a.istop',
            'a.isrecommend',
            'a.isheadline',
            'a.visits',
            'a.ico',
            'a.pics',
            'a.filename',
            'a.outlink',
           /* 'd.urlname',*/

           /* 'b.filename as sortfilename'*/
        );
        /*$join = array(
            array(
                'ay_content_sort b',
                'a.scode=b.scode',
                'LEFT'
            ),
            array(
                'ay_content_sort c',
                'a.subscode=c.scode',
                'LEFT'
            ),
            array(
                'ay_model d',
                'b.mcode=d.mcode',
                'LEFT'
            )
        );*/
        return parent::table('ay_content a')->field($field)
            ->where("a.bcode='$mcode'")
            ->where('a.status_recycle=1')
            ->where("a.lcode!=''")
           /* ->where('d.type=2 OR d.type is null ')*/
            ->where("a.acode='" . session('acode') . "'")
           
            ->like('a.title', $keyword)
            ->order('a.sorting ASC,a.id DESC')
            ->page()
            ->select();
    }

    // 获取子栏目
    public function getSubScodes($scode)
    {
        if (! $scode) {
            return;
        }
        $this->scodes[] = $scode;
        $subs = parent::table('ay_content_sort')->where("pcode='$scode'")->column('scode');
        if ($subs) {
            foreach ($subs as $value) {
                $this->getSubScodes($value);
            }
        }
        return $this->scodes;
    }

    // 检查文章
    public function checkContent($where)
    {
        return parent::table('ay_content')->field('id')
            ->where($where)
            ->find();
    }

    // 检查文章
    public function getListName($lcode)
    {
        return parent::table('ay_content')
            ->where("id=$lcode")
            ->value('title');
    }

    // 获取文章详情
    public function getContent($id)
    {
        $field = array(
            'a.*',
            'b.name as sortname',
            'c.name as subsortname',
            'd.*'
        );
        $join = array(
            array(
                'ay_content_sort b',
                'a.scode=b.scode',
                'LEFT'
            ),
            array(
                'ay_content_sort c',
                'a.subscode=c.scode',
                'LEFT'
            ),
            array(
                'ay_content_ext d',
                'a.id=d.contentid',
                'LEFT'
            )
        );
        return parent::table('ay_content a')->field($field)
            ->where("a.id=$id")
            ->where("a.acode='" . session('acode') . "'")
            ->join($join)
            ->find();
    }

    // 添加文章
    public function addContent(array $data)
    {
        return parent::table('ay_content')->autoTime()->insertGetId($data);
    }

    // 删除文章
    public function delContent($id)
    {
        return parent::table('ay_content')->where("id=$id")
            ->where("acode='" . session('acode') . "'")
            ->update('status_recycle=0');
    }

    /*// 删除文章
    public function delContentList($ids)
    {
        return parent::table('ay_content')->where("acode='" . session('acode') . "'")->delete($ids);
    }*/
    // 删除文章
    public function delContentList($ids)
    {
        /*var_dump($ids);
        exit;*/
        foreach($ids as $k=>$v){
            parent::table('ay_content')->where("acode='" . session('acode') . "'")->where("id=$v")->update('status_recycle=0');
        }
        return true;
    }

    // 修改文章
    public function modContent($id, $data)
    {
        return parent::table('ay_content')->autoTime()
            ->in('id', $id)
            ->where("acode='" . session('acode') . "'")
            ->update($data);
    }


     //获取栏目scode
    public function getScode($lcode){
        return parent::table('ay_content')->where("id=$lcode")->value('scode');
        
    }

    // 复制内容到指定栏目
    public function copyContent($ids, $lcode,$scode)
    {
        // 查找出要复制的主内容
        $data = parent::table('ay_content')->in('id', $ids)->select(1);
        
        foreach ($data as $key => $value) {
            // 查找扩展内容
            $extdata = parent::table('ay_content_ext')->where('contentid=' . $value['id'])->find(1);
            
            // 去除主键并修改栏目
            unset($value['id']);
            $value['lcode'] = $lcode;
             $value['scode']=$scode;
            
            // 插入主内容
            $id = parent::table('ay_content')->insertGetId($value);
            
            // 插入扩展内容
            if ($id && $extdata) {
                unset($extdata['extid']);
                $extdata['contentid'] = $id;
                $result = parent::table('ay_content_ext')->insert($extdata);
            } else {
                $result = $id;
            }
        }
        return $result;
    }

    // 查找文章扩展内容
    public function findContentExt($id)
    {
        return parent::table('ay_content_ext')->where("contentid=$id")->find();
    }

    // 添加文章扩展内容
    public function addContentExt(array $data)
    {
        return parent::table('ay_content_ext')->insert($data);
    }

    // 修改文章扩展内容
    public function modContentExt($id, $data)
    {
        return parent::table('ay_content_ext')->where("contentid=$id")->update($data);
    }

    // 删除文章扩展内容
    public function delContentExt($id)
    {
        return parent::table('ay_content_ext')->where("contentid=$id")->delete();
    }

    // 删除文章扩展内容
    public function delContentExtList($ids)
    {
        return parent::table('ay_content_ext')->delete($ids, 'contentid');
    }

    // 检查自定义URL名称
    public function checkFilename($filename, $where = array())
    {
        return parent::table('ay_content')->field('id')
            ->where("filename='$filename'")
            ->where($where)
            ->find();
    }
}