<?php
namespace App\Services\Implementations;
use App\Services\Interfaces\NodeTree;
use App\Models\StoreBranch as SB;

class StoreBranchTree implements NodeTree{

    /**
     * @param $id
     * @return mixed
     * create node
     */
    public function createNode($data){
        $sb = new SB();
        $sb->id = $data['id'];
        $sb->name = $data['name']?:'new_node_'.$data['id'];
        $sb->parent = $data['parent'];
        $sb->save();
    }

    /**
     * @param $id
     * @return mixed
     * view one node info only
     */
    public function viewNode($id){
        $sb_obj = SB::where('id',$id)->first();
        return $sb_obj;
    }

    /**
     * @return mixed
     * view one node and its all children
     */
    public function viewNodeWithChildren(){

    }

    /**
     * @return mixed
     * view all nodes of a tree
     */
    public function viewAllNode(){

    }

    /**
     * @return mixed
     * will delete node with its all children
     */
    public function delete(){

    }

    /**
     * @return mixed
     * update node info
     */
    public function update(){

    }

    /**
     * @return mixed
     * move node with its children to another parent
     */
    public function move(){

    }
}