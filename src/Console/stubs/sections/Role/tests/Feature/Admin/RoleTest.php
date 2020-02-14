<?php

namespace App\Http\Controllers\Role\Tests\Feature\Admin;

use App\Http\Controllers\Role\Models\Role;
use Tests\TestCase;

class RoleTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        $this->signInAdmin();
    }

    /**
     * @test
     */
    public function valid_jalalian_date_index()
    {
        $role = Role::latest('id')->first();
        $this->get(action('Role\Controllers\Admin\RoleController@index'))
             ->assertSee(jdate($role->created_at->timestamp)->format('H:i - Y/m/d'));
    }
}
