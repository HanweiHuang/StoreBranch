<?php

namespace Tests\Unit;

use App\Models\StoreBranch;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class StoreBranchTest extends TestCase
{
    use DatabaseTransactions;
    /**
     * view
     * @test
     * @return void
     */
    public function test_call_view_storebranch_with_no_parameter()
    {
        $this->json('GET','api/viewStoreBranch')
            ->assertStatus(422)
            ->assertJson([
                'message' => "The given data was invalid.",
                "errors" =>[
                    "id" => [
                        "The id field is required."
                    ],
                ]
            ]);
    }

    /**
     * @test
     */
    public function test_call_view_storebranch()
    {
        $sb = new StoreBranch();
        $sb->id = '100';
        $sb->name = 'name';
        $sb->parent = '100';
        $sb->save();
        $this->json('GET','api/viewStoreBranch',['id'=>100])
            ->assertStatus(200)
            ->assertJson([
                'id' => '100',
                'name' => 'name',
                'parent' => '100',
            ]);
    }

    /**
     * @test
     */
    public function test_call_view_storebranch_with_sanitary_parameter(){
        $this->json('GET','api/viewStoreBranch',['id'=>'12%<?ph? 12'])
            ->assertStatus(422);
    }

    /**
     * @test
     * view all notes
     */
    public function test_call_view_all_storebranch(){
        $count = StoreBranch::all()->count();
        $sb = new StoreBranch();
        $sb->id = '100';
        $sb->name = 'name';
        $sb->parent = '100';
        $sb->save();

        $this->json('GET','api/viewAllStoreBranch')->assertJsonCount($count+1);
    }


    /**
     *
     * @test
     * view group store branch
     */
    public function test_call_view_group_storebranch(){
        $sb1 = new StoreBranch();
        $sb1 -> id = 333;
        $sb1 -> name = 'sb1';
        $sb1 -> parent = 333;
        $sb1->save();

        $sb2 = new StoreBranch();
        $sb2 -> id = 334;
        $sb2 -> name = 'sb2';
        $sb2 -> parent = 333;
        $sb2->save();

        $sb3 = new StoreBranch();
        $sb3 -> id = 335;
        $sb3 -> name = 'sb3';
        $sb3 -> parent = 334;
        $sb3->save();

        $sb4 = new StoreBranch();
        $sb4 -> id = 336;
        $sb4 -> name = 'sb4';
        $sb4 -> parent = 334;
        $sb4->save();

        $this->json('GET','api/viewGroupStoreBranch',['id' => 333])->assertJsonCount(4);
        $this->json('GET','api/viewGroupStoreBranch',['id' => 334])->assertJsonCount(3);
        $this->json('GET','api/viewGroupStoreBranch',['id' => 335])->assertJsonCount(1);
        $this->json('GET','api/viewGroupStoreBranch',['id' => 336])->assertJsonCount(1);

    }

    /**
     * @test
     */
    public function test_call_view_group_storebranch_with_no_exist_id(){
        $count = StoreBranch::where('id',1)->count();
        //delete if exist
        if($count > 0 ) StoreBranch::destroy('1');
        $this->json('GET','api/viewGroupStoreBranch',['id' => 1])
            ->assertStatus(404);
    }

    /**
     * @test
     */
    public function test_call_view_group_storebranch_with_wrong_id(){
        $this->json('GET','api/viewGroupStoreBranch',['id' => '<?php echo:22, 《上司 ?>'])
            ->assertStatus(422);
    }

    /**
     * @test
     */
    public function test_call_view_group_storebranch_with_other_parameters(){
        $count = StoreBranch::where('id',1)->count();
        //delete if exist
        if(!$count > 0 ) {
            $sb = new StoreBranch();
            $sb -> id = 1;
            $sb -> name = 'sb';
            $sb -> parent = '1';
            $sb -> save();
        }
        $this->json('GET','api/viewGroupStoreBranch',['id' => 1,'parent'=>12])
            ->assertStatus(200);
    }

    /**
     * delete store branch
     * @test
     */
    public function test_call_delete_group_storebranch(){
        $count = StoreBranch::where('id',1)->count();
        if(!$count > 0 ) {
            $sb1 = new StoreBranch();
            $sb1 -> id = 1;
            $sb1 -> name = 'sb';
            $sb1 -> parent = '1';
            $sb1 -> save();

            $sb2 = new StoreBranch();
            $sb1 -> id = 77;
            $sb2 -> name = 'sb2';
            $sb2 -> parent = '1';
            $sb2 -> save();
        }else{
            $count = StoreBranch::where('id',77)->count();
            if($count>0){
                $obj = StoreBranch::where('id',77)->first();
                $obj->parent = 1;
                $obj->save();
            }else{
                $sb1 = new StoreBranch();
                $sb1 -> id = 77;
                $sb1 -> name = 'sb';
                $sb1 -> parent = 1;
                $sb1 -> save();
            }
        }

        $this->json('DELETE','api/deleteStoreBranch',['id' => 1])
            ->assertStatus(200);
        $count = StoreBranch::where('id',1)->count();
        //delete example
        $this->assertEquals(0,$count);

        //all children
        $this->json('GET','api/viewGroupStoreBranch',['id' => 1])
            ->assertStatus(404);

        $this->json('GET','api/viewStoreBranch',['id' => 77])
            ->assertStatus(404);

    }

    /**
     * @test
     */
    public function test_call_delete_group_with_no_parameter(){
        $this->json('DELETE','api/deleteStoreBranch')
            ->assertStatus(422);
    }

    /**
     * @test
     */
    public function test_call_delete_group_with_sensitive_parameter(){
        $this->json('DELETE','api/deleteStoreBranch',['id' => '<?php echo 1; ?>'])
            ->assertStatus(422);
    }

    /**
     * @test
     */
    public function test_call_delete_group_with_more_parameter(){
        $count = StoreBranch::where('id',1)->count();
        if(!$count > 0 ) {
            $sb1 = new StoreBranch();
            $sb1 ->id = 1;
            $sb1 -> name = 'sb';
            $sb1 -> parent = '1';
            $sb1 -> save();

            $sb2 = new StoreBranch();
            $sb2 ->id = 33;
            $sb2 -> name = 'sb2';
            $sb2 -> parent = '1';
            $sb2 -> save();
        }

        $this->json('DELETE','api/deleteStoreBranch',['id' => '1','parent' => '21'])
            ->assertStatus(200);
    }

    /**
     * @test
     */
    public function test_call_delete_group_with_long_parameter(){
        $this->json('DELETE','api/deleteStoreBranch',['id' => '10001'])
            ->assertStatus(422);
    }

    /**
     * @test
     */
    public function test_call_delete_group_with_short_parameter(){
        $this->json('DELETE','api/deleteStoreBranch',['id' => 0])
            ->assertStatus(422);
    }

    /**
     * move store branch
     * @test
     */
    public function test_call_move_storebranch(){
        $count = StoreBranch::where('id',1)->count();
        if(!$count > 0 ) {
            $sb1 = new StoreBranch();
            $sb1 -> id = 1;
            $sb1 -> name = 'sb';
            $sb1 -> parent = '1';
            $sb1 -> save();

            $sb2 = new StoreBranch();
            $sb2 -> id = 67;
            $sb2 -> name = 'sb2';
            $sb2 -> parent = '1';
            $sb2 -> save();

            $sb3 = new StoreBranch();
            $sb3 -> id = '77';
            $sb3 -> name = 'sb3';
            $sb3 -> parent = 77;
            $sb3 -> save();
        }else{
            $count = StoreBranch::where('id',77)->count();
            if($count>0){
                $obj = StoreBranch::where('id',77)->first();
                $obj->parent = 77;
                $obj->save();
            }else{
                $sb1 = new StoreBranch();
                $sb1 -> id = 77;
                $sb1 -> name = 'sb';
                $sb1 -> parent = 77;
                $sb1 -> save();
            }
        }
        $this->json('GET','api/viewGroupStoreBranch',['id' => 77])
            ->assertJsonCount(1);

        $this->json('PUT','api/moveStoreBranch',['id' => 1,'parent'=>77])
            ->assertStatus(200);

        $re = $this->json('GET','api/viewGroupStoreBranch',['id' => 1]);
        $count = sizeof(json_decode($re->content()));
        $this->json('GET','api/viewGroupStoreBranch',['id' => 77])
            ->assertJsonCount($count+1);

    }

    /**
     * @test
     */
    public function test_call_move_storebranch_to_its_child(){
        $sb1 = new StoreBranch();
        $sb1 -> id = 88;
        $sb1 -> name = 'sb';
        $sb1 -> parent = 88;
        $sb1 -> save();

        $sb2 = new StoreBranch();
        $sb2 -> id = 89;
        $sb2 -> name = 'sb2';
        $sb2 -> parent = 88;
        $sb2 -> save();

        $sb3 = new StoreBranch();
        $sb3 -> id = '77';
        $sb3 -> name = 'sb3';
        $sb3 -> parent = 89;
        $sb3 -> save();

        $this->json('PUT','api/moveStoreBranch',['id' => 88,'parent'=>77])
            ->assertStatus(403);
    }

    /**
     * @test
     */
    public function test_call_move_storebranch_does_not_exist(){
        $count = StoreBranch::where('id',1)->count();
        if($count>0){
            StoreBranch::destroy('1');
        }
        $count = StoreBranch::where('id',2)->count();
        if(!$count>0){
            $sb3 = new StoreBranch();
            $sb3 -> id = '2';
            $sb3 -> name = 'sb3';
            $sb3 -> parent = 2;
            $sb3 -> save();
        }

        $this->json('PUT','api/moveStoreBranch',['id' => 1,'parent'=>2])
            ->assertStatus(404);

    }

    /**
     * @test
     */
    public function test_call_move_storebranch_does_not_exist_parent(){
        $count = StoreBranch::where('id',1)->count();
        if($count>0){
            StoreBranch::destroy('1');
        }
        $count = StoreBranch::where('id',2)->count();
        if(!$count>0){
            $sb3 = new StoreBranch();
            $sb3 -> id = '2';
            $sb3 -> name = 'sb3';
            $sb3 -> parent = 2;
            $sb3 -> save();
        }
        $this->json('PUT','api/moveStoreBranch',['id' => 2,'parent'=>1])
            ->assertStatus(404);
    }

    /**
     * @test
     */
    public function test_call_move_storebranch_move_to_itself(){
        $count = StoreBranch::where('id',2)->count();
        if(!$count>0){
            $sb3 = new StoreBranch();
            $sb3 -> id = '2';
            $sb3 -> name = 'sb3';
            $sb3 -> parent = 2;
            $sb3 -> save();
        }
        $this->json('PUT','api/moveStoreBranch',['id' => 2,'parent'=>2])
            ->assertStatus(403);
    }


    /**
     * update store branch
     */
    public function test_call_update_storebranch(){
        $count = StoreBranch::where('id',2)->count();
        if(!$count>0){
            $sb3 = new StoreBranch();
            $sb3 -> id = '2';
            $sb3 -> name = 'sb3';
            $sb3 -> parent = 2;
            $sb3 -> save();
        }
        $this->json('PUT','api/updateStoreBranch',['id' => 2,'name'=>'update_name'])
            ->assertStatus(200);

        $re = StoreBranch::where('id', 2)->first();
        $this->assertEquals($re->name,'update_name');
    }

    /**
     * @test
     */
    public function test_call_update_storebranch_with_id_only(){
        $this->json('PUT','api/updateStoreBranch',['id' => 2])
            ->assertStatus(422);
    }

    /**
     * @test
     */
    public function test_call_update_storebranch_id_does_not_exist(){
        $count = StoreBranch::where('id',1)->count();
        if($count>0){
            StoreBranch::destroy('1');
        }
        $this->json('PUT','api/updateStoreBranch',['id' => 1, 'name'=>'update_name'])
            ->assertStatus(404);
    }

    /**
     * @test
     */
    public function test_call_update_storebranch_with_long_id(){
        $this->json('PUT','api/updateStoreBranch',['id' => 2002])
            ->assertStatus(422);
    }

    /**
     * add store branch
     * @test
     */
    public function test_call_add_storebranch_with_id_only(){
        $payload = ['id' => 333];
        $this->json('POST','api/addStoreBranch',$payload)
            ->assertStatus(422)
            ->assertJson([
                'message' => 'The given data was invalid.',
                'errors' => [
                    'parent' => [
                        'The parent field is required.'
                    ]
                ],
            ]);
    }

    /**
     * @test
     */
    public function test_call_add_storebranch_with_parent_only(){
        $payload = ['parent' => 333];
        $this->json('POST','api/addStoreBranch',$payload)
            ->assertStatus(422);
    }


    /**
     * @test
     */
    public function test_call_add_storebranch_that_id_already_exist(){
        //add a node
        $sb = new StoreBranch();
        $sb->id = '333';
        $sb->name = 'duplicated';
        $sb->parent = '333';
        $sb->save();

        $payload = ['id' => 333,'parent'=> 333];
        $this->json('POST','api/addStoreBranch',$payload)
            ->assertStatus(422)//success actually
            ->assertJson([
                "message"=> "SB already exist",
                "code"=> "40010",
            ]);
    }

    /**
     * @test
     */
    public function test_call_add_storebranch_parent_id_does_not_exist(){
        $payload = ['id' => 333,'parent'=> 334];
        $this->json('POST','api/addStoreBranch',$payload)
            ->assertStatus(404);//success actually
    }


    /**
     * @test
     */
    public function test_call_add_storebranch_without_parameters(){
        $this->json('POST','api/addStoreBranch')
            ->assertStatus(422)
            ->assertJson([
                'message' => 'The given data was invalid.',
                'errors' => [
                    'id' => [
                        'The id field is required.'
                    ],
                    'parent' => [
                        'The parent field is required.'
                    ]
                ],
            ]);
    }

    /**
     * @test
     */
    public function test_call_add_storebranch_with_sanitary_parameters(){
        $this->json('POST','api/addStoreBranch',['id'=> 'id=id;@# <?php echo?>'])
            ->assertStatus(422)
            ->assertJson([
                'message' => 'The given data was invalid.',
                'errors' => [
                    'id' => ['The id must be a number.'],
                ],
            ]);
    }

    /**
     * @test
     */
    public function test_call_add_storebranch_with_correct_parameters(){
        $sb = new StoreBranch();
        $sb->id = '332';
        $sb->name = 'name';
        $sb->parent = '332';
        $sb->save();

        $payload = ['id' => 333,'parent'=>332];
        $this->json('POST','api/addStoreBranch',$payload)
            ->assertStatus(200);
        //find it in database
        $obj = StoreBranch::where('id',333)->first();
        $this->assertEquals($obj->id, 333);
        $this->assertEquals($obj->parent,332);
    }

    /**
     * @test
     */
    public function test_call_add_storebranch_over_lenth(){
        $payload = ['id' => 1001,'parent'=>1001];
        $this->json('POST','api/addStoreBranch',$payload)
            ->assertStatus(422);
    }

    /**
     * @test
     */
    public function test_call_add_storebranch_under_lenth(){
        $payload = ['id' => 0,'parent'=>0];
        $this->json('POST','api/addStoreBranch',$payload)
            ->assertStatus(422);
    }

}