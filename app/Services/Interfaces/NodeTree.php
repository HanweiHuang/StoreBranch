<?php
namespace App\Services\Interfaces;

interface NodeTree{

    /**
     * @param $id
     * @return mixed
     * create node
     */
    public function createNode($id);

    /**
     * @param $data
     * @return mixed
     * view one node info only
     */
    public function viewNode($data);

    /**
     * @return mixed
     * view one node and its all children
     */
    public function viewNodeWithChildren();

    /**
     * @return mixed
     * view all nodes of a tree
     */
    public function viewAllNode();

    /**
     * @return mixed
     * will delete node with its all children
     */
    public function delete();

    /**
     * @return mixed
     * update node info
     */
    public function update();

    /**
     * @return mixed
     * move node with its children to another parent
     */
    public function move();

}