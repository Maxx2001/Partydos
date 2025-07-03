<?php

namespace Tests\Unit\Domain\Users\Models;

use Domain\Users\Models\User;
use Domain\Users\Models\UserNotSellData;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserNotSellDataTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_not_sell_data_is_fillable(): void
    {
        $record = new UserNotSellData();
        $fillable = ['user_id'];
        $this->assertEquals($fillable, $record->getFillable());
    }

    public function test_can_create_record_with_fillable_attributes(): void
    {
        $user = User::factory()->create();
        $data = ['user_id' => $user->id];

        $record = UserNotSellData::create($data);

        $this->assertDatabaseHas('user_not_sell_data', $data);
        $this->assertEquals($user->id, $record->user_id);
    }

    public function test_user_relationship(): void
    {
        $user = User::factory()->create();
        $record = UserNotSellData::factory()->create(['user_id' => $user->id]);

        $this->assertInstanceOf(BelongsTo::class, $record->user());
        $this->assertTrue($record->user->is($user));
    }

    public function test_table_name_is_correct(): void
    {
        $record = new UserNotSellData();
        $this->assertEquals('user_not_sell_data', $record->getTable());
    }
}
