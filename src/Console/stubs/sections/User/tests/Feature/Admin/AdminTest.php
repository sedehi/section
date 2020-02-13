<?php

namespace App\Http\Controllers\User\Tests\Feature\Admin;

use Tests\TestCase;
use App\Http\Controllers\Role\Models\Role;
use App\Http\Controllers\User\Models\User;
use App\Http\Controllers\User\Models\Admin;

class AdminTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->signInAdmin();
    }

    /**
     * @test
     */
    public function index_admin()
    {
        $admin = factory(Admin::class)->create();
        $user  = factory(User::class)->create();
        $this->get(action('User\Controllers\Admin\AdminController@index'))
             ->assertSee($admin->email)
             ->assertDontSee($user->email);
    }

    /**
     * @test
     */
    public function index_search_admin()
    {
        $adminMustBeShow = factory(Admin::class)->state('withRole')->create([
            'email'      => 'admin@admin.com',
            'first_name' => 'test',
            'last_name'  => 'testian'
        ]);
        $adminDoesntShow = factory(Admin::class)->create();
        $user            = factory(User::class)->create();
        $this->get(action('User\Controllers\Admin\AdminController@index', ['email' => $adminMustBeShow->email]))
             ->assertSee($adminMustBeShow->email)
             ->assertDontSee($user->email)
             ->assertDontSee($adminDoesntShow->email);
        $this->get(action('User\Controllers\Admin\AdminController@index', ['firstname_lastname' => $adminMustBeShow->first_name]))
             ->assertSee($adminMustBeShow->email)
             ->assertDontSee($user->email)
             ->assertDontSee($adminDoesntShow->email);
        $this->get(action('User\Controllers\Admin\AdminController@index', ['role' => $adminMustBeShow->roles->first()->id]))
             ->assertSee($adminMustBeShow->email)
             ->assertDontSee($user->email)
             ->assertDontSee($adminDoesntShow->email);
        $this->get(action('User\Controllers\Admin\AdminController@index', ['role' => 'Invalid']))
             ->assertDontSee($adminMustBeShow->email)
             ->assertDontSee($user->email)
             ->assertDontSee($adminDoesntShow->email);
    }

    /**
     * @test
     */
    public function add_admin_required_validation()
    {
        $this->post(action('User\Controllers\Admin\AdminController@store'))->assertSessionHasErrors([
            'first_name',
            'last_name',
            'email',
            'password',
            'role',
        ]);
    }

    /**
     * @test
     */
    public function add_admin_unique_validation()
    {
        $item = factory(Admin::class)->create();
        $this->post(action('User\Controllers\Admin\AdminController@store'), $item->toArray())->assertSessionHasErrors([
            'email',
            'mobile',
        ]);
    }

    /**
     * @test
     */
    public function add_admin_successfully()
    {
        $item = factory(Admin::class)->make();
        $role = factory(Role::class)->create();
        $this->post(action('User\Controllers\Admin\AdminController@store'), $item->toArray() + [
            'password' => '123456',
            'role'     => [$role->id]
        ])
             ->assertSessionhas('success')
             ->assertRedirect(action('User\Controllers\Admin\AdminController@index'));
        $this->assertDatabaseHas('admins', ['email' => $item->email, 'mobile' => $item->mobile]);
    }

    /**
     * @test
     */
    public function valid_jalalian_date_index_page()
    {
        $user = factory(Admin::class)->create();

        $this->get(action('User\Controllers\Admin\AdminController@index'))
            ->assertSee(
                jdate($user->created_at->timestamp)->format('H:i - Y/m/d')
            );
    }

    /**
     * @test
     */
    public function can_edit_admin()
    {
        $item     = factory(Admin::class)->state('withRole')->create();
        $data     = factory(Admin::class)->make()->toArray();
        $oldRoles = $item->roles->pluck('id')->toArray();
        $newRole  = factory(Role::class)->create();
        $this->patch(action('User\Controllers\Admin\AdminController@update', [$item->id]), $data + [
            'role' => [$newRole->id]
        ])->assertSessionhas('success')
            ->assertRedirect(action('User\Controllers\Admin\AdminController@index'));
        $this->assertDatabaseHas('admins', $data);
        $this->assertCount(0, $item->roles()->find($oldRoles));
        $this->assertCount(1, $item->roles()->find([$newRole->id]));
    }

    /**
     * @test
     */
    public function can_delete_admin()
    {
        $item = factory(Admin::class)->state('withRole')->create();
        $this->delete(action('User\Controllers\Admin\AdminController@destroy', [$item->id]), [
            'deleteId' => [$item->id]
        ])->assertSessionhas('success')
            ->assertRedirect(action('User\Controllers\Admin\AdminController@index'));
        $this->assertSoftDeleted('admins', [
            'id'    => $item->id
        ]);
    }
}
