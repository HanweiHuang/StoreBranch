<?php

use Illuminate\Database\Seeder;
use App\Models\StoreBranch as SB;

class StoreBranchSeeder extends Seeder
{
    private $parents_stack = [];

    protected $toTruncate = ['store_branches'];
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        SB::truncate();

        factory(SB::class, 20)->create();

        $bs = SB::query()->take(20)->get();

        foreach($bs as $v){
            $p_id = $this->buildParentId($v);
            $v->parent = $p_id;
            $v->save();
        }

    }


    public function buildParentId($bs){
        //get distinct in db
        $list = SB::query()->groupBY('parent')->pluck('parent');

        //merge into array
        $this->parents_stack = array_unique (array_merge ($this->parents_stack, $list->toArray()));

        //random pick one
        $val = $this->parents_stack[array_rand($this->parents_stack)];

        //save current user id into array for next operation
        $bs_id = (string)$bs->id;
        $this->parents_stack[] = $bs_id;

        //return
        return $val;
    }
}
