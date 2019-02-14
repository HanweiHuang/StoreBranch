<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Requests\StoreBranchRequest;
use App\Models\StoreBranch as SB;
use phpDocumentor\Reflection\Types\Integer;

class StoreBranchController extends ApiController
{

    /**
     * @param StoreBranchRequest $sb_request
     * @return mixed
     * create a store branch
     */
    public function create(StoreBranchRequest $sb_request){

        $id = $sb_request->id;
        $parent = $sb_request->parent;

        if($this->isExistSB($id)){
            return $this->reponseErrorMessage('SB already exist','40010','404');
        }else if(!$this->isExistSB($parent)){
            return $this->reponseErrorMessage('Can not find parent','40020','404');
        }else{
            $sb = new SB();
            $sb->id = $id;
            $sb->name = 'new_node_'.$id;
            $sb->parent = $parent;
            $sb->save();
        }
        return $this->response->array([
            'message' => 'success',
            'status_code' => '200',
        ])->setStatusCode('200');

    }


    /**
     * @param StoreBranchRequest $sb_request
     * @return mixed
     * view one store branch
     */
    public function view(StoreBranchRequest $sb_request){
        $id = $sb_request->id;
        if(!$this->isExistSB($id)){
            return $this->reponseErrorMessage('Store Branch Does not exist','40030','404');
        }else{
            $sb_obj = SB::where('id',$id)->first();
            return $this->response->array($sb_obj->toArray());
        }
    }

    /**
     * @return mixed
     * get all nodes
     */
    public function viewAll(){
        $all = SB::all();
        return $this->response->array($all->toArray());
    }

    public function viewWithItsChildren(StoreBranchRequest $sb_request){
        $id = $sb_request->id;
        if(!$this->isExistSB($id)){
            return $this->reponseErrorMessage('Store Branch Does not exist','40030','404');
        }else{
            $children = [];
            $this->findAllChildren($id,$children);
            //include self
            $children[] = (Integer)$id;
            return $this->response->array(SB::whereIn('id',$children)->get()->toArray());
        }
    }

    /**
     * @param StoreBranchRequest $sb_request
     * @return mixed
     * update one store branch
     */
    public function update(StoreBranchRequest $sb_request){
        $name = $sb_request->name;
        $id = $sb_request->id;

        if(!$this->isExistSB($id)){
            return $this->reponseErrorMessage('Store Branch Does not exist','40030','404');
        }
        if(!isset($name)){
            return $this->reponseErrorMessage('No data updated','40040','200');
        }else{
            return $this->updateStoreBranch($id, 'name', $name);
        }

    }

    /**
     * @param StoreBranchRequest $sb_request
     * @return mixed
     * move a store branch to a new store branch
     */
    public function move(StoreBranchRequest $sb_request){
        $parent = $sb_request->parent;
        $id = $sb_request->id;

        if(!$this->isExistSB($id)){
            return $this->reponseErrorMessage('Store Branch Does not exist','40030','404');
        }
        if($id == $parent) {
            return $this->reponseErrorMessage('Can not move to itself', '40050', '403');
        }
        //find all current node's children
        $children = [];
        $this->findAllChildren($id,$children);

        //add itself into children
        $children[] = (Integer)$id;
        //find current all nodes
        $all_nodes = SB::pluck('id')->toArray();
        //filter avaliable nodes
        $avaliable = array_diff($all_nodes, $children);

        if(in_array($parent,$avaliable)){
            return $this->updateStoreBranch($id,'parent',$parent);
        }else{
            return $this->reponseErrorMessage('Can not move to its children','40050','403');
        }
    }


    public function delete(StoreBranchRequest $sb_request){
        $id = $sb_request->id;
        if(!$this->isExistSB($id)){
            return $this->reponseErrorMessage('Store Branch Does not exist','40030','404');
        }
        $children = [];
        $this->findAllChildren($id,$children);
        $children[] = (Integer)$id;
        $re = SB::whereIn('id',$children)->delete();
        return $this->response->array([
            'message' => 'success',
            'status_code' => '200',
            'delete_count' => $re,
        ]);
    }

    //help functions
    public function updateStoreBranch($id, $column, $value){
        $sb_obj = SB::where('id',$id)->first();
        $sb_obj -> $column = $value;
        $sb_obj ->save();
        return $this->response->array([
            'message' => 'success',
            'status_code' => '200',
        ]);
    }


    public function isExistSB($id){
        $sb_obj = SB::where('id',$id)->first();
        return isset($sb_obj)? true:false;
    }

    public function reponseErrorMessage($message,$code,$status_code='200'){
        return $this->response->array([
            'message'=>$message,
            'code' => $code,
            'status_code' => $status_code,
        ]);
    }

    public function findAllChildren($id, Array &$ch_stack){
        //root
        if($this->isRoot($id)) {
            $ch_stack = SB::pluck('id')->toArray();
            return $ch_stack = array_diff( $ch_stack, [$id]);
        }

        $children = SB::where('parent', $id)->get();
        if(empty($children->toArray())) {return false;}

        foreach($children as $child){
            //skip root
            $ch_stack[] = $child->id;
            $this->findAllChildren($child->id,$ch_stack);
        }
        return $ch_stack;
    }

    public function isRoot($id){
        $sb_obj = SB::where('id',$id)->first();
        return ($sb_obj->parent == $id) ? true : false;

    }
}
