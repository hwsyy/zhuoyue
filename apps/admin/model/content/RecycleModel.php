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

class RecycleModel extends Model
{

    protected $scodes = array();

    // 获取文章列表
    public function getList($mcode)
    {
        $field = array(
            'a.id',
            'a.desc',
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

            'b.filename as sortfilename',
            'e.*'
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
            ),
            array(
                'ay_content_ext e',
                'a.id=e.contentid',
                'LEFT'
            )
        );
        return parent::table('ay_content a')->field($field)
            ->where("b.mcode='$mcode'")
            ->where("a.lcode=''")
            ->where("a.status_recycle=1")
            ->where('d.type=2 OR d.type is null ')
            ->where("a.acode='" . session('acode') . "'")
            ->join($join)
            ->order('a.sorting ASC,a.id DESC')
            ->page()
            ->select();
    }

    public function getRecycle(){
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
            
            ->where('a.status_recycle=0')
            ->where('d.type=2 OR d.type is null ')
            ->where("a.acode='" . session('acode') . "'")
            ->join($join)
            ->order('a.sorting ASC,a.id DESC')
            ->page()
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
    public function findContent($mcode, $scode, $keyword)
    {
        $fields = array(
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
        $this->scodes = array(); // 先清空
        $scodes = $this->getSubScodes($scode);
        return parent::table('ay_content a')->field($fields)
            ->where("a.status_recycle=1")
            ->where("a.lcode=''")
            ->where("b.mcode='$mcode'")
            ->where('d.type=2 OR d.type is null ')
            ->where("a.acode='" . session('acode') . "'")
            ->in('a.scode', $scodes)
            ->like('a.title', $keyword)
            ->join($join)
            ->order('a.sorting ASC,a.id DESC')
            ->page()
            ->select();
    }

    // 在全部栏目查找文章
    public function findContentAll($mcode, $keyword)
    {
        $fields = array(
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
        return parent::table('ay_content a')->field($fields)
            ->where("a.status_recycle=1")
            ->where("a.lcode=''")
            ->where("b.mcode='$mcode'")
            ->where('d.type=2 OR d.type is null ')
            ->where("a.acode='" . session('acode') . "'")
            ->like('a.title', $keyword)
            ->join($join)
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
    
    
    // 清空回收站
    public function clearRecycle()
    {
        return parent::table('ay_content')->where("status_recycle=0")->delete();
    }
    
    
    //一键还原回收站
    public function restoreRecycle()
    {
        return parent::table('ay_content')->where("status_recycle=0")->update('status_recycle=1');
    }
    

    // 添加文章
    public function addContent(array $data)
    {
        return parent::table('ay_content')->autoTime()->insertGetId($data);
    }

    // 把文章放入回收站
    public function delContent($id)
    {
        return parent::table('ay_content')->where("id=$id")
            ->where("acode='" . session('acode') . "'")
            ->delete();
    }

    // 把文章还原
    public function recycleContent($id)
    {
        return parent::table('ay_content')->where("id=$id")
            ->where("acode='" . session('acode') . "'")
            ->update('status_recycle=1');
    }



    // 删除文章
   

    // 删除文章
    public function delContentList($ids)
    {
        /*var_dump($ids);
        exit;*/
        foreach($ids as $k=>$v){
            parent::table('ay_content')->where("acode='" . session('acode') . "'")->where("id=$v")->delete();
        }
        return true;
    }
    
    //批量环境
    public function recycleContentList($ids)
    {
        /*var_dump($ids);
        exit;*/
        foreach($ids as $k=>$v){
            parent::table('ay_content')->where("acode='" . session('acode') . "'")->where("id=$v")->update('status_recycle=1');
        }
        return true;
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