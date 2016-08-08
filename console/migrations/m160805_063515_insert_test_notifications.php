<?php

use yii\db\Migration;

class m160805_063515_insert_test_notifications extends Migration
{
    public function up()
    {
        $this->insert('{{%notification}}', [
            'id' => 1,
            'user_id' => 2,
            'title'=> 'Lower heart rate',
            'description' => 'Your goal is halway complete!',
            'viewed' => 1,
            'type' => 'positivity',
            'tag' => 'goal',
            'created_at' => 1469555555,
            'updated_at'=> 1470319456
        ]);

        $this->insert('{{%notification}}', [
            'id' => 2,
            'user_id' => 1,
            'title'=> 'Unhealty data detected',
            'description' => 'Unusually low hrv detected',
            'viewed' => 0,
            'type' => 'negativity',
            'tag' => 'tip',
            'created_at' => 1469589326,
            'updated_at'=> 1469589330
        ]);

        $this->insert('{{%notification}}', [
            'id' => 3,
            'user_id' => 2,
            'title'=> 'Deeper sleep',
            'description' => 'You have achieved your goal!',
            'viewed' => 0,
            'type' => 'positivity',
            'tag' => 'tip',
            'created_at' => 1469589326,
            'updated_at'=> 1469589330
        ]);

        $this->insert(
            '{{%notification}}',
            [
                'id' => 4,
                'user_id' => 1,
                'title'=> 'Pillow',
                'description' => 'Use your arctic pillow tonight',
                'viewed' => 0,
                'type' => 'neutrality',
                'tag' => 'experiment',
                'created_at' => 1469589326,
                'updated_at'=> 1469589340
            ]
        );

        $this->insert(
            '{{%notification}}',
            [
                'id' => 5,
                'user_id' => 2,
                'title'=> 'Fall asleep faster',
                'description' => 'You have achieved your goal!',
                'viewed' => 0,
                'type' => 'negativity',
                'tag' => 'tip',
                'created_at' => 1469589568,
                'updated_at'=> 1469589570
            ]
        );

        $this->insert(
            '{{%notification}}',
            [
                'id' => 6,
                'user_id' => 1,
                'title'=> 'Weekly report',
                'description' => 'Your goal is halway complete!',
                'viewed' => 1,
                'type' => 'neutrality',
                'tag' => 'report',
                'created_at' => 1469589568,
                'updated_at'=> 1469589590
            ]
        );

        $this->insert(
            '{{%notification}}',
            [
                'id' => 7,
                'user_id' => 2,
                'title'=> 'Healthy streak',
                'description' => 'Ideal length 5 nights in a row',
                'viewed' => 1,
                'type' => 'positivity',
                'tag' => 'report',
                'created_at' => 1469555555,
                'updated_at'=> 1470319456
            ]
        );

        $this->insert(
            '{{%notification}}',
            [
                'id' => 8,
                'user_id' => 1,
                'title'=> 'Increase sleep time',
                'description' => 'Your goal is waiting',
                'viewed' => 0,
                'type' => 'negativity',
                'tag' => 'goal',
                'created_at' => 1469555555,
                'updated_at'=> 1470319456
            ]
        );

        $this->insert(
            '{{%notification}}',
            [
                'id' => 9,
                'user_id' => 2,
                'title'=> 'Lower heart rate',
                'description' => 'Your goal is halway complete!',
                'viewed' => 1,
                'type' => 'positivity',
                'tag' => 'experiment',
                'created_at' => 1469555555,
                'updated_at'=> 1470319456
            ]
        );

        $this->insert(
            '{{%notification}}',
            [
                'id' => 10,
                'user_id' => 2,
                'title'=> 'New window in room',
                'description' => 'Trying to sleep with new window',
                'viewed' => 0,
                'type' => 'positivity',
                'tag' => 'experiment',
                'created_at' => 1469555555,
                'updated_at'=> 1470319456
            ]
        );
    }

    public function down()
    {
        $this->delete('notification', ['id' => 1]);
        $this->delete('notification', ['id' => 2]);
        $this->delete('notification', ['id' => 3]);
        $this->delete('notification', ['id' => 4]);
        $this->delete('notification', ['id' => 5]);
        $this->delete('notification', ['id' => 6]);
        $this->delete('notification', ['id' => 7]);
        $this->delete('notification', ['id' => 8]);
        $this->delete('notification', ['id' => 9]);
        $this->delete('notification', ['id' => 10]);
    }
}
